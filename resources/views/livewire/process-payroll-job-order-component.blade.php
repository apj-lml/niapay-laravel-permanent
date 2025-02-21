@push('styles')
    <style>
        input[type="text"]:focus {
            z-index: 1080;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .no-days {
            font-size: 12px;
            text-align: center;
            width: 90%;
            border: 1px solid #89878754;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 135;
            /* Ensure background color to differentiate from table rows */
            /* z-index: 9999; Ensure it stays above the table rows */
        }

        .sample {
            background-image: url("{{ URL::asset('/img/bagong_pilipinas.png') }}");
            background-size: 75px 75px;
            background-repeat: no-repeat;
            background-position: left top;
        }

        table {
            border: 1px black;
        }

       table td {
            vertical-align: middle;
            text-align: right;
        }

        table td. {
            vertical-align: middle;
            text-align: right;
        }

        table tfoot {
            position: sticky;
        }

        table tfoot {
            inset-block-end: 0;
            /* "bottom" */
            color: #0a090a;
            background-color: #febdcd;
        }

        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #fadee8;
        }


    </style>
@endpush

<div>

    <font size="2.5" face="Cambria">
        <ul class="nav nav-tabs" id="myTab" role="tablist" wire:ignore>
            @php
                $characters = [' ', '(', ')', '[', ']', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
                $replacement = null;
            @endphp
            @foreach ($payrollFunds as $payrollFund)
                <li class="nav-item" role="presentation">
                    <button wire:key="{{ Str::random(15) }}"
                        class="nav-link @if ($loop->first) active @endif"
                        id="{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}"
                        type="button" role="tab"
                        aria-controls="{{ Str::replace(' ', '-', $payrollFund->fund_description) }}"
                        aria-selected="true">{{ $payrollFund->fund_description }}</button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="myTabContent" wire:ignore.self>
            @foreach ($payrollFunds as $payrollFund)
                @if (isset($payrollFund->users))
                    <div wire:ignore.self class="tab-pane fade @if ($loop->first) show active @endif "
                        id="{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}"
                        role="tabpanel"
                        aria-labelledby="{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}-tab">

                        <h1 class="mt-2">{{ $payrollFund->fund_description }} | {{ $payrollPeriodTxt }}</h1>

                        @php
                            $counter = 0;
                        @endphp

                        @foreach ($payrollFund->sections as $office => $payrollUserSections)
                            @foreach ($payrollUserSections as $payrollUserSection)
                                <div class="border border-light shadow p-3 mb-3">
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-6">
                                                <h6 class="">{{ $office }}</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="enableScroll{{ $payrollUserSection->id }}"
                                                        wire:change.debounce.500ms="lockScroll()"
                                                        {{ $overflow == 'auto' ? '' : 'checked' }}>
                                                    <label class="form-check-label"
                                                        for="enableScroll{{ $payrollUserSection->id }}">
                                                        Lock Scrolling
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">

                                            </div>
                                        </div>

                                        <div class="col-sm-12 row" id="screen_{{ $payrollUserSection->id }}">
                                            <div class="col-sm-2">
                                                <button class="ms-auto mt-auto btn btn-outline-primary btn-sm mt-2"
                                                    onclick="changeSize('screen_{{ $payrollUserSection->id }}', 'max', 'table_container{{ $payrollUserSection->id }}', 'maxBtn{{ $payrollUserSection->id }}'); return false;"
                                                    id="maxBtn{{ $payrollUserSection->id }}">
                                                    Maximize
                                                </button>
                                            </div>

                                            <div class="table-responsive mt-3"
                                                style="max-height: 700px; overflow-y: auto; z-index: 80;"
                                                id="table_container{{ $payrollUserSection->id }}">
                                                @php
                                                    $hasPERA = false;
                                                    $peraCol = 0;

                                                    foreach ($joAllowances as $allowance) {
                                                        if ($allowance->allowance_group == 'PERA') {
                                                            $hasPERA = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasPERA == false) {
                                                        $peraCol = 1;
                                                    }

                                                    $hasMEDICAL = false;
                                                    $medicalCol = 0;

                                                    foreach ($joAllowances as $allowance) {
                                                        if ($allowance->allowance_group == 'MEDICAL') {
                                                            $hasMEDICAL = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasMEDICAL == false) {
                                                        $medicalCol = 1;
                                                    }

                                                    $hasMEAL = false;
                                                    $mealCol = 0;

                                                    foreach ($joAllowances as $allowance) {
                                                        if ($allowance->allowance_group == 'MEAL') {
                                                            $hasMEAL = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasMEAL == false) {
                                                        $mealCol = 1;
                                                    }

                                                    $hasCHILD = false;
                                                    $childCol = 0;

                                                    foreach ($joAllowances as $allowance) {
                                                        if ($allowance->allowance_group == 'CHILD') {
                                                            $hasCHILD = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasCHILD == false) {
                                                        $childCol = 1;
                                                    }
                                                    

                                        
                                                    $hasTAX = false;
                                                    $taxCol = 0;

                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'TAX') {
                                                            $hasTAX = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasTAX == false) {
                                                        $taxCol = 1;
                                                    }

                                                    $hasGSIS = false;
                                                    $gsisCol = 0;

                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'GSIS') {
                                                            $hasGSIS = true;
                                                            // break;
                                                        }
                                                    }
                                                    if (
                                                        $hasGSIS == false &&
                                                        strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' &&
                                                        strtoupper($payrollEmploymentStatus) != 'PERMANENT'
                                                    ) {
                                                        $gsisCol = 1;
                                                    }

                                                    $hasHDMF = false;
                                                    $hdmfCol = 0;

                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'HDMF') {
                                                            $hasHDMF = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasHDMF == false) {
                                                        $hdmfCol = 1;
                                                    }

                                                    $hasPHIC = false;
                                                    $phicCol = 0;

                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'PHIC') {
                                                            $hasPHIC = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasPHIC == false) {
                                                        $phicCol = 1;
                                                    }

                                                    $hasCOOP = false;
                                                    $coopCol = 0;
                                                    // dd($joDeductions);
                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'COOP') {
                                                            $hasCOOP = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasCOOP == false) {
                                                        $coopCol = 1;
                                                    }

                                                    $hasDISALLOWANCE = false;
                                                    $disallowanceCol = 0;

                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'DISALLOWANCE') {
                                                            $hasDISALLOWANCE = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasDISALLOWANCE == false) {
                                                        $disallowanceCol = 1;
                                                    }

                                                    $hasOTHER = false;
                                                    $otherCol = 0;

                                                    foreach ($joDeductions as $deduction) {
                                                        if ($deduction->deduction_group == 'OTHER') {
                                                            $hasOTHER = true;
                                                            // break;
                                                        }
                                                    }
                                                    if ($hasOTHER == false) {
                                                        $otherCol = 1;
                                                    }

                                                @endphp


                                                <table
                                                    class="table table-striped table-hover table-bordered position-relative display"
                                                    id="payrolltable{{ $payrollUserSection->id }}">
                                                    <thead class="sticky-top">
                                                        <tr>
                                                            <th scope="col" rowspan="3" colspan="1"
                                                                class="text-center align-middle ">
                                                                <div class="form-switch w-0 p-0 pt-1">
                                                                    <input class="form-check-input major m-auto"
                                                                        type="checkbox"
                                                                        id="checkAll{{ $payrollUserSection->id }}"
                                                                        wire:change="updateIsActive([], {{ $payrollUserSection->id }})"
                                                                        checked>
                                                                </div>
                                                            </th>
                                                            <th scope="col" rowspan="3" colspan="1" style="width:15%"
                                                                class="text-center align-middle ">Name</th>
                                                            <th scope="col" rowspan="3"
                                                                class="text-center align-middle" style="width:10%">
                                                                Position Title</th>
                                                            <th scope="col" rowspan="3"
                                                                class="text-center align-middle" style="width:5%">No. of
                                                                Days Worked</th>
                                                            @if (strtoupper($payrollEmploymentStatus) == 'PERMANENT')
                                                                <th scope="col" rowspan="3"
                                                                    class="text-center align-middle" style="width:5%">
                                                                    Monthly Rate</th>
                                                            @else
                                                                <th scope="col" rowspan="3"
                                                                    class="text-center align-middle" style="width:5%">
                                                                    Daily Rate</th>
                                                            @endif
                                                            <th scope="col" rowspan="3"
                                                                class="text-center align-middle" style="width:5%">Basic Pay
                                                            </th>
                                                                
                                                            @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                    strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                                                <th scope="col"
                                                                    colspan="{{ $joAllowances->count() }}"
                                                                    class="text-center align-middle ">A L L O W A N C E S
                                                                </th>
                                                                <th scope="col" rowspan="3"
                                                                    class="text-center align-middle">Total Allowances
                                                                </th>
                                                            @endif
                                                            <th scope="col" rowspan="3"
                                                                class="text-center align-middle" style="width:5%">GROSS PAY
                                                            </th>
                                                            @php

                                                                $inactiveDeduction = $joDeductions
                                                                    ->where('deduction_group', '<>', 'HDMF')
                                                                    ->where('deduction_group', '<>', 'PHIC')
                                                                    ->where('deduction_group', '<>', 'COOP')
                                                                    ->where('deduction_group', '<>', 'DISALLOWANCE')
                                                                    ->where('deduction_group', '<>', 'TAX')
                                                                    ->where('status', 'INACTIVE');

                                                                    $joDeductionsCount = $joDeductions->count() == 0 ? 1 : $joDeductions->count();
                                                                
                                                                // $inactiveDeduction = collect();

                                                            @endphp

                                                            <th scope="col"
                                                                {{-- colspan="{{ $joDeductions->count() + $gsisCol + $hdmfCol + $taxCol + $phicCol + $coopCol + $disallowanceCol - $inactiveDeduction->count() }}" --}}
                                                                colspan="{{ $joDeductionsCount + $gsisCol + $hdmfCol + $taxCol + $phicCol + $coopCol + $disallowanceCol }}"
                                                                class="text-center align-middle ">D E D U C T I O N S
                                                            </th>
                                                            {{-- <th scope="col" colspan="{{ $joDeductions->count() + $gsisCol + $hdmfCol + $taxCol + $phicCol + $coopCol + $disallowanceCol }}" class="text-center align-middle ">D E D U C T I O N S</th> --}}
                                                            <th scope="col" rowspan="3"
                                                                class="text-center align-middle ">Total Deductions
                                                            </th>

                                                            <th scope="col"
                                                                {{-- @if($isLessFifteen == false) --}}
                                                                @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                                                            
                                                                    rowspan="2"
                                                                    colspan="3"
                                                                @else
                                                                    rowspan="3"
                                                                    colspan="1"
                                                               
                                                                @endif
                                                                class="text-center align-middle ">NET PAY
                                                            </th>
                                                        </tr>

                                                        <tr>
                                                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                    strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                                                @if ($joAllowances->countBy('allowance_group')->has('PERA'))
                                                                    @if ($joAllowances->countBy('allowance_group')['PERA'] >= 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'PERA')->sortBy('sort_position')->all() as $childDed)
                                                                            <th scope="col" rowspan="2"
                                                                                class="text-center align-middle">
                                                                                {{ $childDed->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                                @if ($joAllowances->countBy('allowance_group')->has('MEDICAL'))
                                                                    @if ($joAllowances->countBy('allowance_group')['MEDICAL'] >= 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'MEDICAL')->sortBy('sort_position')->all() as $childDed)
                                                                            <th scope="col" rowspan="2"
                                                                                class="text-center align-middle">
                                                                                {{ $childDed->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                                @if ($joAllowances->countBy('allowance_group')->has('MEAL'))
                                                                    @if ($joAllowances->countBy('allowance_group')['MEAL'] >= 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'MEAL')->sortBy('sort_position')->all() as $childDed)
                                                                            <th scope="col" rowspan="2"
                                                                                class="text-center align-middle">
                                                                                {{ $childDed->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                                @if ($joAllowances->countBy('allowance_group')->has('CHILD'))
                                                                    @if ($joAllowances->countBy('allowance_group')['CHILD'] >= 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'CHILD')->sortBy('sort_position')->all() as $childDed)
                                                                            <th scope="col" rowspan="2"
                                                                                class="text-center align-middle">
                                                                                {{ $childDed->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif

                                                        @endif

                                                            <th scope="col" {{-- @if ($joDeductions->countBy('deduction_group')['TAX'] <= 1) --}}
                                                                @if (!$hasTAX || $joDeductions->countBy('deduction_group')['TAX'] <= 1) rowspan="2"
                        @else
                          colspan="{{ $joDeductions->countBy('deduction_group')['TAX'] }}" @endif
                                                                class="text-center align-middle">
                                                                WHT
                                                            </th>

                                                            @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                    strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                                                <th scope="col"
                                                                    @if (!$hasGSIS || $joDeductions->countBy('deduction_group')['GSIS'] <= 1) rowspan="2" 
                        @else
                          colspan="{{ $joDeductions->countBy('deduction_group')['GSIS'] }}" @endif
                                                                    class="text-center align-middle">
                                                                    GSIS
                                                                </th>
                                                            @endif


                                                            <th scope="col"
                                                                @if (!$hasHDMF || $joDeductions->countBy('deduction_group')['HDMF'] <= 1) rowspan="2" 
                        @else
                          colspan="{{ $joDeductions->countBy('deduction_group')['HDMF'] }}" @endif
                                                                class="text-center align-middle">
                                                                HDMF
                                                            </th>

                                                            <th scope="col"
                                                                @if (!$hasPHIC || $joDeductions->countBy('deduction_group')['PHIC'] <= 1) rowspan="2" 
                        @else
                        colspan="{{ $joDeductions->countBy('deduction_group')['PHIC'] }}" @endif
                                                                class="text-center align-middle">
                                                                PHIC
                                                            </th>

                                                            <th scope="col" {{-- @if ($joDeductions->countBy('deduction_group')['COOP'] <= 1) --}}
                                                                @if (!$hasCOOP || $joDeductions->countBy('deduction_group')['COOP'] <= 1) rowspan="2"
                      @else
                        colspan="{{ $joDeductions->countBy('deduction_group')['COOP'] }}" @endif
                                                                class="text-center align-middle">
                                                                COOP LOAN
                                                            </th>

                                                            <th scope="col" {{-- @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] <= 1) --}}
                                                                @if (!$hasDISALLOWANCE || $joDeductions->countBy('deduction_group')['DISALLOWANCE'] <= 1) rowspan="2" 
                      @else
                        colspan="{{ $joDeductions->countBy('deduction_group')['DISALLOWANCE'] }}" @endif
                                                                class="text-center align-middle">
                                                                DISALLOWANCE
                                                            </th>

                                                            {{-- OTHER DED --}}
                                                            @if ($joDeductions->countBy('deduction_group')->has('OTHER'))
                                                                @if ($joDeductions->countBy('deduction_group')['OTHER'] >= 1)
                                                                    @foreach ($joDeductions->where('deduction_group', '=', 'OTHER')->sortBy('sort_position')->all() as $otherDed)
                                                                        <th scope="col" rowspan="2"
                                                                            class="text-center align-middle">
                                                                            {{ $otherDed->description }}</th>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </tr>

                                                        <tr>
                                                            @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                    strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')

                                                                @if ($hasPERA)
                                                                    @if ($joAllowances->countBy('allowance_group')['PERA'] > 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'PERA')->sortBy('sort_position')->all() as $peraAllowance)
                                                                            <th scope="col"
                                                                                class="text-center align-middle ">
                                                                                {{ $peraAllowance->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif

                                                                @if ($hasMEDICAL)
                                                                    @if ($joAllowances->countBy('allowance_group')['MEDICAL'] > 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'MEDICAL')->sortBy('sort_position')->all() as $medicalAllowance)
                                                                            <th scope="col"
                                                                                class="text-center align-middle ">
                                                                                {{ $medicalAllowance->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif

                                                                @if ($hasMEAL)
                                                                    @if ($joAllowances->countBy('allowance_group')['MEAL'] > 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'MEAL')->sortBy('sort_position')->all() as $mealAllowance)
                                                                            <th scope="col"
                                                                                class="text-center align-middle ">
                                                                                {{ $mealAllowance->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif

                                                                @if ($hasCHILD)
                                                                    @if ($joAllowances->countBy('allowance_group')['CHILD'] > 1)
                                                                        @foreach ($joAllowances->where('allowance_group', '=', 'CHILD')->sortBy('sort_position')->all() as $childAllowance)
                                                                            <th scope="col"
                                                                                class="text-center align-middle ">
                                                                                {{ $childAllowance->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            @if ($hasTAX)
                                                                @if ($joDeductions->countBy('deduction_group')['TAX'] > 1)
                                                                    @foreach ($joDeductions->where('deduction_group', '=', 'TAX')->sortBy('sort_position')->all() as $taxDeduction)
                                                                        <th scope="col"
                                                                            class="text-center align-middle ">
                                                                            {{ $taxDeduction->description }}</th>
                                                                    @endforeach
                                                                @endif
                                                            @endif

                                                            @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                    strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                                                @if ($hasGSIS)
                                                                    @if ($joDeductions->countBy('deduction_group')['GSIS'] > 1)
                                                                        @foreach ($joDeductions->where('deduction_group', '=', 'GSIS')->sortBy('sort_position')->all() as $gsisDeduction)
                                                                            <th scope="col"
                                                                                class="text-center align-middle ">
                                                                                {{ $gsisDeduction->description }}</th>
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            @if ($hasHDMF)
                                                                @if ($joDeductions->countBy('deduction_group')['HDMF'] > 1)
                                                                    @foreach ($joDeductions->where('deduction_group', '=', 'HDMF')->sortBy('sort_position')->all() as $hdmfDeduction)
                                                                        <th scope="col"
                                                                            class="text-center align-middle ">
                                                                            {{ $hdmfDeduction->description }}</th>
                                                                    @endforeach
                                                                @endif
                                                            @endif

                                                            @if ($hasPHIC)
                                                                @if ($joDeductions->countBy('deduction_group')['PHIC'] > 1)
                                                                    @foreach ($joDeductions->where('deduction_group', '=', 'PHIC')->sortBy('sort_position')->all() as $phicDeduction)
                                                                        <th scope="col"
                                                                            class="text-center align-middle ">
                                                                            {{ $phicDeduction->description }}</th>
                                                                    @endforeach
                                                                @endif
                                                            @endif

                                                            @if ($hasCOOP)
                                                                @if ($joDeductions->countBy('deduction_group')['COOP'] > 1)
                                                                    @foreach ($joDeductions->where('deduction_group', '=', 'COOP')->sortBy('sort_position')->all() as $coopDeduction)
                                                                        <th scope="col"
                                                                            class="text-center align-middle ">
                                                                            {{ $coopDeduction->description }}</th>
                                                                    @endforeach
                                                                @endif
                                                            @endif

                                                            @if ($hasDISALLOWANCE)
                                                                @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] > 1)
                                                                    @foreach ($joDeductions->where('deduction_group', '=', 'DISALLOWANCE')->sortBy('sort_position')->all() as $disallowanceDeduction)
                                                                        <th scope="col"
                                                                            class="text-center align-middle ">
                                                                            {{ $disallowanceDeduction->description }}
                                                                        </th>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                            {{-- @if($isLessFifteen == false) --}}
                                                        @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')

                                                                <th scope="col"
                                                                    rowspan="1"
                                                                    class="text-center align-middle ">TOTAL
                                                                </th>

                                                                <th scope="col"
                                                                    rowspan="1"
                                                                    class="text-center align-middle ">1ST HALF
                                                                </th>

                                                                <th scope="col" 
                                                                    rowspan="1"
                                                                    class="text-center align-middle ">2ND HALF
                                                                </th>
                                                           
                                                            @endif
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            if (
                                                                $this->payrollEmploymentStatus == 'Coterminous' ||
                                                                $this->payrollEmploymentStatus == 'Permanent'
                                                            ) {

                                                                $payrollUsers = $payrollFund->users
                                                                    ->where(function ($query) {
                                                                        return $query
                                                                            ->where('employment_status', 'COTERMINOUS')
                                                                            ->orWhere('employment_status', 'PERMANENT');
                                                                    })
                                                                    ->where('is_active', 1)
                                                                    ->sortBy('full_name');
                                                            } else {
                                                                $payrollUsers = $payrollFund->users
                                                                    ->where(function ($query) {
                                                                        $query
                                                                            ->where('employment_status', '<>', 'COTERMINOUS')
                                                                            ->orWhere(
                                                                                'employment_status',
                                                                                '<>',
                                                                                'PERMANENT',
                                                                            );
                                                                    })
                                                                    ->sortBy('full_name');
                                                            }
                                                        @endphp

                                                        @foreach ($payrollUsers as $payrollUser)
                                                            @if ($payrollFund->id == $payrollUser->fund_id)
                                                                {{-- @if ($payrollUserSection->id == $payrollUser->id) --}}
                                                                @if (
                                                                    $payrollUserSection->office ==
                                                                        $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office']
                                                                )
                                                                    @php
                                                                        $counter = $counter + 1;
                                                                    @endphp
                                          
                                                                    <tr
                                                                        @if (($payrollUser->basic_pay - $payrollUser->total_user_deduction + $payrollUser->total_user_allowance < 5000) && $isLessFifteen == 'full_month' || ($payrollUser->basic_pay - $payrollUser->total_user_deduction + $payrollUser->total_user_allowance < 0) && ($isLessFifteen == 'less_fifteen_first_half' || $isLessFifteen == 'less_fifteen_second_half')) style="background-color: rgba(245, 94, 39, 0.172)" @endif>
                                                                        <td scope="row" style="width: 50px;"
                                                                            class="text-center align-middle">
                                                                            <div class="form-switch w-0 p-0 pt-1">
                                                                                <span
                                                                                    style="">{{ $counter }}</span>
                                                                                <br>
                                                                                <input
                                                                                    class="form-check-input myCheckInput{{ $payrollUserSection->id }} m-auto"
                                                                                    type="checkbox"
                                                                                    wire:change="updateIsActive([{{ $payrollUser->id }}])"
                                                                                    {{ $payrollUser->include_to_payroll ? 'checked' : '' }}>

                                                                            </div>
                                                                        </td>
                                                                        <td scope="row" class="text-start">
                                                                            <strong>
                                                                                <a href="#" data-bs-toggle="modal"
                                                                                    data-bs-target="#employeeProfileModal"
                                                                                    wire:click="showEmployeeProfile({{ $payrollUser->id }})">
                                                                                    {{ $payrollUser->full_name }}
                                                                                </a>
                                                                            </strong>
                                                                        </td>
                                                                        <td scope="row" class="text-start">
                                                                            {{ $payrollUser->position }} /
                                                                            {{ $payrollUser->sg_jg }}</td>
                                                                        <td scope="row"
                                                                            class="text-center align-middle p-0">
                                                                            @livewire('modals.add-individual-attendance-modal', ['startDate' => $payrollDateFrom, 'endDate' => $payrollDateTo, 'userId' => $payrollUser->id, 'userDailyRate' => $payrollUser->daily_rate, 'userSgJg' => $payrollUser->sg_jg, 'isLessFifteen' => $isLessFifteen], key($payrollUser->id) )
                                                                        </td>
                                                                        @if (strtoupper($payrollUser->employment_status) == 'JOB ORDER' &&
                                                                                strtoupper($payrollUser->employment_status) == 'CONTRACT OF SERVICE')
                                                                            <td scope="row">
                                                                                {{ number_format((float) $payrollUser->monthly_rate, 2) }}
                                                                            </td>
                                                                        @elseif(strtoupper($payrollUser->employment_status) == 'PERMANENT' &&
                                                                                strtoupper($payrollUser->employment_status) == 'COTERMINOUS')
                                                                            <td scope="row">
                                                                                {{ number_format(bcdiv((float) $payrollUser->daily_rate * 22, 1, 2), 2) }}
                                                                            </td>
                                                                        @else
                                                                            <td scope="row">
                                                                                {{ number_format((float) $payrollUser->daily_rate, 2) }}
                                                                            </td>
                                                                        @endif

                                                                        <td scope="row">
                                                                            {{ number_format((float) $payrollUser->basic_pay, 2) }}
                                                                        </td>

                                                                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')

                                                                            @if ($hasPERA)
                                                                                @if (isset($payrollUser->user_allowances['PERA']))
                                                                                    @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['PERA']; $y++)
                                                                                        <td>
                                                                                            @foreach ($payrollUser->user_allowances['PERA'] as $pera)
                                                                                            {{-- @if ($pera['sort_position'] == $y + 1) --}}
                                                                                                {{  number_format((float) $pera['pivot']['amount'], 2) }}
                                                                                                {{-- @endif --}}
                                                                                            @endforeach
                                                                                        </td>
                                                                                    @endfor
                                                                                @else
                                                                                    @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['PERA']; $y++)
                                                                                        <td></td>
                                                                                    @endfor
                                                                                @endif
                                                                            @else
                                                                            {{-- <td></td> --}}
                                                                            @endif

                                                                            @if ($hasMEDICAL)
                                                                                @if (isset($payrollUser->user_allowances['MEDICAL']))
                                                                                    @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                                                                        <td>
                                                                                            @foreach ($payrollUser->user_allowances['MEDICAL'] as $medical)
                                                                                            {{-- @if ($medical['sort_position'] == $y + 1) --}}
                                                                                                {{  number_format((float) $medical['pivot']['amount'], 2) }}
                                                                                                {{-- @endif --}}
                                                                                            @endforeach
                                                                                        </td>
                                                                                    @endfor
                                                                                @else
                                                                                    @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                                                                        <td></td>
                                                                                    @endfor
                                                                                @endif
                                                                            @else
                                                                            {{-- <td></td> --}}
                                                                        @endif

                                                                        @if ($hasMEAL)
                                                                            @if (isset($payrollUser->user_allowances['MEAL']))
                                                                                @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_allowances['MEAL'] as $meal)
                                                                                        {{-- @if ($meal['sort_position'] == $y + 1) --}}
                                                                                            {{  number_format((float) $meal['pivot']['amount'], 2) }}
                                                                                            {{-- @endif --}}
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                        {{-- <td></td> --}}
                                                                        @endif

                                                                        @if ($hasCHILD)
                                                                            @if (isset($payrollUser->user_allowances['CHILD']))
                                                                                @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_allowances['CHILD'] as $child)
                                                                                        {{-- @if ($child['sort_position'] == $y + 1) --}}
                                                                                            {{  number_format((float) $child['pivot']['amount'], 2) }}
                                                                                            {{-- @endif --}}
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                        {{-- <td></td> --}}
                                                                        @endif

                                                                            {{-- OLD CODE --}}
                                                                            {{-- @if (isset($payrollUser->user_allowances['MEAL']))
                                                                                @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_allowances['MEAL'] as $meal)
                                                                                            @if ($meal['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $meal['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif --}}

                                                                            <td scope="row">
                                                                                {{ number_format((float) $payrollUser->total_user_allowance, 2) }}
                                                                            </td>
                                                                            {{-- THIS IS END IF OF THE CONDITION FOR ALLOWANCES --}}
                                                                        @endif
                                                                        <td scope="row">
                                                                            {{ number_format((float) $payrollUser->total_user_allowance + (float) $payrollUser->basic_pay, 2) }}
                                                                        </td>


                                                                        @if ($hasTAX)
                                                                            @if (isset($payrollUser->user_deductions['TAX']))
                                                                                @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['TAX']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_deductions['TAX'] as $tax)
                                                                                            @if ($tax['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $tax['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['TAX']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                            <td></td>
                                                                        @endif

                                                                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                                strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                                                            @if ($hasGSIS)
                                                                                @if (isset($payrollUser->user_deductions['GSIS']))
                                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['GSIS']; $y++)
                                                                                        <td>
                                                                                            @foreach ($payrollUser->user_deductions['GSIS'] as $gsis)
                                                                                                @if ($gsis['sort_position'] == $y + 1)
                                                                                                    {{ number_format((float) $gsis['pivot']['amount'], 2) }}
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </td>
                                                                                    @endfor
                                                                                @else
                                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['GSIS']; $y++)
                                                                                        <td></td>
                                                                                    @endfor
                                                                                @endif
                                                                            @else
                                                                                <td></td>
                                                                            @endif
                                                                        @endif

                                                                        @if ($hasHDMF)
                                                                            @if (isset($payrollUser->user_deductions['HDMF']))
                                                                                @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_deductions['HDMF'] as $hdmf)
                                                                                            @if ($hdmf['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $hdmf['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                            <td></td>
                                                                        @endif

                                                                        @if ($hasPHIC)
                                                                            @if (isset($payrollUser->user_deductions['PHIC']))
                                                                                @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_deductions['PHIC'] as $phic)
                                                                                            @if ($phic['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $phic['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                            <td></td>
                                                                        @endif

                                                                        @if ($hasCOOP)
                                                                            @if (isset($payrollUser->user_deductions['COOP']))
                                                                                @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['COOP']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_deductions['COOP'] as $COOP)
                                                                                            @if ($COOP['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $COOP['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['COOP']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                            <td></td>
                                                                        @endif


                                                                        @if ($hasDISALLOWANCE)
                                                                            @if (isset($payrollUser->user_deductions['DISALLOWANCE']))
                                                                                @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_deductions['DISALLOWANCE'] as $disallowance)
                                                                                            @if ($disallowance['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $disallowance['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                            <td></td>
                                                                        @endif

                                                                        @if ($hasOTHER)
                                                                            @if (isset($payrollUser->user_deductions['OTHER']))
                                                                                @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['OTHER']; $y++)
                                                                                    <td>
                                                                                        @foreach ($payrollUser->user_deductions['OTHER'] as $otherDed)
                                                                                            @if ($otherDed['sort_position'] == $y + 1)
                                                                                                {{ number_format((float) $otherDed['pivot']['amount'], 2) }}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['OTHER']; $y++)
                                                                                    <td></td>
                                                                                @endfor
                                                                            @endif
                                                                        @else
                                                                            {{-- <td></td> --}}
                                                                        @endif



                                                                        <td scope="row">
                                                                            {{ number_format((float) $payrollUser->total_user_deduction, 2) }}
                                                                        </td>

                                                                        <td scope="row">
                                                                            <b>{{ number_format(($payrollUser->first_half) + ($payrollUser->second_half), 2) }} </b>
                                                                        </td>
                                                                        
                                                                        {{-- @if($isLessFifteen == false) --}}
                                                                        @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')

                                                                        <td scope="row">
                                                                           <b> {{ number_format(bcdiv($payrollUser->first_half, 1, 2), 2) }} </b>
                                                                        </td>
                                                                        <td scope="row">
                                                                           <b> {{ number_format(bcdiv($payrollUser->second_half, 1, 2), 2) }} </b>

                                                                        </td>
                                                                        @endif

                                                                    </tr>
                                                                    {{-- @endif --}}
                                                                @endif
                                                            @endif
                                                        @endforeach

                                                    </tbody>
                                                    <tfoot class="fw-bold sticky-bottom">
                                                        <tr>
                                                            <td colspan="2">TOTAL NET PAY</td>
                                                            <td colspan="3" class="text-start">
                                                                {{ Helper::numberToWord(sprintf('%.2f', $payrollUserSection->total_net_pay)) }}
                                                            </td>
                                                            <td>{{ number_format((float) $payrollUserSection->total_basic_pay, 2) }}
                                                            </td>

                                                            @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                                                    strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')

                                                            @if ($hasPERA)
                                                                {{-- TOTAL PERA --}}
                                                                @if (isset($payrollUserSection->grand_total_allowance))
                                                                    @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['PERA']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                                                                @if ($grandTotalAllowance['allowance_group'] == 'PERA')
                                                                                    @if ($grandTotalAllowance['sort_position'] == $y + 1)
                                                                                        {{-- {{ number_format((float)$grandTotalAllowance['total'],2) }} --}}
                                                                                        {{ number_format(bcdiv((float) $grandTotalAllowance['total'], 1, 2), 2) }}
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['PERA']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                {{-- <td></td> --}}
                                                            @endif

                                                            {{-- TOTAL MEDICAL --}}
                                                            @if ($hasMEDICAL)
                                                                @if (isset($payrollUserSection->grand_total_allowance))
                                                                    @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                                                                @if ($grandTotalAllowance['allowance_group'] == 'MEDICAL')
                                                                                    @if ($grandTotalAllowance['sort_position'] == $y + 1)
                                                                                        {{-- {{ number_format((float)$grandTotalAllowance['total'],2) }} --}}
                                                                                        {{ number_format(bcdiv((float) $grandTotalAllowance['total'], 1, 2), 2) }}
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                {{-- <td>1</td> --}}
                                                            @endif

                                                            {{-- TOTAL MEAL --}}
                                                            @if ($hasMEAL)
                                                                @if (isset($payrollUserSection->grand_total_allowance))
                                                                    @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                                                                @if ($grandTotalAllowance['allowance_group'] == 'MEAL')
                                                                                    @if ($grandTotalAllowance['sort_position'] == $y + 1)
                                                                                        {{-- {{ number_format((float)$grandTotalAllowance['total'],2) }} --}}
                                                                                        {{ number_format(bcdiv((float) $grandTotalAllowance['total'], 1, 2), 2) }}
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                {{-- <td>2</td> --}}
                                                            @endif

                                                            {{-- TOTAL CHILD --}}
                                                            @if ($hasCHILD)
                                                                @if (isset($payrollUserSection->grand_total_allowance))
                                                                    @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                                                                @if ($grandTotalAllowance['allowance_group'] == 'CHILD')
                                                                                    @if ($grandTotalAllowance['sort_position'] == $y + 1)
                                                                                        {{-- {{ number_format((float)$grandTotalAllowance['total'],2) }} --}}
                                                                                        {{ number_format(bcdiv((float) $grandTotalAllowance['total'], 1, 2), 2) }}
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif


                                                                 @endif
                                                            @else
                                                                {{-- <td>3</td> --}}
                                                            @endif

                                                            {{-- TOTAL ALLOWANCES --}}
                                                            <td>{{ number_format((float)sprintf('%.2f', $payrollUserSection->total_allowance), 2) }}
                                                            </td>


                                                            {{-- TOTAL GROSS PAY --}}
                                                            <td>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_allowance + $payrollUserSection->total_basic_pay), 2)}}</td>


                                                            {{-- TOTAL TAX --}}
                                                            @if ($hasTAX)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['TAX']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'TAX')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['TAX']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif

                                                            @if ($hasGSIS)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['GSIS']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'GSIS')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['GSIS']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif


                                                            {{-- TOTAL HDMF --}}
                                                            @if ($hasHDMF)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'HDMF')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif


                                                            {{-- TOTAL PHIC --}}
                                                            @if ($hasPHIC)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'PHIC')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif


                                                            {{-- TOTAL COOP LOAN --}}
                                                            @if ($hasCOOP)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['COOP']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'COOP')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['COOP']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif


                                                            {{-- TOTAL DISALLOWANCE --}}
                                                            @if ($hasDISALLOWANCE)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'DISALLOWANCE')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif


                                                            {{-- TOTAL OTHER --}}
                                                            @if ($hasOTHER)
                                                                @if (isset($payrollUserSection->grand_total_deduction))
                                                                    @for ($y = 0; $y < $joDeductions->countBy('deduction_group')['OTHER']; $y++)
                                                                        <td>
                                                                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                                                                @if ($grandTotalDeduction['deduction_group'] == 'OTHER')
                                                                                    @if ($grandTotalDeduction['sort_position'] == $y + 1)
                                                                                        <strong>{{ number_format((float) $grandTotalDeduction['total'], 2) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                @else
                                                                    @for ($y = 1; $y <= $joDeductions->countBy('deduction_group')['OTHER']; $y++)
                                                                        <td></td>
                                                                    @endfor
                                                                @endif
                                                            
                                                                
                                                            @endif


                                                            <td>{{ number_format((float) sprintf('%.2f', $payrollUserSection->total_deduction), 2) }}
                                                            </td>
                                                            <td>{{ number_format((float) sprintf('%.2f', $payrollUserSection->total_net_pay), 2) }}
                                                            </td>

                                                            {{-- @if($isLessFifteen == false) --}}
                                                            @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')

                                                            <td>{{ number_format((float) sprintf('%.2f', $payrollUserSection->total_first_half), 2) }}
                                                            </td>
                                                            <td>{{ number_format((float) sprintf('%.2f', $payrollUserSection->total_second_half), 2) }}
                                                            </td>
                                                            @endif
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $counter = 0;
                                @endphp

                                @break
                            @endforeach
                        @endforeach


                    </div>
                @endif
            @endforeach
        </div>
    </font>

    <!-- Modal -->
    <div class="modal fade" id="printPreviewJoPayrollModal" tabindex="-1"
        aria-labelledby="printPreviewJoPayrollModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printPreviewJoPayrollModalLabel">Print Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div wire:loading>
                        <div class=" bg-white">
                            <div
                                class="h-100 w-100 position-absolute translate-middle top-50 start-50 bg-white d-flex align-items-center justify-content-center">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($this->isEmptyFund)
                        <h1 class="text-muted">No data to be shown</h1>
                    @endif
                    <div class="h-100" id="pdfPreview">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary" wire:click="createPDF()">Print</button> --}}
                </div>
            </div>
        </div>
    </div>


</div>

@push('scripts')
    <script type="text/javascript">
        window.addEventListener('srollLock', event => {
            alert('Scroll locking set to: ' + event.detail.overflow);
            document.body.style.overflow = event.detail.overflow;
        })

        window.addEventListener('testPdf', event => {
            let pdfWindow = document.getElementById('pdfPreview');
            pdfWindow.innerHTML =
                `<iframe width='100%' height='100%' src='data:application/pdf;base64, ${encodeURI(event.detail.mypdf)} ${event.detail.toolbar}'></iframe>`;
        })

        document.addEventListener("keydown", function(event) {

            if (event.ctrlKey && event.keyCode == 80) {
                event.stopPropagation();
                event.preventDefault();

                var printPayrollJoModal = new bootstrap.Modal(document.getElementById('printPayrollJoModal'));
                printPayrollJoModal.show();

            }
            // else if(event.ctrlKey && event.keyCode == 70){
            //   event.stopPropagation();
            //   event.preventDefault();

            //   var searchWindow = new bootstrap.Modal(document.getElementById('searchWindow'),{
            //     backdrop: true
            //   });
            //   searchWindow.show();

            // }
        });

        function changeSize(screenId, ssize, tableContainerId, maxBtnId) {
            var screen = document.getElementById(screenId);

            console.log(tableContainerId)
            var tableContainer = document.getElementById(tableContainerId);

            tableContainer.style.maxHeight = '90vh';

            // Remove any existing classes that may affect the size and position
            screen.classList.remove('position-relative', 'p-3', 'mb-3');

            // Add classes to make it fill the screen
            //screen.classList.add('position-fixed', 'top-0', 'left-0', 'vh-100', 'vw-100', 'bg-light');

            screen.classList.add('bg-white', 'position-fixed', 'w-100', 'ms-1', 'h-100', 'top-0', 'start-0', 'd-flex',
                'align-items-start', 'px-2');

            // Optionally, you can remove the border and shadow to give a cleaner look
            // screen.classList.remove('border', 'border-light', 'shadow');

            screen.style.zIndex = '9999';

            // Update other child elements if needed
            // Example: Set full height for child elements inside the screen
            // screen.querySelectorAll('.row').forEach(function(row) {
            //   row.classList.add('h-100');
            // });

            // You may also want to update the button text to "Minimize" after maximizing
            // var maximizeButton = screen.querySelector('button');
            var maximizeButton = document.getElementById(maxBtnId);

            console.log(maximizeButton);

            maximizeButton.innerText = 'Minimize';

            // Add an event listener to the button to restore the original size when clicked again
            maximizeButton.onclick = function() {
                restoreSize(screenId, tableContainerId, maxBtnId);
                return false;

            };

            console.log(screen);
        }



        // Function to restore the original size of the screen
        function restoreSize(screenId, tableContainerId, maxBtnId) {
            var screen = document.getElementById(screenId);
            var tableContainer = document.getElementById(tableContainerId);

            tableContainer.style.maxHeight = '700px';


            // Remove classes added for maximizing
            screen.classList.remove('bg-white', 'position-fixed', 'w-100', 'h-100', 'top-0', 'start-0', 'd-flex',
                'align-items-start', 'px-2');

            // Restore original classes
            screen.classList.add('row');

            screen.style.zIndex = '90';


            // Restore button text to "Maximize"
            var maximizeButton = screen.querySelector('button');
            maximizeButton.innerText = 'Maximize';

            // Restore button onclick function
            maximizeButton.onclick = function() {
                changeSize(screenId, 'max', tableContainerId, maxBtnId);
                return false;
            };

            console.log(screen);
        }



        document.addEventListener("DOMContentLoaded", function(event) {
            var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })

        });
    </script>
@endpush
