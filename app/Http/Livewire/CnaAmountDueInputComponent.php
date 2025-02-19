<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CnaAmountDueInputComponent extends Component
{
    public $cnaId, $year, $noMosRendered = 12.000, $amountDue, $payrollUser;

    protected $listeners = ['refreshCnaAmountDueInputComponent' => '$refresh'];

    function mount($payrollUser) {
        $this->payrollUser = $payrollUser;
    }

    public function render()
    {
        if($this->payrollUser){
            if ($this->payrollUser->cnas->isNotEmpty()) {
                $userCna = $this->payrollUser->cnas->where('year', $this->year)->first();
                $this->noMosRendered = number_format(bcdiv((float) $userCna->no_mos, 1, 2), 3);
                $this->cnaId = $userCna->id;
                $this->amountDue = number_format(bcdiv((float) $userCna->amount_due, 1, 2), 2);
            }
        }
        return view('livewire.cna-amount-due-input-component');
    }
}
