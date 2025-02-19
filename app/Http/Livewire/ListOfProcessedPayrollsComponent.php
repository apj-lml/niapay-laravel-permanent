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
                })->map(function ($monthGroup) {
                    // Add a constant grouping for 1-15 and 16-end of the month
                    return $monthGroup->groupBy(function ($monthGroupUsers) {


                        $startDate = (int)date('d', strtotime($monthGroupUsers->period_covered_from));
                        $endDate = (int)date('d', strtotime($monthGroupUsers->period_covered_to)); // Using end date here
                        $totalDays = (int)date('t', strtotime($monthGroupUsers->period_covered_from)); // Total days in the month

                        if ($startDate <= 15 && $endDate <= 15) {
                            return '1-15';
                        } elseif ($startDate > 15 && $endDate <= $totalDays) {
                            return '16-' . $endDate; // Use $endDate instead of $totalDays to get correct end date
                        } else {
                            // Handle cases where the end date exceeds the total days in the month
                            return '1-' . $endDate;
                        }
                        
                    })->map(function ($periodGroup) use ($monthGroup) {
                        return $periodGroup->groupBy(function ($periodGroupUser) {
                            $officeSectionFilter = explode(" / ", $periodGroupUser->office_section);
                            $officeFilter =  $periodGroupUser->office;
                            $periodGroupUsers = NewPayrollIndex::where('period_covered_from', $periodGroupUser->period_covered_from)
                            ->where('period_covered_to', $periodGroupUser->period_covered_to)
                            ->where('funding_charges', $periodGroupUser->funding_charges)
                            // ->where('office_section', 'LIKE', $officeSectionFilter[0] . '%')
                            ->where('office', $officeFilter)
                            ->get()
                            ->sortBy('name');

                            $filename = "This is a File";
    
                            $others = 'other';
                            if((count($periodGroupUsers) - 1) > 2){
                                $others = 'others';
                            }
    
                            if(count($periodGroupUsers) == 1){
    
                            $filename = $periodGroupUser->period_covered_from->format('Y-m-d') . ' to ' . $periodGroupUser->period_covered_to->format('Y-m-d') . '_' . $periodGroupUsers->first()->name;
    
                            }else if(count($periodGroupUsers) > 1){
    
                            $nameParts = explode(', ', $periodGroupUsers->first()->name);
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
    
                            $filename = $periodGroupUsers->first()->period_covered_from->format('Y-m-d') . ' to ' . $periodGroupUsers->first()->period_covered_to->format('Y-m-d') . '_' . $modifiedName. ' & ' . (count($periodGroupUsers) - 1) . ' '. $others;
                            }
    
                            return $trimmedFilename = preg_replace('/\s+/', ' ', $filename);


                        })->map(function ($group) {

                            return $group->flatMap(function ($user){
                                // Grouping by deduction category (npiad_group) and summing up the amounts

                                return $user->newPayrollIndexAllDed->groupBy('npiad_description')->map(function ($deductions, $deductionGroup) use ($user){
                                    $officeSectionFilter = explode(" / ", $user->office_section);

                                    $usersInThisPayroll = NewPayrollIndex::with(['newPayrollIndexAllDed' => function ($query) use ($deductionGroup) {
                                        // Filter related deductions based on npiad_group
                                        $query->where('npiad_description', $deductionGroup);
                                    }])
                                    ->where('period_covered_from', $user->period_covered_from)
                                    ->where('period_covered_to', $user->period_covered_to)
                                    ->where('funding_charges', $user->funding_charges)
                                    ->where('office_section', 'LIKE', $officeSectionFilter[0] . '%')
                                    // ->where('office_section', $user->office_section)
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
            });
    }

    public function render()
    {
        // dd($this->payrollIndices);
        return view('livewire.list-of-processed-payrolls-component');
    }
}