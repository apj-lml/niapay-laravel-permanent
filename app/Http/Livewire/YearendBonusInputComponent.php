<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\YearendBonus;

class YearendBonusInputComponent extends Component
{
    public $yebId, $yeb, $year, $cg; //, $casabloan;

    public function mount($payrollUser)
    {
        if($payrollUser){
            if ($payrollUser->yebs->isNotEmpty()) {
                $userYeb = $payrollUser->yebs->where('year', $this->year)->first();
                $this->yeb = number_format(bcdiv((float) $userYeb->year_end_bonus, 1, 2), 2);
                //$this->casabloan = number_format(bcdiv((float) $userYeb->casab_loan, 1, 2), 2);
                $this->yebId = $userYeb->id;
                // $this->emit('yearendBonusUpdated', $this->yeb);
            }
        }

    }

    public function updatedYeb()
    {
        $checkYeb = YearendBonus::find($this->yebId);
        if($checkYeb){
            $cgVal = $checkYeb->cash_gift;
            $casabloan = $checkYeb->casab_loan;
            $yebVal = str_replace( ',', '', $this->yeb);
            // $casabloan = str_replace( ',', '', $this->casabloan);

            $checkYeb->update([
                'year_end_bonus' => bcdiv((float) $yebVal, 1, 2),
                'total_year_end_bonus' => bcdiv((float) ($cgVal + (float) $yebVal), 1, 2),
                'net_amount' => bcdiv((float) ($cgVal + (float) $yebVal) - $casabloan, 1, 2),
            ]);
        }

        $this->yeb = number_format(bcdiv((float) $yebVal, 1, 2), 2);

        $this->emit('refreshYebComponent', $this->yebId);
        // $this->emit('refreshCasabloan', $this->yebId);
        
    }

    public function render()
    {
        return view('livewire.yearend-bonus-input-component');
    }
}
