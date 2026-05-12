<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MidyearBonus;

class MidyearBonusInputComponent extends Component
{
    public $mybId, $year, $casabloan; //, $casabloan;

    public function mount($payrollUser)
    {
        if($payrollUser){
            if ($payrollUser->mybs->isNotEmpty()) {
                $userMyb= $payrollUser->mybs->where('year', $this->year)->first();
                $this->casabloan = number_format(bcdiv((float) $userMyb->casab_loan, 1, 2), 2);
                $this->mybId = $userMyb->id;
            }
        }

    }

    public function updatedCasabloan()
    {
        $checkMyb = MidyearBonus::find($this->mybId);
        if($checkMyb){

            $myb = $checkMyb->mid_year_bonus;
            // $casabloan = $checkMyb->casab_loan;
            $casabloan = str_replace( ',', '', $this->casabloan);

            $checkMyb->update([
                'mid_year_bonus' => bcdiv((float) $myb, 1, 2),
                // 'total_mid_year_bonus' => bcdiv((float) ((float) $myb), 1, 2),
                'casab_loan' =>  $casabloan,
                'net_amount' => bcdiv((float) ((float) $myb) - $casabloan, 1, 2),
            ]);

            $this->casabloan = number_format(bcdiv((float) $casabloan, 1, 2), 2);

        }


        $this->emit('refreshMybComponent', $this->mybId);
        
    }

    public function render()
    {
        return view('livewire.midyear-bonus-input-component');
    }
}
