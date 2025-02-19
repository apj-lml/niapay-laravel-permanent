<?php

namespace App\Http\Livewire\Modals;

use App\Models\Deduction;
use App\Models\DeductionUser;
use Livewire\Component;

class AddDeductionModal extends Component
{

    protected $listeners = ['openEditDeductionModal', 'openAddDeductionModal'];

    public $deductionDescription,
            $accountTitle,
            $deductionType,
            $deductionGroup,
            $isEditMode = false,
            $deductionId,
            $uacsLfps,
            $uacsCob,
            $sortPosition,
            $deductionFor = "both",
            $status = "ACTIVE";

    public function updated(){
        $this->validate([
            'deductionDescription' => 'required',
            'accountTitle' => 'required',
            // 'deductionFor' => 'required',
            'uacsLfps' => 'required',
            'uacsCob' => 'required',
        ]);
    }

    public function openAddDeductionModal()
    {
        $this->reset();
        $this->resetErrorBag();
    }
    
    public function openEditDeductionModal($deductionId){
        
        $this->resetErrorBag();
        // dd($deductionId);
        $deduction = Deduction::find($deductionId);
        $this->deductionDescription = $deduction->description;
        $this->accountTitle = $deduction->account_title;
        // $this->deductionType = $deduction->deduction_type;
        $this->deductionGroup = $deduction->deduction_group;
        $this->deductionFor = $deduction->deduction_for;
        $this->status = $deduction->status;
        $this->deductionId = $deduction->id;
        $this->uacsCob = $deduction->uacs_code_cob;
        $this->uacsLfps = $deduction->uacs_code_lfps;
        $this->sortPosition = $deduction->sort_position;

        $this->isEditMode = true;
    }

    public function addDeductionForm()
    {
        if($this->isEditMode){
            $this->validate([
                'deductionDescription' => 'required|unique:deductions,' . $this->deductionId,
                'deductionDescription' => 'required',
                'accountTitle' => 'required',
                'uacsLfps' => 'required',
                'uacsCob' => 'required',
                // 'deductionFor' => 'required',
            ]);
        }else{
            $this->validate([
                'deductionDescription' => 'required|unique:deductions,description',
                'accountTitle' => 'required',
                'uacsLfps' => 'required',
                'uacsCob' => 'required',
                // 'deductionFor' => 'required',
            ]);
        }

        if(is_null($this->deductionGroup)){
            $this->deductionGroup = $this->deductionDescription;
        }

        if(empty($this->sortPosition)){
            $lastSortPosition = Deduction::where('deduction_group', '=', $this->deductionGroup)->orderBy('sort_position', 'desc')->first();
            if(is_null($lastSortPosition)){
                $this->sortPosition = 0;
            }else{
                $this->sortPosition = $lastSortPosition->sort_position + 1;
            }
        }

        Deduction::updateOrCreate(['id'=>$this->deductionId], [
            'description' => strtoupper($this->deductionDescription),
            'account_title' => strtoupper($this->accountTitle),
            'deduction_type' => strtoupper(str_replace(' ', '_', $this->deductionDescription)),
            'deduction_group' => strtoupper(str_replace(' ', '_', $this->deductionGroup)),
            'deduction_for' => $this->deductionFor,
            'status' => $this->status,
            'uacs_code_lfps' => $this->uacsLfps,
            'uacs_code_cob' => $this->uacsCob,
            'sort_position' => $this->sortPosition,
        ]);

        if(!$this->isEditMode){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully added to the database.']);
        }else{
            // $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved changes.']);
            $selectUserDeductions = DeductionUser::where('deduction_id', $this->deductionId)->get();
            foreach($selectUserDeductions as $userDeduction){
                $data = DeductionUser::findOrFail($userDeduction->id);        
                if($this->status == 'ACTIVE'){
                    $is_active = 1;
                }else{
                    $is_active = 0;
                }
                if($userDeduction->deduction_id == 27){
                    if(($userDeduction->remarks == 'N/A' || $userDeduction->remarks == null) ){
                        $data->update([
                            'active_status' => $is_active,
                        ]);
                    }
                }else{
                    $data->update([
                        'active_status' => $is_active,
                    ]);
                }
            }
            // dd($selectUserDeductions->get());
        }

        $this->reset();
        $this->isEditMode = false;
        $this->emit('refreshListOfDeductions');
        $this->emit('refreshDeductionStatusComponent');

    }

    public function render()
    {
        return view('livewire.modals.add-deduction-modal');
    }
}
