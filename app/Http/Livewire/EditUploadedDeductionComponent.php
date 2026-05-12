<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;

class EditUploadedDeductionComponent extends Component
{
    public $file;
    public $selectedDeductionType;
    public $listOfEmployees;

    public $listOfToBeNotUpdated = [];
    public $listOfCannotFindInDatabase = [];
    public $listOfFinalToBeUpdated = [];

    public $listTobeSaved = [];

    public $deductionMap = [
        'PS' => 9,
        'CONSOLOAN' => 10,
        'MPL' => 11,
        'CPL' => 12,
        'PLREG' => 13,
        'MPL_LITE' => 14,
        'EMRGYLN' => 15,
        'GFAL' => 16,
        'SALARY_LOAN' => 17,
    ];

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

        // === Step 3: Employees NOT in Excel ===
        $listNotInExcel = $this->listOfEmployees->filter(function ($user) use ($seenGsisFromExcel) {
            return $user->gsis && !in_array($user->gsis, $seenGsisFromExcel);
        });

        // === Step 3.5: Employees with a loan in DB but Excel shows 0 ===
        $deductionIds = $this->deductionMap;

        $loanMismatchList = $this->listOfEmployees->filter(function ($user) use ($deductionIds) {

            foreach ($deductionIds as $dedType => $dedId) {
                // If user has loan in DB AND loan in Excel = 0 → mismatch
                if ($this->validateIsWithDeduction($user->id, $dedType, 0)) {
                    return true;
                }
            }

            return false;
        });

        // === MERGE both lists and REMOVE duplicates ===
        $this->listOfToBeNotUpdated = $listNotInExcel
            ->merge($loanMismatchList)
            ->unique('id')
            ->values();



        // === FORMAT deductions for table display ===
        $this->listOfToBeNotUpdated = $this->listOfToBeNotUpdated->map(function ($user) use ($data) {

            // Create default 0 values
            $deductions = [
                'PS'          => 0,
                'CONSOLOAN'   => 0,
                'SALARY_LOAN' => 0,
                'MPL'         => 0,
                'MPL_LITE'    => 0,
                'PL_REG'      => 0,
                'EMRGYLN'     => 0,
                'GFAL'        => 0,
                'CPL'         => 0
            ];

            // Merge actual deductions
            foreach ($user['employeeDeductions'] ?? [] as $ded) {
                if($ded['deduction_group'] != 'GSIS'){
                    continue;
                }
                $type = $ded['deduction_type'] ?? null;
                if ($type == 'GSIS_PREM') {
                    $type = 'PS'; //in excel it's PS
                }
                if ($type == 'PL_REG') {
                    $type = 'PLREG'; //in excel it's PLREG
                }
                if ($type == 'GSIS_MPL') {
                    $type = 'MPL'; //in excel it's MPL
                }
                if ($type == 'CONSO_LOAN') {
                    $type = 'CONSOLOAN'; //in excel it's CONSOLOAN
                }
                if ($type && isset($deductions[$type])) {
                    foreach ($data as $row) {
                        $bpno = trim($row['BPNO'] ?? ''); // GSIS from Excel
                        if ($bpno == $user['gsis']) {
                            $excelVal = (float)$row[$type];
                            $dbVal = (float) ($ded['pivot']['amount'] ?? 0);
                            if ($excelVal == 0 && $dbVal > 0) {
                                // dd($type);
                                // if($type == "MPL"){
                                //     dd($excelVal, $dbVal);

                                // }

                                $deductions[$type] = $dbVal;
                            }
                            break;
                        }
                    }

                    // $deductions[$type] = $ded['pivot']['amount'] ?? $ded['amount'] ?? 0;
                }
            }
            // Attach processed values to the user
            $user['deductions_flat'] = $deductions;

            return $user;
        });

