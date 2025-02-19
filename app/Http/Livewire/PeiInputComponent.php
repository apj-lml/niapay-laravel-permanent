<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pei;

class PeiInputComponent extends Component
{
    public $peiId, $year, $peiInput, $payrollUser;

    function mount($payrollUser) {
        $this->payrollUser = $payrollUser;

        if($payrollUser){
            if ($payrollUser->peis->isNotEmpty()) {
                $userPei = $payrollUser->peis->where('year', $this->year)->first();
                $this->peiInput = number_format(bcdiv($userPei->pei, 1, 2), 2);
                $this->peiId = $userPei->id;
            }
        }
    }

    public function updatedPeiInput()
    {
        $checkPei = Pei::find($this->peiId);
        if($checkPei){
            $checkPei->update([
                'pei' => $this->peiInput,
            ]);
        }

        $this->peiInput = number_format(bcdiv($this->peiInput, 1, 2), 2);

        $this->emit('refreshPeiComponent', $this->peiId);
    }

    public function render()
    {
        return view('livewire.pei-input-component');
    }
}
