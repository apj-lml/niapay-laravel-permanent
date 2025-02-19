<div>
    <ol>
        @php

            $totalDeductions = array();
            $totalSumByDeductionTypePerMonthHdmf = 0.00;
            // $totalSumByDeductionTypePerCutOff = array();

                 
        @endphp




        @forelse ($this->payrollIndices as $year => $yearGroup)

            <li> {{ $year }} </li>
            <ul>
                @foreach ($yearGroup as $month => $payrollGroup)

                @php
                $totalSumByDeductionTypePerCutOffforMonthly = [];
                $totalDeductions = [];

                foreach ($payrollGroup as $payrollFreq => $payrollGroups){
                    foreach ($payrollGroups as $payroll) {
                        foreach ($payroll as $deductionType => $deductionValues) {

                            // Convert the Collection to an array
                            $deductionValuesArray = $deductionValues->toArray();
                            // Check if the deduction type exists in the total sum array
                            if (!isset($totalSumByDeductionType[$deductionType])) {
                                // If it doesn't exist, initialize it with 0
                                $totalSumByDeductionType[$deductionType] = 0;
                            }
                            // Add the sum of deduction values to the total sum for this deduction type
                            $totalSumByDeductionType[$deductionType] += array_sum($deductionValuesArray);
                            // Add the sum to the total sum for this deduction type and month
                            if (!isset($totalSumByDeductionTypePerCutOffforMonthly[$year][$month][$deductionType])) {
                                $totalSumByDeductionTypePerCutOffforMonthly[$year][$month][$deductionType] = 0;
                            }

                        
                            // Check if the key exists in the array
                            if (isset($totalDeductions[$deductionType])) {
                                // If key exists, add the new value to the existing value
                                $totalDeductions[$deductionType] += array_sum($deductionValuesArray);
                            } else {
                                // If key doesn't exist, initialize it with the new value
                                $totalDeductions[$deductionType] = array_sum($deductionValuesArray);
                            }

                

                        }
                    }
                }
                @endphp


                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#collapse{{ $month }}">{{ $month }}</a>
                        {{-- <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#monthDeductionsModal">
                          <i class="bi bi-view-list"></i> Deductions
                        </button> --}}
                    <div class="collapse" id="collapse{{ $month }}">
                        {{-- @dd($payrollGroup) --}}
                        {{-- <div class="card card-body"> --}}
                        <div class="container row">
                            <div class="card card-body col-md-3 col-sm-12">
                                <div class="card-title">Total Deductions per Month</div>
                                <table class="table-secondary table-striped table-hover">
                                    @php
                                        ksort($totalDeductions);
                                    @endphp
                                    @foreach ($totalDeductions as $keyTotalDeduction => $totalDeduction)
                                    <tr>
                                        <td class="align-top">
                                            <b class="text-muted">
                                                {{ $keyTotalDeduction }}
                                            </b>
                                        </td>
                                        <td class="align-top text-end">
                                            <b>
                                            {{ number_format((float)sprintf("%.2f", $totalDeduction), 2) }}
                                            </b>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="card card-body col-md-9 col-sm-12">

                            <ul>
                                @foreach ($payrollGroup as $payrollFreq => $payrollGroup)
                                    <li>
                                        <a href="#" data-bs-toggle="collapse" data-bs-target="#collapse{{ $month }}{{ $payrollFreq }}">{{ $payrollFreq }} <span class="badge bg-secondary">{{ count($payrollGroup) }}</span></a>
                                        <div class="collapse" id="collapse{{ $month }}{{ $payrollFreq }}">
                                            <div class="card card-body col-sm-6">
                                                <div class="card-title">Total Deductions per Payroll Period</div>

                                                @php
                                                $totalSumByDeductionTypePerCutOff = array();
                                                $totalSumByDeductionType = array();
                                                        foreach ($payrollGroup as $payroll) {
                                                            foreach ($payroll as $deductionType => $deductionValues) {

                                                                // Convert the Collection to an array
                                                                $deductionValuesArray = $deductionValues->toArray();
                                                                // Check if the deduction type exists in the total sum array
                                                                if (!isset($totalSumByDeductionType[$deductionType])) {
                                                                    // If it doesn't exist, initialize it with 0
                                                                    $totalSumByDeductionType[$deductionType] = 0;
                                                                }
                                                                // Add the sum of deduction values to the total sum for this deduction type
                                                                $totalSumByDeductionType[$deductionType] += array_sum($deductionValuesArray);
                                                                // Add the sum to the total sum for this deduction type and month
                                                                if (!isset($totalSumByDeductionTypePerCutOff[$year][$month][$deductionType])) {
                                                                    $totalSumByDeductionTypePerCutOff[$year][$month][$deductionType] = 0;
                                                                }

                                                                $totalSumByDeductionTypePerCutOff[$year][$month][$deductionType] += array_sum($deductionValuesArray);
                                                        

                                                            }
                                                        }

                                                        // print_r($totalSumByDeductionTypePerCutOff);
            
                                                @endphp

                                                    <table class="table-secondary table-striped table-hover">
                                                        
                                                        @forelse ($totalSumByDeductionType as $deductionType => $totalSum)
                                                            <tr>
                                                                @if(isset($totalSumByDeductionTypePerCutOff[$year]))

                                                                    <td class="align-top">
                                                                        <b class="text-muted">
                                                                            {{ $deductionType }}
                                                                        </b>
                                                                    </td>
                                                                    {{-- <td>@dd($deductionType)</td> --}}
                                                                    <td class="align-top text-end">
                                                                        <b>
                                                                        {{ number_format((float)sprintf("%.2f", $totalSumByDeductionTypePerCutOff[$year][$month][$deductionType]), 2) }}
                                                                        </b>
                                                                    </td>
                                                                @else
                                                                    @if ($loop->first)   
                                                                    <td>
                                                                        <h5 class="text-muted">No Deductions</h5>
                                                                    </td>
                                                                    @endif
                                                                @endif
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td><h5 class="text-muted">No Deductions</h5></td>
                                                            </tr>
                                                        @endforelse
                                                    </table>

                                            </div>
                                        </div>
                                        <ul>
                                
                                            @foreach ($payrollGroup as $payroll => $deductions)
                                                <li>
                                                    <a href="{{ route('openProcessedPayroll', ['filename' => $payroll]) }}"  data-bs-toggle="collapse" data-bs-target="#collapse{{ preg_replace('/[^A-Za-z0-9]/', '', $payroll) }}">{{ $payroll }}</a> - <a class="link-info" href="{{ route('openProcessedPayroll', ['filename' => $payroll]) }}" target="_blank"><i class="bi bi-eye"></i> View Payroll</a>
                                                    <div class="collapse" id="collapse{{ preg_replace('/[^A-Za-z0-9]/', '', $payroll) }}">
                                                        <div class="card card-body col-sm-6">
                                                            <div class="card-title">Total Deductions per Payroll</div>

                                                            <table class="table-secondary table-striped table-hover">
                                                                @forelse ($deductions->sortKeys() as $deductionDesc => $myDedSum)
                                                                    @php
                                                                        $myDedSum = array_sum($myDedSum->toArray());
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="align-top"><b class="text-muted">{{ $deductionDesc }}</b></td>
                                                                        <td class="align-top text-end"><b>{{ number_format((float)sprintf("%.2f", $myDedSum), 2) }}</b></td>
                                                                    </tr>
                                                                    {{-- <tr height="4px">
                                                                        <td><hr></td>
                                                                    </tr> --}}
                                                                @empty
                                                                    <tr>
                                                                        <td><h5 class="text-muted">No Deductions</h5></td>
                                                                    </tr>
                                                                @endforelse
                                                            </table>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>

                </li>
                @endforeach
            </ul>
            {{-- @dd($totalDeductions) --}}

        @empty
            <h3 class="text-muted m-0 p-0">NO DATA TO BE SHOWN</h3>
        @endforelse
    </ol>
</div>
