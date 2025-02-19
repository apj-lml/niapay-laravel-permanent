<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cna;

class CnaRemarksInputComponent extends Component
{
    public $cnaId, $year, $cnaRemarks, $payrollUser;

    function mount($payrollUser) {
        $this->payrollUser = $payrollUser;

        if($payrollUser){
            if ($payrollUser->cnas->isNotEmpty()) {
                $userCna = $payrollUser->cnas->where('year', $this->year)->first();
                $this->cnaRemarks = $userCna->remarks;
                $this->cnaId = $userCna->id;
            }
        }
    }

    public function updatedCnaRemarks()
    {
        $checkCna = Cna::find($this->cnaId);
        if($checkCna){
            $checkCna->update([
                'remarks' => $this->cnaRemarks,
            ]);
        }

        $this->emit('refreshCnaComponent', $this->cnaId);
    }

    public function render()
    {
        return view('livewire.cna-remarks-input-component');
    }
}
