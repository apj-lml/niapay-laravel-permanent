<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\YearendBonus;

class CasabloanInputComponent extends Component
{

    public $yebId, $yeb, $year, $casabloan;

    protected $listeners = [
        'refreshCasabloan' => 'updatedCasabloan',
    ];

    public function mount($payrollUser, $casabloan)
    {
        if($payrollUser){
            if ($payrollUser->yebs->isNotEmpty()) {
                $userYeb = $payrollUser->yebs->where('year', $this->year)->first();
                $this->yebId = $userYeb->id;
                $this->casabloan = number_format(bcdiv((float) $casabloan, 1, 2), 2);

            }
        }

    }

    public function updatedCasabloan()
    {
        $checkYeb = YearendBonus::find($this->yebId);
        if($checkYeb){
            $casabloanVal = $checkYeb->casab_loan;
            if($checkYeb->casab_loan != $this->casabloan){
                $casabloanVal = str_replace( ',', '', $this->casabloan);
            }
            $checkYeb->update([
                'casab_loan' => bcdiv((float) $casabloanVal, 1, 2),
                'net_amount' => bcdiv((float) ((float) $checkYeb->total_year_end_bonus - $casabloanVal), 1, 2),
            ]);
        }
        // dd($checkYeb);

        $this->casabloan = number_format(bcdiv((float) $casabloanVal, 1, 2), 2);

        $this->emit('refreshYebComponent', $this->yebId);
    }

    public function render()
    {
        return view('livewire.casabloan-input-component');
    }
}
