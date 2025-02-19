<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\DeductionUser;
use App\Models\Deduction;

class EmployeeDeductionsComponent extends Component
{
    protected $listeners = ['openEmployeeDeductionsTab', 'closeEmployeeDeductionsTab', 'deleteDeduction'];
    public $employee,
            $deduction,
            $amount,
            $frequency = 1,
            $active_status = 1,
            $remarks = 'N/A',
            $listOfDeductions,
            $editMode = false,
            $deductionUserId,
            $userId;

    public function openEmployeeDeductionsTab($userId)
    {
        $this->employee = User::find($userId);
        if ($this->employee->employment_status != "CASUAL") {
            $this->listOfDeductions = Deduction::
                // where('status', 'ACTIVE')
                orderBy('deduction_group')
                ->orderBy('description')
                ->get();
        } else {
            $this->listOfDeductions = Deduction::
                // where('status', 'ACTIVE')
                // where('deduction_group', '<>', 'GSIS')
                orderBy('deduction_group')
                ->orderBy('description')
                ->get();
        }

        // if(!$this->listOfDeductions){
            $this->deduction = $this->listOfDeductions[0]['id'];
        // }else{
        //     $this->deduction = null;
        // }


        $this->userId = $userId;
    }

    public function addDeduction()
    {
        $this->validate([
            'amount' => 'required'
        ]);

        $checkDupes = DeductionUser::where('deduction_id', '=', $this->deduction)
                        ->where('user_id', '=', $this->employee->id)
                        ->where('frequency', '=', $this->frequency)
                        ->first();

        if($checkDupes){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'error', 'title' => 'Deduction already exists!']);
        }else{
            DeductionUser::create([
                'user_id' => $this->employee->id,
                'deduction_id' => $this->deduction,
                'amount' => $this->amount,
                'frequency' => $this->frequency,
                'active_status' => $this->active_status,
                'remarks' => $this->remarks
            ]);

            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Deduction successfully added!']);

            $this->openEmployeeDeductionsTab($this->employee->id);

            $this->reset('amount', 'remarks');

            $this->emit('refreshProcessPayrollJobOrderComponent');

        }

    }

    public function myEditMode($isEditMode, $pivotId)
    {
        $this->editMode = $isEditMode;

        $deductionEmployees = $this->employee->employeeDeductions()->wherePivot('id', '=', $pivotId)->get();

        foreach($deductionEmployees as $deductionEmployee){
            $this->deduction = $deductionEmployee->pivot->deduction_id;
            $this->amount = $deductionEmployee->pivot->amount;
            $this->active_status = $deductionEmployee->pivot->active_status;
            $this->frequency = $deductionEmployee->pivot->frequency;

            $this->remarks = $deductionEmployee->pivot->remarks;
            // dd($deductionEmployee->pivot->remarks);
        }

        $this->deductionUserId = $pivotId;
    }

    public function changeStatus()
    {
        if($this->active_status == 1){
            $this->remarks = "N/A";
        }
    }

    public function clickUpdateEmployeeDeduction()
    {
        $this->validate([
            'amount' => 'required'
        ]);

        $selectDeductionUser = DeductionUser::findOrFail($this->deductionUserId);

        $selectDeductionUser->deduction_id = $this->deduction;
        $selectDeductionUser->amount = $this->amount;
        $selectDeductionUser->frequency = $this->frequency;
        $selectDeductionUser->active_status = $this->active_status;
        $selectDeductionUser->remarks = $this->remarks;

        $selectDeductionUser->save();

        $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully updated deduction!']);
        $this->editMode = false;
        $this->amount = '';
        $this->deduction = 28;
        $this->active_status = 1;
        $this->remarks = 'N/A';

        $this->employee = User::find($this->userId);

        $this->emit('refreshProcessPayrollJobOrderComponent');


    }

    public function backToAdd($isEditMode)
    {
        $this->editMode = $isEditMode;
        $this->listOfDeductions = Deduction::all()->sortBy('description');
        $this->deduction = $this->listOfDeductions[0]['id'];
        $this->amount = "";
        $this->frequency = 1;
        $this->active_status = 1;
        $this->remarks = 'N/A';

    }

    public function deleteDeductionConfirmation($pivotId, $deductionId, $userId){

        $this->dispatchBrowserEvent('deleteDeductionConfirmation', ['pivotId' => $pivotId, 'deductionId' => $deductionId, 'userId' => $userId]);
        $this->reset();
        
    }

    public function deleteDeduction($pivotId, $deductionId, $userId){

        DeductionUser::findOrFail($pivotId)->delete();

        $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully removed deduction!']);

        $this->openEmployeeDeductionsTab($userId);

        $this->emit('refreshProcessPayrollJobOrderComponent');

    }

    public function closeEmployeeDeductionsTab()
    {

        $this->reset();
        $this->dispatchBrowserEvent('closeDeductionsTab');

    }

    public function render()
    {
        return view('livewire.employee-deductions-component');
    }
}
