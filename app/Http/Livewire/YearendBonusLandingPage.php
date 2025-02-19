<?php

namespace App\Http\Livewire;

use Livewire\Component;

class YearendBonusLandingPage extends Component
{

    public $year, $mc, $cg = "5,000.00";

    protected $rules = [
        'year' => 'required|size:4',
    ];

    public function formatValue()
    {
        $this->cg = number_format(floatval(preg_replace('/[^\d.]/', '', $this->cg)), 2);
    }

    public function processCashGift()
    {
        $this->validate();

        // if($payrollEmploymentStatus == 'Casual'){
            return redirect()->route('year-end-bonus', [
                'year' => $this->year,
                'mc' => $this->mc,
                'cg' => $this->cg
             ]);
    }

    public function render()
    {
        return view('livewire.yearend-bonus-landing-page');
    }
}
