<?php

namespace App\Http\Livewire\Modals;

use Livewire\Component;
use App\Models\AgencySection;
use App\Models\Fund;
use App\Models\NewPayrollIndex;

class PrintPayrollJoModal extends Component
{

    public $section = 0, $fund = 0, $sectionOrFund = "perFund", $payrollDateTo,
    $payrollDateFrom, $isProcessed;

    protected $queryString = ['payrollDateFrom', 'payrollDateTo'];

    public function print()
    {
        // dd($this->section);
        // dd($this->fund);
        $this->emit('createPDF', $this->section, $this->fund);
        // $this->emit('insertIndexDeductions', $this->section, $this->fund);
    }

    // public function runRemovePayrollData()
    // {
    //     $this->emit('removePayrollData', $this->section, $this->fund);
    // }

    // public function SaveIndex()
    // {
    //     $this->emit('createPDF', $this->section, $this->fund);
    // }

    public function render()
    {
        $checkPayrollIndexDupe = NewPayrollIndex::where('period_covered_from', $this->payrollDateFrom)
                                            ->where('period_covered_to', $this->payrollDateTo)
                                            ->get();
        // dd($checkPayrollIndexDupe->isNotEmpty());
        $this->isProcessed = $checkPayrollIndexDupe->isNotEmpty();
        $listOfFunds = Fund::all()->sortBy('fund_description');
        $listOfSections = AgencySection::all()->groupBy('office');
        // dd($listOfSections);
        return view('livewire.modals.print-payroll-jo-modal', ['listOfSections'=>$listOfSections, 'listOfFunds'=>$listOfFunds]);
    }
}
