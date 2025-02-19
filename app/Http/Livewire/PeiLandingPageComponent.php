<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PeiLandingPageComponent extends Component
{

    public $year, $mc, $pei = "5,000.00";

    protected $rules = [
        'year' => 'required|size:4',
        'pei' => 'required'
    ];

    public function formatValue()
    {
        $this->pei = number_format(floatval(preg_replace('/[^\d.]/', '', $this->pei)), 2);
    }

    public function processPei()
    {
        $this->validate();

        $pei = intval(preg_replace('/[^\d.]/', '', $this->pei));

            return redirect()->route('pei', [
                'year' => $this->year,
                'mc' => $this->mc,
                'pei' => $pei
             ]);
    }

    public function render()
    {
        return view('livewire.pei-landing-page-component');
    }
}
