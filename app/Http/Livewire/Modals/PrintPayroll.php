<?php

namespace App\Http\Livewire\Modals;

use Livewire\Component;

class PrintPayroll extends Component
{

    public $payrollDateFrom,
            $payrollDateTo,
            $isLessFifteen,
            $payrollFrequency;

    protected $validationAttributes = [
        'payrollDateFrom' => 'Date From',
        'payrollDateTo' => 'Date To',
    ];


    public function updated($field, $value)
    {
        // Emit the updated data to other components
        $this->emit('fieldUpdated', $field, $value);
    }

    public function processPayroll($payrollEmploymentStatus)
    {
        $this->validate([
            'payrollDateFrom' => 'required',
            'payrollDateTo' => 'required',
        ]);

            return redirect()->route('process-payroll-jo', [
                'payrollEmploymentStatus'=> $payrollEmploymentStatus,
                'payrollDateFrom' => $this->payrollDateFrom,
                'payrollDateTo' => $this->payrollDateTo,
                'isLessFifteen' => $this->isLessFifteen
             ]);
        
    }

    public function render()
    {
        return view('livewire.modals.print-payroll');
    }

}
