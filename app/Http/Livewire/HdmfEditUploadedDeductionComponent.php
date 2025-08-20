<?php

namespace App\Http\Livewire;

use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;

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

        // === Step 1: Build quick lookup of employees by HDMF ===
        $employeeMapByHdmf = $this->listOfEmployees->keyBy('hdmf'); // [hdmf => User]


    
        // Track all HDMF from DB for later comparison
        $allEmployeeHdmf = $this->listOfEmployees->pluck('hdmf')->filter()->all();

        // === Step 2: Match Excel data to DB employees ===
        $seenHdmfFromExcel = [];


        // dd($data);

        foreach ($data as $row) {
            $pagibigId = trim($row['pagibigid'] ?? ''); // HDMF from Excel


            if (!$pagibigId) {
                continue; // Skip rows with empty BPNO
            }

            $seenHdmfFromExcel[] = $pagibigId;

            if (isset($employeeMapByHdmf[$pagibigId])) {
                $employee = $employeeMapByHdmf[$pagibigId];

                // Combine with ID from DB
                $this->listOfFinalToBeUpdated[] = [
                    'user_id' => $employee->id,
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
        })->sortBy('full_name')->values()->all();

        foreach ($this->listOfFinalToBeUpdated as $row) {
            $entry = ['user_id' => $row['user_id']];
            $typeOfHdmfDeduction = "";
            $deductionId = null;
                       
            if (Str::contains($row['excel_data']['scheme_desc'], '448') || Str::contains($row['excel_data']['scheme_desc'], '469')) {
                $typeOfHdmfDeduction = "HDMF_MPL";
                $deductionId = 3;
        
                if ($this->validateValueWithChanges($row['user_id'], 'HDMF_MPL', $typeOfHdmfDeduction)) {
                    $entry['deduction_type'] = 'HDMF_MPL';
                    $entry['application_no'] = $row['excel_data']['applno'];
                    $entry['monthly_amortization'] = $row['excel_data']['monthly_amo'];
                    $entry['loan_granted'] = $row['excel_data']['loan_grante'];
                    $entry['start_term'] = $row['excel_data']['start_term'];
                    $entry['end_term'] = $row['excel_data']['end_term'];
                    $entry['deduction_id'] = $deductionId;
                }
        
            } elseif (Str::contains($row['excel_data']['scheme_desc'], '449')) {
                $typeOfHdmfDeduction = "HDMF_CAL";
                $deductionId = 4;
        
                if ($this->validateValueWithChanges($row['user_id'], 'HDMF_CAL', $typeOfHdmfDeduction)) {
                    $entry['deduction_type'] = 'HDMF_CAL';
                    $entry['application_no'] = $row['excel_data']['applno'];
                    $entry['monthly_amortization'] = $row['excel_data']['monthly_amo'];
                    $entry['loan_granted'] = $row['excel_data']['loan_grante'];
                    $entry['start_term'] = $row['excel_data']['start_term'];
                    $entry['end_term'] = $row['excel_data']['end_term'];
                    $entry['deduction_id'] = $deductionId;
                }
            }
        
            // Only push if more than ID AND deduction does NOT exist in DB
            if (count($entry) > 1) {
                $pagibigId = $row['excel_data']['pagibigid'] ?? null;

                $user = $this->listOfEmployees->firstWhere('hdmf', $pagibigId);
                // $existing = $user
                //     ? $user->employeeDeductions->firstWhere('deduction_id', $entry['deduction_id'])
                //     : null;
                $existing = $user
                    ? $user->employeeDeductions()->firstWhere('deduction_id', $entry['deduction_id'])
                    : null;
                    
                // Only add to list if:
                // - The deduction doesn't exist, OR
                // - The amount in Excel is different from the existing amount
                if (! $existing || floatval($existing->pivot->amount) != floatval($entry['monthly_amortization'])) {
                    $this->listTobeSaved[] = $entry;
                }
            }
        }

        usort($this->listOfFinalToBeUpdated, function ($a, $b) {
            $nameA = trim($a['excel_data']['lname'] . ' ' . $a['excel_data']['fname'] . ' ' . $a['excel_data']['mid'] . ' ' . ($a['excel_data']['name_ext'] ?? ''));
            $nameB = trim($b['excel_data']['lname'] . ' ' . $b['excel_data']['fname'] . ' ' . $b['excel_data']['mid'] . ' ' . ($b['excel_data']['name_ext'] ?? ''));
            
            return strcmp($nameA, $nameB); // Ascending order
        });
    }

    // This function checks if the value for a specific deduction type has changed compared to the current value in the database.
    // It returns true if the value has changed, false otherwise.
    public function validateValueWithChanges($id, $dedType, $value){
        $user = User::find($id);
        $deductionMap = [
            'HDMF_MPL' => 3,
            'HDMF_CAL' => 4,
        ];
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


    public function updateListToBeSaved($id, $amortization, $applNo, $loanGranted, $startTerm, $endTerm)
    {
        // Find the entry in the final list to update
        $found = false;

        foreach ($this->listTobeSaved as $key => $entry) {
            if ($entry['user_id'] == $id) {
                if ($amortization > 0) {
                    $this->listTobeSaved[$key]['monthly_amortization'] = $amortization;
                } else {
                    // Remove the entire entry if value is 0
                    unset($this->listTobeSaved[$key]);
                }
                return; // Exit after update or delete
            }
        }
        unset($entry);
    
        if (! $found && $amortization > 0) {
            $this->listTobeSaved[] = [
                'user_id' => $id,
                'application_no' => $applNo,
                'monthly_amortization' => $amortization,
                'loan_granted' => $loanGranted,
                'start_term' => $startTerm,
                'end_term' => $endTerm,
            ];
        }

        
    }

    public function saveRecords(){

        foreach ($this->listTobeSaved as $entry) {
            $user = User::find($entry['user_id']);
            if ($user) {
                foreach ($entry as $key => $value) {
                    if ($key != 'user_id') {
                        $deductionId = null;
                        if ($key == 'deduction_type') {
                            if ($value == 'HDMF_MPL') {
                                $deductionId = 3; // HDMF MPL
                            } elseif ($value == 'HDMF_CAL') {
                                $deductionId = 4; // HDMF_CAL
                            } 
                        }

                        if ($deductionId) {
                            // dd($entry);
                            $user->employeeDeductions()->syncWithoutDetaching([ // this will not remove existing deductions. It will only add or update the specified deduction.
                                $deductionId => [
                                'amount' => $entry['monthly_amortization'],
                                'application_no' => $entry['application_no'],
                                'loan_granted' => $entry['loan_granted'],
                                'start_term' => $entry['start_term'],
                                'end_term' => $entry['end_term'],
                                'frequency' => 1
                                ]
                            ]);
                        }
                    }
                }
            }
        }

        session()->flash('message', 'Records updated successfully!');
        return redirect()->route('edit-uploaded-deductions-landing-page', ['file' => $this->file, 'selectedDeductionType' => $this->selectedDeductionType]);

    }

    public function ddMe() {
        dd($this->listTobeSaved);
        
    }

    public function render()
    {

        return view('livewire.hdmf-edit-uploaded-deduction-component');
    }
}
