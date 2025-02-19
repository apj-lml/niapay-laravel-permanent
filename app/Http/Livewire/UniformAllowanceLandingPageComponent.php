<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UniformAllowanceLandingPageComponent extends Component
{
    public $year, $mc, $uniformAllowance = "7,000.00";

    protected $rules = [
        'year' => 'required|size:4',
        'uniformAllowance' => 'required'
    ];

    public function formatValue()
    {
        $this->uniformAllowance = number_format(floatval(preg_replace('/[^\d.]/', '', $this->uniformAllowance)), 2);
    }

    public function processUniformAllowance()
    {
        $this->validate();

        $uniformAllowance = intval(preg_replace('/[^\d.]/', '', $this->uniformAllowance));

            return redirect()->route('uniform-allowance', [
                'year' => $this->year,
                'mc' => $this->mc,
                'uniformAllowance' => $uniformAllowance
             ]);
    }

    public function render()
    {
        return view('livewire.uniform-allowance-landing-page-component');
    }
}
