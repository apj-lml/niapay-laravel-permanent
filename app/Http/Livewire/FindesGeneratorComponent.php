<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\User;
use App\Models\NewPayrollIndex;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

use ZipArchive;

class FindesGeneratorComponent extends Component
{
    public $payrollDateFrom, $payrollDateTo, $frequency, $isLessFifteen = 0, $filterFund, $filterSection;

    public function generateFindes($filterSection = null, $filterFund = null)
    {
        DB::statement("SET SQL_MODE=''");

        $newPayroll = NewPayrollIndex::with([
            'user.fund',
            'user.agencyUnit.agencySection',
        ])
            ->where('period_covered_from', $this->payrollDateFrom)
            ->where('period_covered_to', $this->payrollDateTo)
            ->where('is_less_fifteen', $this->isLessFifteen);

        if ($filterSection !== null) {
            $newPayroll->where('office', $filterSection);
        }

        if ($filterFund !== null) {
            $newPayroll->whereHas('user.fund', function ($query) {
                $query->where('id', $this->filterFund);
            });
        }

        // ✅ Group only by FUND
        $funds = $newPayroll->get()
            ->groupBy(function ($item) {
                return ($item->funding_charges ?? 'Unknown Fund') . '|' . ($item->fund_acct_no ?? 'N/A');
            });

        if ($funds->isEmpty()) {
            return collect([]);
        }

        // ✅ Initialize ZIP
        $zip = new ZipArchive();
        $zipFileName = 'FINDES_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path("app/findes/{$zipFileName}");

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $tempFiles = [];

        foreach ($funds as $fundKey => $payrollEntries) {
            [$fundName, $fundAcctNo] = explode('|', $fundKey);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $rowStart = 1;

            // Optional headers
            // $sheet->setCellValue("A{$rowStart}", 'ACCOUNT NO');
            // $sheet->setCellValue("B{$rowStart}", 'EMPLOYEE NAME');
            // $sheet->setCellValue("C{$rowStart}", 'AMOUNT');
            // $rowStart++;

            foreach ($payrollEntries as $npiUser) {
                $amount = 0;
                if ($this->frequency == 'full_month') {
                    $amount = $npiUser->first_half_basic_pay + $npiUser->second_half_basic_pay;
                } else if ($this->frequency == 'first_half') {
                    $amount = $npiUser->first_half_basic_pay;
                } else if ($this->frequency == 'second_half') {
                    $amount = $npiUser->second_half_basic_pay;
                }

                $amount = str_replace(".", "", $amount);
                $fundAcctNo = str_replace(" ", "", $fundAcctNo);

                $sheet->setCellValue("A{$rowStart}", $npiUser->atm_no ?? 'N/A');
                $sheet->setCellValue("B{$rowStart}", str_replace(['Ñ', 'ñ', '-'], ['N', 'n', ' '], $npiUser->name));
                $sheet->setCellValue("C{$rowStart}", $amount == 0 ? '000' : $amount);

                $rowStart++;
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setLineEnding("\r\n");
            $writer->setSheetIndex(0);

            $fileName = "{$fundName}_" . now()->format('Y_m') . ".csv";
            $tempPath = storage_path("app/findes/tmp/{$fileName}");

            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0777, true);
            }

            $writer->save($tempPath);

            // Fix quoting for column B
            $lines = file($tempPath, FILE_IGNORE_NEW_LINES);
            foreach ($lines as &$line) {
                $cols = str_getcsv($line);
                if (isset($cols[1])) {
                    $cols[1] = '"' . $cols[1] . '"';
                }
                $line = implode(',', $cols);
            }
            file_put_contents($tempPath, implode("\r\n", $lines));

            // Add to ZIP
            $zip->addFile($tempPath, basename($tempPath));
            $tempFiles[] = $tempPath;
        }

        $zip->close();

        // ✅ Clean up temp files
        foreach ($tempFiles as $file) {
            @unlink($file);
        }

        @rmdir(storage_path('app/findes/tmp'));

        // ✅ Trigger browser download
        $this->dispatchBrowserEvent('fileDownload', [
            'url' => route('download.findes', ['filename' => $zipFileName])
        ]);

        return $zipPath;
    }

    public function render()
    {
        return view('livewire.findes-generator-component');
    }
}
