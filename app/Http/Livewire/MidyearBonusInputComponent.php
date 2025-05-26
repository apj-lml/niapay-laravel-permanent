<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MidyearBonus;

class MidyearBonusInputComponent extends Component
{
    public $mybId, $myb, $year, $cg; //, $casabloan;

    public function mount($payrollUser)
    {
        if($payrollUser){
            if ($payrollUser->mybs->isNotEmpty()) {
                $userYeb = $payrollUser->mybs->where('year', $this->year)->first();
                $this->myb = number_format(bcdiv((float) $userYeb->mid_year_bonus, 1, 2), 2);
                $this->mybId = $userYeb->id;
            }
        }

    }

    public function updatedMyb()
    {
        $checkYeb = MidyearBonus::find($this->mybId);
        if($checkYeb){
            $cgVal = $checkYeb->cash_gift;
            $casabloan = $checkYeb->casab_loan;
            $mybVal = str_replace( ',', '', $this->myb);

            $checkYeb->update([
                'mid_year_bonus' => bcdiv((float) $mybVal, 1, 2),
                'total_mid_year_bonus' => bcdiv((float) ($cgVal + (float) $mybVal), 1, 2),
                'net_amount' => bcdiv((float) ($cgVal + (float) $mybVal) - $casabloan, 1, 2),
            ]);
        }

        $this->myb = number_format(bcdiv((float) $mybVal, 1, 2), 2);

        $this->emit('refreshMybComponent', $this->mybId);
        
    }

    public function render()
    {
        return view('livewire.midyear-bonus-input-component');
    }
}
