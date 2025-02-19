<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\NewPayrollIndex;


class ListOfProcessedPayrollsComponent extends Component
{

    public $payrollIndices;

    public function mount()
    {

        $this->payrollIndices = NewPayrollIndex::with('user', 'newPayrollIndexAllDed')
        ->get()
        ->groupBy(function ($payrollIndex) {
            return substr($payrollIndex->period_covered_from, 0, 4); // Extract year from period_covered_from
        })
        ->map(function ($yearGroup) {
            return $yearGroup->groupBy(function ($payrollIndex) {
                return date('F', strtotime($payrollIndex->period_covered_from));
            })->map(function ($monthGroup){
                    return $monthGroup->groupBy(function ($monthGroupUsers) {
                        $usersInThisPayroll = NewPayrollIndex::where('period_covered_from', $monthGroupUsers->period_covered_from)
                        ->where('period_covered_to', $monthGroupUsers->period_covered_to)
                        ->where('funding_charges', $monthGroupUsers->funding_charges)
                        ->where('office_section', $monthGroupUsers->office_section)
                        ->get()
                        ->sortBy('name');

                        $others = 'other';
                        if((count($usersInThisPayroll) - 1) > 2){
                        $others = 'others';
                        }

                        if(count($usersInThisPayroll) == 1){

                        $filename = $monthGroupUsers->period_covered_from . ' to ' . $monthGroupUsers->period_covered_to . '_' . $usersInThisPayroll->first()->name;

                        }else if(count($usersInThisPayroll) > 1){

                        $nameParts = explode(', ', $usersInThisPayroll->first()->name);
                        // Extract the first name
                        $firstName = $nameParts[1];

                        // Extract the last name
                        $lastName = $nameParts[0];

                        // Check if the last word contains a suffix
                        $words = explode(' ', $firstName);

                        $suffix = '';
                        if (count($words) > 1) {
                            $lastWord = end($words);
                            foreach($words as $word){
                                if (in_array(strtoupper($word), ['JR.', 'SR.', 'I', 'II', 'III', 'IV'])) {
                                $suffix = $word;
                                }
                            }
                        }
                        // Combine the modified first name initial, a period, and the last name with suffix
                        $modifiedFirstName = '';
                        if (count($words) > 1) {
                        // If the first name contains more than one word, consider only the first word
                        $modifiedFirstName = substr($words[0], 0, 1) . '.';
                        } else {
                        // If the first name contains only one word, consider the whole word
                        $modifiedFirstName = substr($words[0], 0, 1) . '.';
                        }

                        // Combine the modified first name initial, a period, and the last name with suffix
                        $modifiedName = $modifiedFirstName . ' ' . $lastName . ($suffix ? ' ' . $suffix : '');

                        $filename = $monthGroupUsers->period_covered_from . ' to ' . $monthGroupUsers->period_covered_to . '_' . $modifiedName. ' & ' . (count($usersInThisPayroll) - 1) . ' '. $others;
                        }

                        return $trimmedFilename = preg_replace('/\s+/', ' ', $filename);
                })

                ->map(function ($monthGroupUsers){
                    return $monthGroupUsers->flatMap(function ($user){
                        // Grouping by deduction category (npiad_group) and summing up the amounts
                        return $user->newPayrollIndexAllDed->groupBy('npiad_group')->map(function ($deductions, $deductionGroup) use ($user){
                              $usersInThisPayroll = NewPayrollIndex::with(['newPayrollIndexAllDed' => function ($query) use ($deductionGroup) {
                                // Filter related deductions based on npiad_group
                                $query->where('npiad_group', $deductionGroup);
                            }])
                            ->where('period_covered_from', $user->period_covered_from)
                            ->where('period_covered_to', $user->period_covered_to)
                            ->where('funding_charges', $user->funding_charges)
                            ->where('office_section', $user->office_section)
                            ->get()
                            ->sortBy('name');
                            
                            $totalAmount = $usersInThisPayroll->map(function ($userInPayroll) use ($deductionGroup) {
                                return $userInPayroll->newPayrollIndexAllDed->pluck('npiad_amount')->sum();
                                // return $deductionGroup;
                            });

                            // dd($totalAmount);

                            return $totalAmount;

                        });
                    });
                });


            });
        });
    }

    public function render()
    {
        dd($this->payrollIndices);
        return view('livewire.list-of-processed-payrolls-component');
    }
}
