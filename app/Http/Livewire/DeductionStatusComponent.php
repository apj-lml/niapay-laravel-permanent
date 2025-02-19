<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Deduction;
use App\Models\DeductionUser;

class DeductionStatusComponent extends Component
{
    public $status, $deduction_id, $isChecked = false, $deduction; // Default state;
    protected $listeners = ['refreshDeductionStatusComponent' => 'setChecks'];

    public function mount()
    {
        $this->setChecks();
    }

    public function setChecks()
    {
        if ($this->deduction_id) {
            $this->deduction = Deduction::find($this->deduction_id);

            if ($this->deduction) {
                $this->isChecked = $this->deduction->status === 'ACTIVE';
            }
        }
    }

    public function updatedIsChecked()
    {
        if ($this->deduction) {
            // Update the specific deduction
            $this->deduction->status = $this->isChecked ? 'ACTIVE' : 'INACTIVE';
            $this->deduction->save();

            $selectUserDeductions = DeductionUser::where('deduction_id', $this->deduction_id)->get();
            foreach($selectUserDeductions as $userDeduction){
                $data = DeductionUser::findOrFail($userDeduction->id);        
                // if($this->status == 'ACTIVE'){
                //     $is_active = 1;
                // }else{
                //     $is_active = 0;
                // }
                if($userDeduction->deduction_id == 27){
                    if(($userDeduction->remarks == 'N/A' || $userDeduction->remarks == null) ){
                        $data->update([
                            'active_status' => $this->isChecked,
                        ]);
                    }
                }else{
                    $data->update([
                        'active_status' => $this->isChecked,
                    ]);
                }
            }

            // dd($selectUserDeductions);

        } else {
            // Update all deductions
            Deduction::query()->update(['status' => $this->isChecked ? 'ACTIVE' : 'INACTIVE']);
        }

        $this->setChecks();
    }

    public function render()
    {
        return view('livewire.deduction-status-component');
    }
}
