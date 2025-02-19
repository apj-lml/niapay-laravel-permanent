<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pei;

class PeiRemarksInputComponent extends Component
{

    public $peiId, $year, $peiRemarks, $payrollUser;

    function mount($payrollUser) {
        $this->payrollUser = $payrollUser;

        if($payrollUser){
            if ($payrollUser->peis->isNotEmpty()) {
                $userPei = $payrollUser->peis->where('year', $this->year)->first();
                $this->peiRemarks = $userPei->remarks;
                $this->peiId = $userPei->id;
            }
        }
    }

    public function updatedPeiRemarks()
    {
        $checkPei = Pei::find($this->peiId);
        if($checkPei){
            $checkPei->update([
                'remarks' => $this->peiRemarks,
            ]);
        }

        $this->emit('refreshPeiComponent', $this->peiId);
    }

    public function render()
    {
        return view('livewire.pei-remarks-input-component');
    }
}
