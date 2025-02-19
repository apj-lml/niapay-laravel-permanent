<?php

namespace App\Http\Livewire;

use App\Models\Allowance;
use Livewire\Component;
use App\Models\User;
use App\Models\AllowanceUser;

class EmployeeAllowancesComponent extends Component
{
    public $employee,
            $allowance,
            $frequency = 1,
            $active_status = 1,
            $amount,
            $listOfAllowances,
            $editMode = false,
            $allowanceUserId,
            $userId;

    protected $listeners = ['openEmployeeAllowancesTab', 'closeEmployeeAllowancesTab', 'deleteAllowance'];

    public function openEmployeeAllowancesTab($userId)
    {
        $this->employee = User::find($userId);
        $this->listOfAllowances = Allowance::where('status', 'ACTIVE')->get();
        $this->allowance = $this->listOfAllowances[0]['id'];

        $this->userId = $userId;

    }

    public function closeEmployeeAllowancesTab()
    {
        $this->reset();
        $this->dispatchBrowserEvent('closeDeductionsTab');
    }

    public function addAllowance()
    {
        $this->validate([
            'amount' => 'required'
        ]);

        $checkDupes = AllowanceUser::where('allowance_id', '=', $this->allowance)
                        ->where('user_id', '=', $this->employee->id)
                        ->where('frequency', '=', $this->frequency)
                        ->first();

        if($checkDupes){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'error', 'title' => 'Allowance already exists!']);
        }else{
            AllowanceUser::create([
                'user_id' => $this->employee->id,
                'allowance_id' => $this->allowance,
                'amount' => $this->amount,
                'frequency' => $this->frequency,
                'active_status' => $this->active_status
    
            ]);

            $this->openEmployeeAllowancesTab($this->employee->id);

            $this->reset('amount');

            $this->emit('refreshProcessPayrollJobOrderComponent');
        }

    }

    public function editMode($isEditMode, $pivotId)
    {
        $this->editMode = $isEditMode;

        $allowanceEmployees = $this->employee->employeeAllowances()->wherePivot('id', '=', $pivotId)->get();

        foreach($allowanceEmployees as $allowanceEmployee){
            $this->allowance = $allowanceEmployee->pivot->allowance_id;
            $this->amount = $allowanceEmployee->pivot->amount;
            $this->active_status = $allowanceEmployee->pivot->active_status;
            $this->frequency = $allowanceEmployee->pivot->frequency;
        }

        $this->allowanceUserId = $pivotId;
    }

    public function backToAdd($isEditMode)
    {
        $this->editMode = $isEditMode;
        $this->listOfAllowances = Allowance::all();
        $this->allowance = $this->listOfAllowances[0]['id'];
        $this->amount = "";
        $this->active_status = 1;

        $this->frequency = 1;
    }

    public function clickUpdateEmployeeAllowance()
    {
        $this->validate([
            'amount' => 'required'
        ]);

        $selectAllowanceUser = AllowanceUser::findOrFail($this->allowanceUserId);
        
        $selectAllowanceUser->allowance_id = $this->allowance;
        $selectAllowanceUser->amount = $this->amount;
        $selectAllowanceUser->frequency = $this->frequency;
        $selectAllowanceUser->active_status = $this->active_status;

        $selectAllowanceUser->save();

        // $this->reset('allowanceUserId');

        $this->employee = User::find($this->userId);

        // foreach ($this->posts as $post) {
        //     $post->save();
        // }

        $this->emit('refreshProcessPayrollJobOrderComponent');

    }

    public function myEditMode($isEditMode, $pivotId)
    {
        $this->editMode = $isEditMode;

        $allowanceEmployees = $this->employee->employeeAllowances()->wherePivot('id', '=', $pivotId)->get();

        foreach($allowanceEmployees as $allowanceEmployee){
            $this->allowance = $allowanceEmployee->pivot->allowance_id;
            $this->amount = $allowanceEmployee->pivot->amount;
            $this->active_status = $allowanceEmployee->pivot->active_status;
            $this->frequency = $allowanceEmployee->pivot->frequency;
        }

        $this->allowanceUserId = $pivotId;
    }

    public function deleteAllowanceConfirmation($pivotId, $allowanceId, $userId){
        $this->dispatchBrowserEvent('deleteAllowanceConfirmation', ['pivotId' => $pivotId, 'allowanceId' => $allowanceId, 'userId'=>$userId]);
    }

    public function deleteAllowance($pivotId, $allowanceId, $userId){
        // $this->employee->employeeAllowances()->detach($allowanceId);
        // $this->employee->employeeAllowances()->wherePivot('id', '=',$pivotId)->delete();
        AllowanceUser::findOrFail($pivotId)->delete();

        $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully removed deduction!']);

        $this->openEmployeeDeductionsTab($userId);

        $this->emit('refreshProcessPayrollJobOrderComponent');

        $this->employee = User::find($userId);

    }


    public function render()
    {
        return view('livewire.employee-allowances-component');
    }
}
