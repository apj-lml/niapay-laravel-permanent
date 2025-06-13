<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class EditUploadedDeductionComponent extends Component
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
        $headersRow = $sheet->rangeToArray('A5:' . $sheet->getHighestColumn() . '5')[0];

        $data = [];
        foreach ($sheet->getRowIterator(6) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            if (array_filter($rowData)) {
                $data[] = array_combine($headersRow, $rowData);
            }
        }

        // === Step 1: Build quick lookup of employees by GSIS ===
        $employeeMapByGsis = $this->listOfEmployees->keyBy('gsis'); // [gsis => User]

        // Track all GSIS from DB for later comparison
        $allEmployeeGsis = $this->listOfEmployees->pluck('gsis')->filter()->all();

        // === Step 2: Match Excel data to DB employees ===
        $seenGsisFromExcel = [];


        // dd($data);

        foreach ($data as $row) {
            $bpno = trim($row['BPNO'] ?? ''); // GSIS from Excel

            if (!$bpno) {
                continue; // Skip rows with empty BPNO
            }

            $seenGsisFromExcel[] = $bpno;

            if (isset($employeeMapByGsis[$bpno])) {
                $employee = $employeeMapByGsis[$bpno];

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
        $this->listOfToBeNotUpdated = $this->listOfEmployees->filter(function ($user) use ($seenGsisFromExcel) {
            return $user->gsis && !in_array($user->gsis, $seenGsisFromExcel);
        })->values()->all();


        // === Step 4: Prepare final list to be saved ===
        foreach ($this->listOfFinalToBeUpdated as $row) {
            $entry = ['id' => $row['id']];

            if ($row['excel_data']['PS'] > 0 && $this->validateValueWithChanges($row['id'], 'PS', $row['excel_data']['PS'])) {
                $entry['PS'] = $row['excel_data']['PS'];
                $entry['deduction_id'] = 9; // 9 is the ID for GSIS PREMIUM
            }
        
            if ($row['excel_data']['SALARY_LOAN'] > 0 && $this->validateValueWithChanges($row['id'], 'SALARY_LOAN', $row['excel_data']['SALARY_LOAN'])) {
                $entry['SALARY_LOAN'] = $row['excel_data']['SALARY_LOAN'];
                $entry['deduction_id'] = 10; // 10 is the ID for GSIS SALARY LOAN
            }
        
            if ($row['excel_data']['MPL'] > 0 && $this->validateValueWithChanges($row['id'], 'MPL', $row['excel_data']['MPL'])) {
                $entry['MPL'] = $row['excel_data']['MPL'];
                $entry['deduction_id'] = 11; // 11 is the ID for GSIS MPL
            }

            if ($row['excel_data']['MPL_LITE'] > 0 && $this->validateValueWithChanges($row['id'], 'MPL_LITE', $row['excel_data']['MPL_LITE'])) {
                $entry['MPL_LITE'] = $row['excel_data']['MPL_LITE'];
                $entry['deduction_id'] = 14; // 14 is the ID for GSIS MPL_LITE
            }

            if ($row['excel_data']['PLREG'] > 0 && $this->validateValueWithChanges($row['id'], 'PLREG', $row['excel_data']['PLREG'])) {
                $entry['PLREG'] = $row['excel_data']['PLREG'];
                $entry['deduction_id'] = 13; // 13 is the ID for GSIS PLREG
            }

            if ($row['excel_data']['CPL'] > 0 && $this->validateValueWithChanges($row['id'], 'CPL', $row['excel_data']['CPL'])) {
                $entry['CPL'] = $row['excel_data']['CPL'];
                $entry['deduction_id'] = 12; // 12 is the ID for GSIS CPL
            }
        
            // Only push if there's more than just the ID
            if (count($entry) > 1) {
                $this->listTobeSaved[] = $entry;
            }
        }

        // dd($this->listOfCannotFindInDatabase);
    }

    public function updateListToBeSaved($id, $dedType, $value)
    {
        // Find the entry in the final list to update

        $found = false;

        foreach ($this->listTobeSaved as &$entry) {
            if ($entry['id'] == $id) {
                if ($value > 0) {
                    $entry[$dedType] = $value;
                } else {
                    // Remove the key if unchecked (set to 0)
                    unset($entry[$dedType]);
                }
                $found = true;
                break;
            }
        }
        unset($entry);
    
        if (! $found && $value > 0) {
            $this->listTobeSaved[] = [
                'id' => $id,
                $dedType => $value,
            ];
        } 
        
    }

    public function validateValueWithChanges($id, $dedType, $value){
        $user = User::find($id);
        $deductionMap = [
            'PS' => 9,
            'SALARY_LOAN' => 10,
            'MPL' => 11,
            'CPL' => 12,
            'PLREG' => 13,
            'MPL_LITE' => 14,
        ];
        $DbDeduction = $deductionMap[$dedType] ?? null;
        // $DbDeduction = 9; // 9 is the ID for GSIS PREMIUM
        // if($dedType == 'PS'){
        //     $DbDeduction = 9; // 9 is the ID for GSIS PREMIUM
        // } elseif($dedType == 'SALARY_LOAN'){
        //     $DbDeduction = 10; // 10 is the ID for GSIS SALARY LOAN
        // } elseif($dedType == 'MPL'){
        //     $DbDeduction = 11; // 11 is the ID for GSIS MPL
        // } elseif($dedType == 'MPL_LITE'){
        //     $DbDeduction = 14; // 14 is the ID for GSIS MPL_LITE
        // } elseif($dedType == 'PLREG'){
        //     $DbDeduction = 13; // 13 is the ID for GSIS PLREG
        // } elseif($dedType == 'CPL'){
        //     $DbDeduction = 12; // 12 is the ID for GSIS CPL
        // }
        $currentDeduction = $user->employeeDeductions()->where('deduction_id', $DbDeduction)->first();
        if ($currentDeduction) {
            $currentValue = $currentDeduction->pivot->amount;
            if ($value != $currentValue) {
                return true; // Value has changed
            }
        } else {
            // If no current deduction, any value > 0 is a change
            return $value > 0;
        }
        return false; // No change
        // dd($this->listTobeSaved);
    }

    public function saveRecords(){

        foreach ($this->listTobeSaved as $entry) {
            $user = User::find($entry['id']);
            if ($user) {
                foreach ($entry as $key => $value) {
                    
                    if ($key != 'id') {
                        $deductionId = null;
                        if ($key == 'PS') {
                            $deductionId = 9; // GSIS PREMIUM
                        } elseif ($key == 'SALARY_LOAN') {
                            $deductionId = 10; // GSIS SALARY LOAN
                        } elseif ($key == 'MPL') {
                            $deductionId = 11; // GSIS MPL
                        } elseif ($key == 'MPL_LITE') {
                            $deductionId = 14; // GSIS MPL_LITE
                        } elseif ($key == 'PLREG') {
                            $deductionId = 13; // GSIS PLREG
                        } elseif ($key == 'CPL') {
                            $deductionId = 12; // GSIS CPL
                        }

                        if ($deductionId) {
                            $user->employeeDeductions()->syncWithoutDetaching([
                                $deductionId => ['amount' => $value, 'frequency' => 1]
                            ]);
                        }
                    }
                }
            }
        }

        session()->flash('message', 'Records updated successfully!');
        return redirect()->route('edit-uploaded-deductions-landing-page', ['file' => $this->file, 'selectedDeductionType' => $this->selectedDeductionType]);

    }

    public function ddMe()
    {
       return dd($this->listTobeSaved);
    }

    public function render()
    {
        return view('livewire.edit-uploaded-deduction-component');
    }
}
