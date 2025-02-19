<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\NewPayrollIndex;
use App\Models\Fund;


class DeductionSummaryComponent extends Component
{
    public $funds, $date_from, $date_to;

    public function getFundsHistorical()
    {
        return NewPayrollIndex::select('funding_charges')
            ->groupBy('funding_charges')
            ->get();
    }

    public function getFunds()
    {
        $funds = Fund::select('fund_description AS funding_charges', 'id')
        ->with(['users', 'users.employeeDeductions'])
        ->has('users') // Ensure that the Fund has users
        ->get();

        // Sum the deductions grouped by `deduction_id`
        $funds = $funds->map(function ($fund) {
            $fund->deductions_summary = $fund->users
            ->where('include_to_payroll', 1)
            ->where('is_active', 1)
            ->flatMap(function ($user) {
                // Access the pivot table data
                return $user->employeeDeductions
                    // ->where('active_status', 1)
                    ->map(function ($deduction) {
                    return [
                        'description' => $deduction->description,
                        'amount' => (float) $deduction->pivot->amount,
                    ];
                });
            })->groupBy('description')->map(function ($deductions) {
                return $deductions->sum('amount');
            });

            return $fund;
        });

        return $funds;
    }

    public function render()
    {
        $this->funds = $this->getFunds();
        return view('livewire.deduction-summary-component');
    }
}
