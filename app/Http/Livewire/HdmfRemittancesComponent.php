<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Deduction;
use App\Models\NewPayrollIndex;
use App\Models\Fund;
use App\Models\AgencySection;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HdmfRemittancesComponent extends Component
{
    public $payrollDateFrom, $payrollDateTo, $loadingTxt = "", $loadingProgress = 0, $loadingState = false, $listOfDeductions, $deduction = 1;

    public function mount()
    {
        $this->listOfDeductions = Deduction::where('deduction_group', 'HDMF')->get();
        $this->listOfDeductions = [
            ['id' => 1, 'description' => 'HDMF Premium'],
            ['id' => 2, 'description' => 'HDMF M2'],
            ['id' => 3, 'description' => 'HDMF MPL'],
            ['id' => 4, 'description' => 'HDMF CL'],
        ];
    }

    public function createExcelFile($filterSection = null, $filterFund = null)
    {
        $templatePath = storage_path('app/excel_templates/f_hdmf_prem_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        $payrollDateFrom = Carbon::parse($this->payrollDateFrom)->format('Y-m-d');
        $payrollDateTo = Carbon::parse($this->payrollDateTo)->format('Y-m-d');

        DB::statement("SET SQL_MODE=''"); // Allow GROUP BY
        $deduction = $this->deduction;

        // $funds = Fund::whereHas('users', function ($query) use ($filterSection, $deduction) {
        //     $query->where('is_active', 1)
        //         ->whereIn('employment_status', ['PERMANENT', 'COTERMINOUS'])
        //         ->where('include_to_payroll', 1);

        //     $query->whereHas('employeeDeductions', function ($subQuery) use ($deduction) {
        //         $subQuery->where('deduction_id', $deduction);
        //     });
    
        //     if ($filterSection !== null) {
        //         $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
        //             $subQuery->where('office', $filterSection);
        //         });
        //     }
        // })
        // // ->with([
        // //     'users' => function ($query) {
        // //         $query->where('is_active', 1)
        // //             ->whereIn('employment_status', ['PERMANENT', 'COTERMINOUS'])
        // //             ->where('include_to_payroll', 1);
        // //     }
        // // ])
        // ->get();

        $newPayroll = NewPayrollIndex::with([
            'user.fund',
            'user.agencyUnit.agencySection',
            'newPayrollIndexAllDed'
        ])
            ->where('period_covered_from', $this->payrollDateFrom)
            ->where('period_covered_to', $this->payrollDateTo)
            ->whereHas('newPayrollIndexAllDed', function ($query) use ($deduction) {
                $query->where('npiad_deduction_id', $deduction);
                $query->where('npiad_type', 'DEDUCTION');
            });
        
        if ($filterSection !== null) {
            $newPayroll->where('office', $filterSection);
        }
        
        if ($filterFund !== null) {
            $newPayroll->whereHas('user.fund', function ($query) {
                $query->where('id', $this->filterFund);
            });
        }
        
        $funds = $newPayroll->get()
            ->groupBy(function ($item) {
                return $item->funding_charges ?? 'Unknown Fund';
            })
            ->map(function ($groupedByFund) {
                return $groupedByFund->groupBy(function ($item) {
                    return $item->office; // Office-level grouping
                });
            });

    
    
        // Apply fund filtering if needed
        // if ($filterFund !== null && $filterFund != 0) {
        //     $funds = $funds->where('id', $filterFund);
        // }
    
        if ($funds->isEmpty()) {
            return collect([]);
        }
    
        // foreach ($funds as $fund) {
        //     // Fetch agency sections that have users within the same fund
        //     $fund->sections = AgencySection::whereHas('users', function ($query) use ($fund) {
        //         $query->where('fund_id', $fund->id)
        //             ->whereIn('employment_status', ['PERMANENT', 'COTERMINOUS'])
        //             ->where('is_active', 1)
        //             ->where('include_to_payroll', 1);
        //     })
        //     ->with(['users' => function ($query) use ($fund) {
        //         $query->where('fund_id', $fund->id)
        //             ->whereIn('employment_status', ['PERMANENT', 'COTERMINOUS'])
        //             ->where('is_active', 1)
        //             ->where('include_to_payroll', 1);
        //     }])
        //     ->get()
        //     ->groupBy('office'); // Group sections by office name
        // }


        // if ($filterFund !== null && $filterFund != 0) {
        //     $funds = $funds->where('id', $filterFund);
        // }

    foreach ($funds as $fundName => $offices) {


            // foreach($payrollSection as $section){
                // Clone the template sheet for each fund


                foreach($offices as $officeName => $payrollEntries){

                    $templateSheet = $spreadsheet->getSheet(0);
                    $newSheet = clone $templateSheet;
    
                    $sheetName = Str::limit(preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', $fundName ." ". $officeName), 31, '');
                    $newSheet->setTitle($sheetName ?: 'HDMF_PREM');
    
                    // Add the sheet to the spreadsheet
                    $spreadsheet->addSheet($newSheet);
    
                    $totalRemittance = 0;
                    $counter = 0;
    
                    $rowStart = 11; // Start inserting rows at 11

                    foreach($payrollEntries as $npiUser) {

                        $rt = 'DT';
                        $plt = 'MC';
                        $er = 200;
                        $deduction;

                        $deductionRecord = $npiUser->newPayrollIndexAllDed()
                        ?->where('npiad_deduction_id', $deduction)
                        ->first();

                        if ($deduction == 2) {
                            $plt = 'M2';
                            $er = 0; // M2 has no employer remittance
                        } elseif ($deduction == 3) {
                            $plt = 'ST'; // MPL
                            $er = 0; // ST has no employer remittance
                        } elseif ($deduction == 4) {
                            $plt = 'CL'; // CL
                            $er = 0; // CL has no employer remittance
                        }

       
                        $amount = $deductionRecord?->npiad_amount ?? 0;
                        $application_no = $deductionRecord?->application_no ?? null;

                        // Insert data for each user
                        $newSheet->insertNewRowBefore($rowStart);
                        $newSheet->setCellValue("A{$rowStart}", $rt);
                        $newSheet->setCellValue("B{$rowStart}", $npiUser?->hdmf);
                        $newSheet->setCellValue("C{$rowStart}", $application_no);
                        $newSheet->setCellValue("D{$rowStart}", $npiUser?->last_name);
                        $newSheet->setCellValue("E{$rowStart}", '=TRIM("' . $npiUser?->first_name . ' ' . $npiUser?->name_extn . '")');
                        $newSheet->setCellValue("F{$rowStart}", $npiUser?->npiad_middle_name);
                        $newSheet->setCellValue("G{$rowStart}", $amount);
                        $newSheet->setCellValue("H{$rowStart}", $er);
                        $newSheet->setCellValue("I{$rowStart}", $plt);

                        $formattedDate = Carbon::parse($npiUser?->period_covered_to)->format('Ym');
                        $newSheet->setCellValue("J{$rowStart}", $formattedDate . '01');

                        $totalRemittance += $amount;

                        $rowStart++; // Move to next row
                        $counter++;
                    }

                $newSheet->setCellValue('C6', $counter);

                }

                // Set totals
                $newSheet->setCellValue('C5', $totalRemittance);

                // Remove the template row
                $newSheet->removeRow(10);
                    // }
                // }
            }

        // Delete the original template sheet
        $spreadsheet->removeSheetByIndex(0);

        // Save the Excel file to storage temporarily
        $fileName = 'hdmf_prem_' . now()->format('Ymd_His') . '.xlsx';
        $modifiedPath = storage_path("app/hdmf_reports/{$fileName}");
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($modifiedPath);

        // Dispatch browser event to trigger download
        $this->dispatchBrowserEvent('fileDownload', [
            'url' => route('download.remittance', ['filename' => $fileName])
        ]);

        return $modifiedPath; // Optional: return path if you also want to use it later

    }

    public function generateRemittanceTemplate()
    {
        $this->createExcelFile();
    }


    public function render()
    {
        return view('livewire.hdmf-remittances-component');
    }
}