        // === Step 4: Prepare final list to be saved ===
        foreach ($this->listOfFinalToBeUpdated as $row) {
            $entry = ['id' => $row['id']];
            $entry['crn'] =  $row['excel_data']['CRN'];
            
            $entry['gsis_birthdate'] = !empty($row['excel_data']['BirthDate'])
            ? Carbon::parse($row['excel_data']['BirthDate'])->format('Y-m-d')
            : null;
            $entry['effectivity_date'] = !empty($row['excel_data']['Effectivity Date'])
            ? Carbon::parse($row['excel_data']['Effectivity Date'])->format('Y-m-d')
            : null;

            if ($row['excel_data']['PS'] > 0 && $this->validateValueWithChanges($row['id'], 'PS', $row['excel_data']['PS'])) {
                $entry['PS'] = $row['excel_data']['PS'];
                $entry['deduction_id'] = 9; // 9 is the ID for GSIS PREMIUM
            }
        
            if ($row['excel_data']['CONSOLOAN'] > 0 && $this->validateValueWithChanges($row['id'], 'CONSOLOAN', $row['excel_data']['CONSOLOAN'])) {
                $entry['CONSOLOAN'] = $row['excel_data']['CONSOLOAN'];
                $entry['deduction_id'] = 10; // 10 is the ID for GSIS SALARY LOAN
            }

            if ($row['excel_data']['SALARY_LOAN'] > 0 && $this->validateValueWithChanges($row['id'], 'SALARY_LOAN', $row['excel_data']['SALARY_LOAN'])) {
                $entry['SALARY_LOAN'] = $row['excel_data']['SALARY_LOAN'];
                $entry['deduction_id'] = 17; // 17 is the ID for GSIS SALARY LOAN
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

            if ($row['excel_data']['EMRGYLN'] > 0 && $this->validateValueWithChanges($row['id'], 'EMRGYLN', $row['excel_data']['EMRGYLN'])) {
                $entry['EMRGYLN'] = $row['excel_data']['EMRGYLN'];
                $entry['deduction_id'] = 15; // 13 is the ID for GSIS EMRGYLN
            }

            if ($row['excel_data']['GFAL'] > 0 && $this->validateValueWithChanges($row['id'], 'GFAL', $row['excel_data']['GFAL'])) {
                $entry['GFAL'] = $row['excel_data']['GFAL'];
                $entry['deduction_id'] = 16; // 13 is the ID for GSIS GFAL
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
        $deductionMap = $this->deductionMap;

        $DbDeduction = $deductionMap[$dedType] ?? null;
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
    }

    public function validateIsWithDeduction($id, $dedType, $value){
        $user = User::find($id);
        $deductionMap = $this->deductionMap;
        $DbDeduction = $deductionMap[$dedType] ?? null;
        $currentDeduction = $user->employeeDeductions()->where('deduction_id', $DbDeduction)->first();
        if ($currentDeduction) {
            $currentValue = $currentDeduction->pivot->amount;
            if ($value == 0 && $currentValue > 0) {
                return true; // No deduction at excel file
            }
        } else {
            // with deduction at excel file
            return false;
        }
        
    }

    public function saveRecords(){
        foreach ($this->listTobeSaved as $entry) {
            $user = User::find($entry['id']);
            if ($user) {
                foreach ($entry as $key => $value) {

                    $user->gsis_crn = $entry['crn'] ?? null;
                    $user->birthdate = $entry['gsis_birthdate'] ?? null;
                    $user->effectivity_date = $entry['effectivity_date'] ?? null;
                    
                    if ($key != 'id') {
                        $deductionId = null;
                        if ($key == 'PS') {
                            $deductionId = 9; // GSIS PREMIUM
                        } elseif ($key == 'CONSOLOAN') {
                            $deductionId = 10; // GSIS CONSOLOAN
                        } elseif ($key == 'MPL') {
                            $deductionId = 11; // GSIS MPL
                        } elseif ($key == 'MPL_LITE') {
                            $deductionId = 14; // GSIS MPL_LITE
                        } elseif ($key == 'PLREG') {
                            $deductionId = 13; // GSIS PLREG
                        } elseif ($key == 'CPL') {
                            $deductionId = 12; // GSIS CPL
                        } elseif ($key == 'EMRGYLN') {
                            $deductionId = 15; // GSIS EMERGENCY LOAN
                        } elseif ($key == 'GFAL') {
                            $deductionId = 16; // GSIS GFAL
                        } elseif ($key == 'SALARY_LOAN') {
                            $deductionId = 17; // GSIS SALARY LOAN
                        }

                        if ($deductionId) {
                            $user->employeeDeductions()->syncWithoutDetaching([
                                $deductionId => ['amount' => $value, 'frequency' => 1]
                            ]);
                        }
                    }
                }
                $user->save();
            }
        }

        session()->flash('message', 'Records updated successfully!');
        return redirect()->route('edit-uploaded-deductions-landing-page', ['file' => $this->file, 'selectedDeductionType' => $this->selectedDeductionType]);

    }



    public function render()
    {
        return view('livewire.edit-uploaded-deduction-component');
    }
}
