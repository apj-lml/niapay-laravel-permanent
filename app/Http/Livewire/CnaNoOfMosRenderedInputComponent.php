<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cna;

class CnaNoOfMosRenderedInputComponent extends Component
{
    public $cnaId, $year, $noMosRendered = 12;

    public function mount($payrollUser)
    {
        if($payrollUser){
            if ($payrollUser->cnas->isNotEmpty()) {
                $userCna = $payrollUser->cnas->where('year', $this->year)->first();
                $this->noMosRendered = bcdiv((float) $userCna->no_mos, 1, 3);
                $this->cnaId = $userCna->id;
            }
        }
    }


    public function updatedNoMosRendered()
    {
        $checkCna = Cna::find($this->cnaId);
        $noMos = bcdiv((float) $this->noMosRendered, 1, 3);
        if($checkCna){
            $checkCna->update([
                'amount_due' => bcdiv(((float) $checkCna->cna / 12) * $this->noMosRendered, 1, 2),
                'no_mos' => $noMos,
            ]);
        }

        $this->noMosRendered = $noMos;

        $this->emit('refreshCnaComponent', $this->cnaId);
    }

    public function render()
    {
        return view('livewire.cna-no-of-mos-rendered-input-component');
    }
}
