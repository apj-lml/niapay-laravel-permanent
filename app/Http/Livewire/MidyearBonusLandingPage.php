<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MidyearBonusLandingPage extends Component
{
    public $year, $mc;

    protected $rules = [
        'year' => 'required|size:4',
    ];

    // public function formatValue()
    // {
    //     $this->cg = number_format(floatval(preg_replace('/[^\d.]/', '', $this->cg)), 2);
    // }

    public function processCashGift()
    {
        $this->validate();

        // if($payrollEmploymentStatus == 'Casual'){
            return redirect()->route('mid-year-bonus', [
                'year' => $this->year,
                'mc' => $this->mc,
                // 'cg' => $this->cg
             ]);
    }

    public function render()
    {
        return view('livewire.midyear-bonus-landing-page');
    }
}
