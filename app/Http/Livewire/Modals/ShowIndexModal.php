<?php

namespace App\Http\Livewire\Modals;

use App\Models\indexDeduction;
use App\Models\NewPayrollIndex;
use App\Models\PayrollIndex;
use App\Models\User;
use Livewire\Component;
use DB;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ShowIndexModal extends Component
{
    public $payrollIndex,
            $payrollIndexUserPerYear,
            $payrollIndexPerUser = "",
            $userId,
            $joDeductions,
            $additionalDeductions,
            $uniqueAdditionalDeductionGroups;

    protected $listeners = ['openIndexModal'];

    public function openIndexModal($userId)
    {
        $this->userId = $userId;
    }

    public function render()
    {

    // Retrieve index deductions for the specified user ID

    $payrollsByYear = [];
    $payrollsByYearAndMonth = [];

    $this->userId = 24;

    if(isset($this->userId)){

        $user = User::findOrFail($this->userId);
        $payrollsByYearAndMonth = $user->newPayrollIndex->groupBy(function ($item) {
            return Carbon::parse($item->period_covered_from)->format('Y'); // Group by year
        })->map(function ($yearGroup) {
            return $yearGroup->groupBy(function ($item) {
                return Carbon::parse($item->period_covered_from)->format('F'); // Group by month
            })->sortKeys(); // Sort months in descending order
        })->sortKeysDesc(); // Sort years in descending order

    }

    // dd($payrollsByYearAndMonth);

        return view('livewire.modals.show-index-modal', [
            'payrollsByYear' => $payrollsByYearAndMonth,
        ]);


    }
}
