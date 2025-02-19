<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowProcessedPayrollPdf extends Component
{
    public $payrollGroup;

    public function render()
    {
        
        return view('livewire.show-processed-payroll-pdf');
    }
}
