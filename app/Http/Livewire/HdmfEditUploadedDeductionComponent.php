<?php

namespace App\Http\Livewire;

use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HdmfEditUploadedDeductionComponent extends Component
{
    public $file;
    public $selectedDeductionType;
    public $listOfEmployees;

    public $listOfToBeNotUpdated = [];
    public $listOfCannotFindInDatabase = [];
    public $listOfFinalToBeUpdated = [];

    public $listTobeSaved = [];

    public function mount($file, $selectedDeductionType)
    {
        $this->file = $file;
        $this->selectedDeductionType = $selectedDeductionType;

        // Get all employees that should be included in payroll
        $this->listOfEmployees = User::with("employeeDeductions")->where('include_to_payroll', 1)->get();
        $filePath = storage_path("app/temp/" . $file);

        // Load Excel file
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Get headers from row 5
        $headersRow = $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . '1')[0];

        $data = [];

        foreach ($sheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // if (array_filter($rowData)) {
            //     $data[] = array_combine($headersRow, $rowData);
            // }

            if (array_filter($rowData)) {
                $rowAssoc = array_combine($headersRow, $rowData);
        
                // Check and convert date columns
                if (!empty($rowAssoc['start_term']) && is_numeric($rowAssoc['start_term'])) {
                    $rowAssoc['start_term'] = Date::excelToDateTimeObject($rowAssoc['start_term'])->format('Y-m-d');
                }
        
                if (!empty($rowAssoc['end_term']) && is_numeric($rowAssoc['end_term'])) {
                    $rowAssoc['end_term'] = Date::excelToDateTimeObject($rowAssoc['end_term'])->format('Y-m-d');
                }
        
                $data[] = $rowAssoc;
            }

        }

        dd($data);

        // === Step 1: Build quick lookup of employees by HDMF ===
        $employeeMapByHdmf = $this->listOfEmployees->keyBy('hdmf'); // [hdmf => User]

        // Track all HDMF from DB for later comparison
        $allEmployeeHdmf = $this->listOfEmployees->pluck('hdmf')->filter()->all();

        // === Step 2: Match Excel data to DB employees ===
        $seenHdmfFromExcel = [];


        // dd($data);

        foreach ($data as $row) {
            $bpno = trim($row['BPNO'] ?? ''); // HDMF from Excel

            if (!$bpno) {
                continue; // Skip rows with empty BPNO
            }

            $seenHdmfFromExcel[] = $bpno;

            if (isset($employeeMapByHdmf[$bpno])) {
                $employee = $employeeMapByHdmf[$bpno];

                // Combine with ID from DB
                $this->listOfFinalToBeUpdated[] = [
                    'id' => $employee->id,
                    'excel_data' => $row
                ];
            } else {
                // Not found in database
                $this->listOfCannotFindInDatabase[] = $row;
            }
        }

        // === Step 3: Check employees NOT in Excel ===
        $this->listOfToBeNotUpdated = $this->listOfEmployees->filter(function ($user) use ($seenHdmfFromExcel) {
            return $user->hdmf && !in_array($user->hdmf, $seenHdmfFromExcel);
        })->values()->all();


        // === Step 4: Prepare final list to be saved ===
        foreach ($this->listOfFinalToBeUpdated as $row) {
            $entry = ['id' => $row['id']];

            if ($row['excel_data']['PS'] > 0 && $this->validateValueWithChanges($row['id'], 'PS', $row['excel_data']['PS'])) {
                $entry['PS'] = $row['excel_data']['PS'];
                $entry['deduction_id'] = 9; // 9 is the ID for HDMF PREMIUM
            }
        
            if ($row['excel_data']['SALARY_LOAN'] > 0 && $this->validateValueWithChanges($row['id'], 'SALARY_LOAN', $row['excel_data']['SALARY_LOAN'])) {
                $entry['SALARY_LOAN'] = $row['excel_data']['SALARY_LOAN'];
                $entry['deduction_id'] = 10; // 10 is the ID for HDMF SALARY LOAN
            }
        
            if ($row['excel_data']['MPL'] > 0 && $this->validateValueWithChanges($row['id'], 'MPL', $row['excel_data']['MPL'])) {
                $entry['MPL'] = $row['excel_data']['MPL'];
                $entry['deduction_id'] = 11; // 11 is the ID for HDMF MPL
            }

            if ($row['excel_data']['MPL_LITE'] > 0 && $this->validateValueWithChanges($row['id'], 'MPL_LITE', $row['excel_data']['MPL_LITE'])) {
                $entry['MPL_LITE'] = $row['excel_data']['MPL_LITE'];
                $entry['deduction_id'] = 14; // 14 is the ID for HDMF MPL_LITE
            }

            if ($row['excel_data']['PLREG'] > 0 && $this->validateValueWithChanges($row['id'], 'PLREG', $row['excel_data']['PLREG'])) {
                $entry['PLREG'] = $row['excel_data']['PLREG'];
                $entry['deduction_id'] = 13; // 13 is the ID for HDMF PLREG
            }

            if ($row['excel_data']['CPL'] > 0 && $this->validateValueWithChanges($row['id'], 'CPL', $row['excel_data']['CPL'])) {
                $entry['CPL'] = $row['excel_data']['CPL'];
                $entry['deduction_id'] = 12; // 12 is the ID for HDMF CPL
            }
        
            // Only push if there's more than just the ID
            if (count($entry) > 1) {
                $this->listTobeSaved[] = $entry;
            }
        }

        // dd($this->listOfCannotFindInDatabase);
    }

    public function render()
    {
        return view('livewire.hdmf-edit-uploaded-deduction-component');
    }
}
