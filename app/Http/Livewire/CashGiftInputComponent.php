<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\YearendBonus;

class CashGiftInputComponent extends Component
{
    public $yebId, $yeb, $year, $cg;

    public function mount($payrollUser, $cg)
    {
        if($payrollUser){
            if ($payrollUser->yebs->isNotEmpty()) {
                $userYeb = $payrollUser->yebs->where('year', $this->year)->first();
                // $this->cg = number_format(bcdiv((float) $userYeb->cash_gift, 1, 2), 2);
                $this->yebId = $userYeb->id;

                $this->cg = number_format(bcdiv((float) $cg, 1, 2), 2);

                // $this->emit('yearendBonusUpdated', $this->yeb);
            }
        }

    }

    public function updatedCg()
    {
        $checkYeb = YearendBonus::find($this->yebId);
        if($checkYeb){
            $cgVal = $checkYeb->cash_gift;
            $yebVal = $checkYeb->year_end_bonus;
            $casabloan = $checkYeb->casab_loan;
            
            if($checkYeb->cash_gift != $this->cg){
                $cgVal = str_replace( ',', '', $this->cg);
            }
            $checkYeb->update([
                'cash_gift' => bcdiv((float) $cgVal, 1, 2),
                'total_year_end_bonus' => bcdiv((float) ($cgVal + (float) $yebVal), 1, 2),
                'net_amount' => bcdiv((float) ($cgVal + (float) $yebVal) - $casabloan, 1, 2),

            ]);
        }
        // dd($checkYeb);

        $this->cg = number_format(bcdiv((float) $cgVal, 1, 2), 2);

        $this->emit('refreshYebComponent', $this->yebId);
    }

    public function render()
    {

        return view('livewire.cash-gift-input-component');
    }
    
}
