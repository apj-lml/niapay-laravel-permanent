<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UniformAllowance;

class UniformAllowanceRemarksComponent extends Component
{

    public $uniformAllowanceId, $year, $uniformAllowanceRemarks, $payrollUser;

    function mount($payrollUser) {
        $this->payrollUser = $payrollUser;

        if($payrollUser){
            if ($payrollUser->uniformAllowances->isNotEmpty()) {
                $userUniformAllowance = $payrollUser->uniformAllowances->where('year', $this->year)->first();
                $this->uniformAllowanceRemarks = $userUniformAllowance->remarks;
                $this->uniformAllowanceId = $userUniformAllowance->id;
            }
        }
    }

    public function updatedUniformAllowanceRemarks()
    {
        $checkUniformAllowance = UniformAllowance::find($this->uniformAllowanceId);
        if($checkUniformAllowance){
            $checkUniformAllowance->update([
                'remarks' => $this->uniformAllowanceRemarks,
            ]);
        }

        $this->emit('refreshUniformAllowanceComponent', $this->uniformAllowanceId);
    }

    public function render()
    {
        return view('livewire.uniform-allowance-remarks-component');
    }
}
