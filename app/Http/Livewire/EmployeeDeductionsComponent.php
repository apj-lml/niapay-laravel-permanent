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
            $loan_granted = null,
            $application_no = null,
            $start_term = null,
            $end_term = null,
            $listOfDeductions,
            $editMode = false,
            $deductionUserId,
            $userId;

    public function openEmployeeDeductionsTab($userId)
    {
        $this->employee = User::find($userId);

        $this->listOfDeductions = Deduction::
            // where('status', 'ACTIVE')
            orderBy('deduction_group')
            ->orderBy('description')
            ->get();

        $this->deduction = $this->listOfDeductions[0]['id'];

        $this->userId = $userId;
    }

    public function addDeduction()
    {
        // dd($this->loan_granted);
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
                'application_no' => $this->application_no,
                'loan_granted' => $this->loan_granted ?: null,
                'start_term' => $this->start_term ?: null,
                'end_term' => $this->end_term ?: null,
                'frequency' => $this->frequency,
                'active_status' => $this->active_status,
                'remarks' => $this->remarks,
            ]);

            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Deduction successfully added!']);

            $this->openEmployeeDeductionsTab($this->employee->id);

            $this->reset('amount', 'remarks', 'loan_granted', 'end_term');

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
            $this->application_no = $deductionEmployee->pivot->application_no;
            $this->loan_granted = $deductionEmployee->pivot->loan_granted;
            $this->start_term = $deductionEmployee->pivot->start_term;
            $this->end_term = $deductionEmployee->pivot->end_term;
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
        $selectDeductionUser->loan_granted = $this->loan_granted;
        $selectDeductionUser->end_term = $this->end_term;
        $selectDeductionUser->start_term = $this->start_term;
        $selectDeductionUser->application_no = $this->application_no;

        $selectDeductionUser->save();

        $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully updated deduction!']);
        $this->editMode = false;
        $this->amount = '';
        $this->deduction = 28;
        $this->active_status = 1;
        $this->remarks = 'N/A';
        $this->loan_granted = "";
        $this->end_term = "";
        $this->application_no = "";

        
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
        $this->loan_granted = "";
        $this->end_term = "";
        $this->start_term = "";
        $this->application_no = "";
        

    }

    public function deleteDeductionConfirmation($pivotId, $deductionId, $userId){

        $this->dispatchBrowserEvent('deleteDeductionConfirmation', ['pivotId' => $pivotId, 'deductionId' => $deductionId, 'userId' => $userId]);
        // $this->reset();
        
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
