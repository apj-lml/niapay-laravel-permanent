<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;


class ShowEmployeesWithoutPayroll extends Component
{

    public $payrollDateFrom, $payrollDateTo, $listOfEmployeesWoPayroll = [];

    public function submitSearchForm()
    {
        $payrollDateTo = $this->payrollDateTo;
        $payrollDateFrom = $this->payrollDateFrom;
        $this->listOfEmployeesWoPayroll = User::whereDoesntHave('newPayrollIndex', function ($query) use ($payrollDateFrom, $payrollDateTo) {
            $query->where('period_covered_from', '<=', $payrollDateTo)
                  ->where('period_covered_to', '>=', $payrollDateFrom);
            })->get()->sortBy('full_name');
    }

    public function render()
    {
        return view('livewire.show-employees-without-payroll');
    }
}
