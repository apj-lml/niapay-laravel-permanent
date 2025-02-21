<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CnaLandingPageComponent extends Component
{

    public $year, $mc, $cna = "30,000.00";

    protected $rules = [
        'year' => 'required|size:4',
        'cna' => 'required'
    ];

    public function formatValue()
    {
        $this->cna = number_format(floatval(preg_replace('/[^\d.]/', '', $this->cna)), 2);
    }

    public function processCNA()
    {
        $this->validate();

        $cna = intval(preg_replace('/[^\d.]/', '', $this->cna));

            return redirect()->route('cna', [
                'year' => $this->year,
                'mc' => $this->mc,
                'cna' => $cna
             ]);
    }

    public function render()
    {
        return view('livewire.cna-landing-page-component');
    }
}
