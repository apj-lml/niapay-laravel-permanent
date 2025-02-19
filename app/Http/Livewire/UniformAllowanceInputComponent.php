<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UniformAllowance;

class UniformAllowanceInputComponent extends Component
{

    public $uniformAllowanceId, $year, $uniformAllowanceInput, $payrollUser;

    function mount($payrollUser) {
        $this->payrollUser = $payrollUser;

        if($payrollUser){
            if ($payrollUser->uniformAllowances->isNotEmpty()) {

                $userUniformAllowance = $payrollUser->uniformAllowances->where('year', $this->year)->first();
                $this->uniformAllowanceInput = $userUniformAllowance->uniform_allowance;
                $this->uniformAllowanceId = $userUniformAllowance->id;
                $this->formatValue();

            }
        }
    }

    public function formatValue()
    {
        $this->uniformAllowanceInput = number_format(floatval(preg_replace('/[^\d.]/', '', $this->uniformAllowanceInput)), 2);
    }

    public function updatedUniformAllowanceInput()
    {
        $checkUniformAllowance = UniformAllowance::find($this->uniformAllowanceId);
        if($checkUniformAllowance){
            $checkUniformAllowance->update([
                'uniform_allowance' => $this->uniformAllowanceInput,
            ]);
        }
        $this->formatValue();

        $this->emit('refreshUniformAllowanceComponent', $this->uniformAllowanceId);
    }
    
    public function render()
    {
        return view('livewire.uniform-allowance-input-component');
    }
}
