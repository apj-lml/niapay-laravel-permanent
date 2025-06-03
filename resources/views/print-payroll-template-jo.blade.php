  <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NIAPay</title>

    <!-- Customized Bootstrap Stylesheet -->
    {{-- <link type="text/css" href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link type="text/css" href="{{ base_path().'/public/css/bootstrap.min.css' }}" rel="stylesheet"> --}}

    <!-- Template Stylesheet -->
    {{-- <link type="text/css" href="{{ base_path().'/public/css/style.css' }}" rel="stylesheet"> --}}
    {{-- <link type="text/css" href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}
<style type="text/css">


 @page { size: 21.59cm 33.02cm landscape; margin-top: 0; }
 /* @page { size: 21.59cm 39cm landscape; margin-top: 0; } */

     body {
         /* background: #fb887c; */
         /* color: #fff; */
         margin-left: -25px;
         margin-right: -25px;
         margin-top:20px;
         margin-bottom: -10px; 
         font-family: Cambria,Georgia,serif;
     }

     h1 {
         font-family: "proxima-nova",sans-serif;
         letter-spacing: -0.01em;
         font-weight: 700;
         font-style: normal;
         font-size: 60px;
         margin-left: -3px;
         line-height: 1em;
         color: #ff00;
         text-align: center;
         margin-bottom: 8px;
         text-rendering: optimizeLegibility;
     }

     caption {
      border: 1.5px solid black;
     }

     caption>h2 {
      margin-top: 0;
      margin-bottom: 0;
     }

     caption>p {
      margin-top: 0;
      margin-bottom: 30px;
     }

     table {
         margin: 0px;
         table-layout: fixed;
         width: 100%;
         border-left: 2px solid black;
         border-right: 2px solid black;
         border-bottom: 2px solid black;

     }

     table.payroll td{
      text-align: right;
     } 

     table, th, td {
         
         border-collapse: collapse;
     }

     th, td {
         padding: 2.5px;
         font-size:7px;
         border: 0.5px solid black;
         /* height:15px */
     }

     th {
      font-size:6.5px;
      font-weight: bolder;
      word-wrap: break-word;
     }

     .page-break {
        page-break-after: always;
    }

    .header { grid-area: header; }
    .main { grid-area: main; }
    .footer { grid-area: footer; }
    .signatory { grid-area: footer; }

    .grid-container {
      display: grid;
      grid-template-areas:
        'header header header header header header'
        'main main main main main main'
        'footer footer footer footer footer footer';
      gap: 5px;
      /* background-color: #2196F3; */
      /* padding: 10px; */
    }

    .grid-container > div {
      /* background-color: rgba(255, 255, 255, 0.8);
      text-align: center;
      padding: 20px 0;
      font-size: 30px; */
    }

    .fw-sm{
      font-size: 9px;
      text-align: left;
      margin-bottom: 0;
      margin-top: 1px;
    }

    .obligation{
      /* border:1px solid red; */
      border: 0;
      width:80%;
      margin-left: 35px;
      margin-top: 15px;
    }

    table.obligation td{
      /* border:1px dashed red;  UNCOMMENT TO SEE BORDERS: 0; */
      border: 0;
      padding: 0;
    }

    table.signatory td{
      /* border:1px dashed red;  UNCOMMENT TO SEE BORDERS: 0; */
      border: 0;
    }

    table.signatory{
      /* border:1px dashed red;  UNCOMMENT TO SEE BORDERS: 0; */
      border: 0;
    }

  .signatory-job{
    margin-bottom: 30px;
  
  }
  .signatory-name{
    margin-bottom: 0;
    margin-top: auto;
    text-align: center;
  }
  .signatory-position{
    margin-top: 0;
    text-align: center;
  }
  hr{
    border: 0.2px solid ;
    width: 150px;
    margin-top: 0;
    margin-bottom: 0;
  }
  .logo-cointainer{
    position: relative;
  }
  .logo{
    position: absolute;
    margin-top: 5px;
    margin-left: 5px;
  }

</style>

</head>

<body>
  <div class="grid-container">
      {{-- @foreach ($payrollFunds as $payrollFund) --}}
        {{-- @foreach ($payrollUserSections as $payrollUserSection) --}}
        <div class="">
          {{-- <div @if(!$loop->last) class="page-break" @endif> --}}

            @php

                if (!function_exists('formatRanges')) {
                    function formatRanges($array) {
                        sort($array);
                        $ranges = [];
                        $start = $array[0];
                        $end = $array[0];

                        for ($i = 1; $i < count($array); $i++) {
                            if ($array[$i] == $end + 1) {
                                $end = $array[$i];
                            } else {
                                if ($start != $end) {
                                    $ranges[] = $start . '-' . $end;
                                } else {
                                    $ranges[] = $start;
                                }
                                $start = $array[$i];
                                $end = $array[$i];
                            }
                        }

                        if ($start != $end) {
                            $ranges[] = $start . '-' . $end;
                        } else {
                            $ranges[] = $start;
                        }

                        return 'Item no/s. ' . implode(', ', array_slice($ranges, 0, -1)) . (count($ranges) > 1 ? ' & ' : '') . end($ranges);
                    }
                }
            


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
                    // $hasNegativeNetpay = 0;
                    $hasTAX = false;
                    $taxCol = 0;
                    
                    foreach ($joDeductions as $deduction) {
                        if ($deduction->deduction_group == 'TAX') {
                            $hasTAX = true;
                            // break;
                        }
                    }
                    if($hasTAX == false){
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

                    if($hasGSIS == false){
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
                    if($hasHDMF == false){
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
                    if($hasPHIC == false){
                      $phicCol = 1;
                    }

                    $hasCOOP = false;
                    $coopCol = 0;
                    foreach ($joDeductions as $deduction) {
                        if ($deduction->deduction_group == 'COOP') {
                            $hasCOOP = true;
                            // break;
                        }
                    }
                    if($hasCOOP == false){
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
                    if($hasDISALLOWANCE == false){
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

                @endphp


          <div class="main">
            <caption>
              <div class="logo-container">
                <img class="logo" src="{{ public_path('img/op-logo.jpg') }}" alt="op logo" height="75px" width="75px">
                <img class="logo" src="{{ public_path('img/nia.jpg') }}" alt="nia logo" height="75px" width="75px" style="margin-left: 75px;">
                <img class="logo" src="{{ public_path('img/bagong-pilipinas.jpg') }}" alt="bagong pilipinas" height="75px" width="75px" style="margin-left: 1375px;">
              </div>
                <h2>L A B O R &nbsp; P A Y R O L L</h2>
                <p>for the period {{ $payrollDateFrom->format('F') }} {{ $payrollDateFrom->format('j') }}-{{ $payrollDateTo->format('j') }}, {{ $payrollDateFrom->format('Y') }}</p>
                <div style="margin-left: 5px; margin-bottom: 3px;">
                  <div class="col-sm-6">
                  <p class="fw-sm">Entity Name: <strong>NATIONAL IRRIGATION ADMINISTRATION - PANGASINAN IMO / {{ $office }}</strong></p>
  
                  </div>
                  <div class="col-sm-6">
                  <p class="fw-sm" style="display: inline; float: right; margin-right:60px; margin-top:-10px;">CHARGED TO: <strong>{{ $payrollFund->fund_description }}</strong></p>
  
                  </div>
                  {{-- <p class="fw-sm" style="display: inline; float: left;">We acknowledge receipt of cash shown opposite our name as full compensation for services rendered for the period covered.</p> --}}
                </div>
              </caption>
              <table class="payroll">
                <thead>
                  <tr>
                    <th scope="col" rowspan="3" style="width:12%; border-right: 1.5px solid black;">N A M E</th>
                    <th scope="col" rowspan="3" style="width:12%;">POSITION TITLE / JG</th>
                    <th scope="col" rowspan="3" style="">NUMBER OF DAYS WORKED</th>
                    <th scope="col" rowspan="3" style="">DAILY RATE</th>
                    <th scope="col" rowspan="3" style="border-right: 1.5px solid black;">BASIC PAY</th>
                    @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                        <th scope="col"
                            colspan="{{ $joAllowances->count() }}"
                            class="text-center align-middle ">A L L O W A N C E S
                        </th>
                        <th scope="col" rowspan="3"
                            class="text-center align-middle">Total Allowances
                        </th>
                        <th scope="col" rowspan="3"
                            class="text-center align-middle">GROSS PAY
                        </th>
                    @endif
                    
                    @php


                      $inactiveDeduction = collect();


                    @endphp

                    <th scope="col" colspan="{{ $joDeductions->count() + $hdmfCol + $gsisCol + $taxCol + $phicCol + $coopCol + $disallowanceCol - $inactiveDeduction->count() }}" class="text-center align-middle ">D E D U C T I O N S</th>

                    <th scope="col" rowspan="3">TOTAL DEDUCTIONS</th>
                    {{-- <th scope="col" rowspan="3" style="border-left: 1.5px solid black;">NET PAY</th> --}}
                    <th scope="col"
                          @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                              
                              rowspan="2"
                              colspan="3"
                          @else
                              rowspan="3"
                              colspan="1"
                          
                          @endif
                          class="text-center align-middle" style="border-left: 1.5px solid black;">NET PAY
                      </th>
                  </tr>
    
                  <tr>
                    @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                      <th scope="col"
                          @if ($joAllowances->countBy('allowance_group')['PERA'] <= 1) 
                            rowspan="2" 
                          @else
                            colspan="{{ $joAllowances->countBy('allowance_group')['PERA'] }}" 
                          @endif
                            class="text-center align-middle">
                            PERA
                        </th>
                        
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


                    <th scope="col" 
                          @if(!$hasTAX || $joDeductions->countBy('deduction_group')['TAX'] <= 1)
                            rowspan="2"
                          @else
                            colspan="{{ $joDeductions->countBy('deduction_group')['TAX'] }}"
                          @endif
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
                          @if(!$hasHDMF  || $joDeductions->countBy('deduction_group')['HDMF'] <= 1)
                            rowspan="2" 
                          @else
                            colspan="{{ $joDeductions->countBy('deduction_group')['HDMF'] }}"
                          @endif
                            class="text-center align-middle">
                            HDMF
                        </th>


                    <th scope="col"  
                          @if(!$hasPHIC || $joDeductions->countBy('deduction_group')['PHIC'] <= 1)
                            rowspan="2" 
                          @else
                          colspan="{{ $joDeductions->countBy('deduction_group')['PHIC'] }}"
                          @endif
                          class="text-center align-middle">
                          PHIC
                        </th>


                    <th scope="col" 
                        {{-- @if ($joDeductions->countBy('deduction_group')['COOP'] <= 1) --}}
                        @if(!$hasCOOP || $joDeductions->countBy('deduction_group')['COOP'] <= 1)
                          rowspan="2"
                        @else
                          colspan="{{ $joDeductions->countBy('deduction_group')['COOP'] }}"
                        @endif
                          class="text-center align-middle">
                          COOP LOAN
                        </th>

                    <th scope="col"
                    {{-- @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] <= 1) --}}
                    @if(!$hasDISALLOWANCE || $joDeductions->countBy('deduction_group')['DISALLOWANCE'] <= 1)
                      rowspan="2" 
                    @else
                      colspan="{{ $joDeductions->countBy('deduction_group')['DISALLOWANCE'] }}"
                    @endif
                      class="text-center align-middle">
                      DISALLOWANCE
                    </th>
    
                    {{-- OTHER DED --}}
                    @if ($joDeductions->countBy('deduction_group')->has('OTHER'))
                      @if ($joDeductions->countBy('deduction_group')['OTHER'] >= 1)
                        @foreach ($joDeductions->where('deduction_group', '=', 'OTHER')->sortBy('sort_position')->all() as $otherDed)
                          <th scope="col" rowspan="2" class="text-center align-middle">{{ $otherDed->description }}</th>
                        @endforeach
                      @endif
                    @endif
                  </tr>
    
                  <tr>

                    @if($hasTAX)
                          @if ($joDeductions->countBy('deduction_group')['TAX'] > 1)
                            @foreach ($joDeductions->where('deduction_group', '=', 'TAX')->sortBy('sort_position')->all() as $taxDeduction)
                            <th scope="col" class="text-center align-middle ">{{ $taxDeduction->description }}</th>
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
    

                    @if($hasHDMF)
                          @if ($joDeductions->countBy('deduction_group')['HDMF'] > 1)
                            @foreach ($joDeductions->where('deduction_group', '=', 'HDMF')->sortBy('sort_position')->all() as $hdmfDeduction)
                            <th scope="col" class="text-center align-middle ">{{ $hdmfDeduction->description }}</th>
                            @endforeach
                          @endif
                        @endif
                    
    
                    @if($hasPHIC)
                          @if ($joDeductions->countBy('deduction_group')['PHIC'] > 1)
                            @foreach ($joDeductions->where('deduction_group', '=', 'PHIC')->sortBy('sort_position')->all() as $phicDeduction)
                            <th scope="col" class="text-center align-middle ">{{ $phicDeduction->description }}</th>
                            @endforeach
                          @endif
                        @endif  
    
                    @if($hasCOOP)
                      @if ($joDeductions->countBy('deduction_group')['COOP'] > 1)
                        @foreach ($joDeductions->where('deduction_group', '=', 'COOP')->sortBy('sort_position')->all() as $coopDeduction)
                        <th scope="col" class="text-center align-middle ">{{ $coopDeduction->description }}</th>
                        @endforeach
                      @endif
                    @endif
                    

                    @if($hasDISALLOWANCE)
                    @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] > 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'DISALLOWANCE')->sortBy('sort_position')->all() as $disallowanceDeduction)
                      <th scope="col" class="text-center align-middle ">{{ $disallowanceDeduction->description }}</th>
                      @endforeach
                    @endif
                  @endif
                  {{-- @if($isLessFifteen == false) --}}
                  @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                      <th scope="col"
                          rowspan="1"
                          style="border-left: 1.5px solid black;"
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
                <tr>
                  <td colspan="4" style="font-size: 8px; height:2px; padding:0px;"></td>
                  <td style="border-right: 1.5px solid black; text-align:center; font-size: 8px; height:2px; padding:0px;">A</td>
                  @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                    <td colspan="{{ $joAllowances->count() }}" style="font-size: 8px; height:2px; padding:0px;"></td>
                    <td colspan="" style="text-align:center; font-size: 8px; height:2px; padding:0px;">B</td>
                    <td colspan="" style="text-align:center; font-size: 8px; height:2px; padding:0px;">C = A + B</td>
                  @endif
                  <td colspan="{{ $joDeductions->count() + $gsisCol + $hdmfCol + $taxCol + $phicCol + $coopCol + $disallowanceCol - $inactiveDeduction->count() }}" style="font-size: 8px; height:2px; padding:0px;"></td>
                  <td style="border-right: 1.5px solid black; text-align:center; font-size: 8px; height:2px; padding:0px;">D</td>
                  <td style="border-right: 1.5px solid black; text-align:center; font-size: 8px; height:2px; padding:0px;"
                  {{-- @if($isLessFifteen == false) --}}
                  @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                  colspan="3"
                  @endif

                  >E = C - D</td>
                </tr>
    
                  <tbody>
                    @php
                      $counter = 1;
                      $itemNo = array();

                      $payrollUsers = $payrollFund->users
                            ->where(function($query) {
                                return $query->where('employment_status', 'COTERMINOUS')
                                      ->orWhere('employment_status', 'PERMANENT');
                            })
                            ->where('is_active', 1)
                            ->sortBy('full_name');
                    @endphp
                        @foreach ($payrollUsers as $payrollUser)
                          @if(!$payrollUser->attendances->isEmpty())
                              @if ($payrollFund->id == $payrollUser->fund_id)
                            
                                {{-- @if ($payrollUserSection->id == $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['id']) --}}
                                @if($office == $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office'])
                                    
                                <tr @if(($payrollUser->basic_pay - $payrollUser->total_user_deduction + $payrollUser->total_user_allowance < 5000) && $isLessFifteen == 'full_month' || ($payrollUser->basic_pay - $payrollUser->total_user_deduction + $payrollUser->total_user_allowance < 0) && ($isLessFifteen == 'less_fifteen_first_half' || $isLessFifteen == 'less_fifteen_second_half')) style="background-color: rgba(245, 94, 39, 0.172)" @endif>
                                    <td scope="row" style="border-right: 1.5px solid black; font-size:8px; height:20px; text-align:left; position: relative;">
                                      <span style="position:absolute; margin-left: -19px; margin-top:5px;">{{ $counter }}</span>{{ $payrollUser->full_name }}
                                    </td>
         
                                    <td scope="row" style="font-size: 7px; text-align:left;">{{ $payrollUser->position }} / {{ $payrollUser->sg_jg }}</td>
        
                                    <td scope="row" style="text-align:center;">
                                      @foreach ($payrollUser->attendances as $attendance)
                                      @php
                                        $attendance->start_date = \Carbon\Carbon::parse($attendance->start_date);
                                        $attendance->end_date = \Carbon\Carbon::parse($attendance->end_date);
                                      @endphp
                                        @if ($attendance->start_date == $payrollDateFrom && $attendance->end_date == $payrollDateTo)
                                          {{ (number_format((float)$attendance->days_rendered, 3)) }}
                                        @endif
                                      @endforeach
                                    </td>
                                    
                                    <td scope="row">{{ number_format((float)$payrollUser->daily_rate, 2) }}</td>  
                                    <td scope="row" style="border-right: 1.5px solid black;"><strong>{{ number_format((float)$payrollUser->basic_pay, 2) }}</strong></td>
                                    {{-- <td scope="row" style="border-right: 1.5px solid black;">{{ number_format((float)$payrollUser->basic_pay, 2) }}</td> --}}
                                    

                                  @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                    @if ($hasPERA)
                                        @if (isset($payrollUser->user_allowances['PERA']))
                                            @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['PERA']; $y++)
                                                <td>
                                                    @foreach ($payrollUser->user_allowances['PERA'] as $pera)
                                                        @if ($pera['sort_position'] == $y + 1)
                                                            {{ number_format((float) $pera['pivot']['amount'], 2) }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endfor
                                        @else
                                            @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['PERA']; $y++)
                                                <td></td>
                                            @endfor
                                        @endif
                                      @endif

                                      @if ($hasMEDICAL)
                                        @if (isset($payrollUser->user_allowances['MEDICAL']))
                                            @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                                <td>
                                                    @foreach ($payrollUser->user_allowances['MEDICAL'] as $medical)
                                                        @if ($medical['sort_position'] == $y + 1)
                                                            {{ number_format((float) $medical['pivot']['amount'], 2) }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endfor
                                        @else
                                            @for ($y = 1; $y <= $joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                                <td></td>
                                            @endfor
                                        @endif
                                      @endif

                                      @if ($hasMEAL)
                                        @if (isset($payrollUser->user_allowances['MEAL']))
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
                                        @endif
                                      @endif

                                      @if ($hasCHILD)
                                        @if (isset($payrollUser->user_allowances['CHILD']))
                                            @for ($y = 0; $y < $joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                                <td>
                                                    @foreach ($payrollUser->user_allowances['CHILD'] as $child)
                                                        @if ($child['sort_position'] == $y + 1)
                                                            {{ number_format((float) $child['pivot']['amount'], 2) }}
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

                                        <td scope="row">
                                            <strong>{{ number_format((float) $payrollUser->total_user_allowance, 2) }}</strong>
                                        </td>
                                        {{-- THIS IS END IF OF THE CONDITION FOR ALLOWANCES --}}
                                    @endif

                                    {{-- GROSS PAY --}}
                                    <td><strong>{{ number_format((float)($payrollUser->basic_pay + $payrollUser->total_user_allowance), 2) }}</strong></td>

                                    @if($hasTAX)
                                    @if(isset($payrollUser->user_deductions['TAX']))
                                      @for($y=0; $y<$joDeductions->countBy('deduction_group')['TAX']; $y++)
                                        <td>
                                          @foreach ($payrollUser->user_deductions['TAX'] as $tax)
                                            @if ($tax['sort_position'] == $y+1)
                                              {{ number_format((float)$tax['pivot']['amount'],2) }}
                                            @endif
                                          @endforeach
                                        </td>
                                      @endfor
                                      @else
                                      @for($y=1; $y<=$joDeductions->countBy('deduction_group')['TAX']; $y++)
                                        <td></td>
                                      @endfor
                                    @endif
                                  @else
                                    <td></td>
                                  @endif

                                  @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' &&
                                          strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                                      @if($hasGSIS)
                                      @if(isset($payrollUser->user_deductions['GSIS']))
                                        @for($y=0; $y<$joDeductions->countBy('deduction_group')['GSIS']; $y++)
                                          <td>
                                            @foreach ($payrollUser->user_deductions['GSIS'] as $gsis)
                                              @if ($gsis['sort_position'] == $y+1)
                                                {{ number_format((float)$gsis['pivot']['amount'],2) }}
                                              @endif
                                            @endforeach
                                          </td>
                                        @endfor
                                      @else
                                        @for($y=1; $y<=$joDeductions->countBy('deduction_group')['GSIS']; $y++)
                                          <td></td>
                                        @endfor
                                      @endif
                                    @else
                                      <td></td>
                                    @endif
                                  @endif
        
                                  @if($hasHDMF)
                                  @if(isset($payrollUser->user_deductions['HDMF']))
                                    @for($y=0; $y<$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                      <td>
                                        @foreach ($payrollUser->user_deductions['HDMF'] as $hdmf)
                                          @if ($hdmf['sort_position'] == $y+1)
                                            {{ number_format((float)$hdmf['pivot']['amount'],2) }}
                                          @endif
                                        @endforeach
                                      </td>
                                    @endfor
                                  @else
                                    @for($y=1; $y<=$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                      <td></td>
                                    @endfor
                                  @endif
                                @else
                                  <td></td>
                                @endif



                                @if($hasPHIC)
                                @if(isset($payrollUser->user_deductions['PHIC']))
                                  @for($y=0; $y<$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                    <td>
                                      @foreach ($payrollUser->user_deductions['PHIC'] as $phic)
                                        @if ($phic['sort_position'] == $y+1)
                                          {{ number_format((float)$phic['pivot']['amount'],2) }}
                                        @endif
                                      @endforeach
                                    </td>
                                  @endfor
                                  @else
                                    @for($y=1; $y<=$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                      <td></td>
                                    @endfor
                                  @endif
                                @else
                                <td></td>
                              @endif
        
                                    
                                @if($hasCOOP)
                                  @if(isset($payrollUser->user_deductions['COOP']))
                                    @for($y=0; $y<$joDeductions->countBy('deduction_group')['COOP']; $y++)
                                      <td>
                                        @foreach ($payrollUser->user_deductions['COOP'] as $COOP)
                                          @if ($COOP['sort_position'] == $y+1)
                                            {{ number_format((float)$COOP['pivot']['amount'],2) }}
                                          @endif
                                        @endforeach
                                      </td>
                                    @endfor
                                    @else
                                        @for($y=1; $y<=$joDeductions->countBy('deduction_group')['COOP']; $y++)
                                          <td></td>
                                        @endfor
                                      @endif
                                    @else
                                    <td></td>
                                  @endif



                                @if($hasDISALLOWANCE)
                                  @if(isset($payrollUser->user_deductions['DISALLOWANCE']))
                                    @for($y=0; $y<$joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                                      <td>
                                        @foreach ($payrollUser->user_deductions['DISALLOWANCE'] as $disallowance)
                                          @if ($disallowance['sort_position'] == $y+1)
                                            {{ number_format((float)$disallowance['pivot']['amount'], 2) }}
                                          @endif
                                        @endforeach
                                      </td>
                                    @endfor
                                    @else
                                          @for($y=1; $y<=$joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                                            <td></td>
                                          @endfor
                                        @endif
                                      @else
                                      <td></td>
                                    @endif


                                  @if($hasOTHER)
                                    @if(isset($payrollUser->user_deductions['OTHER']))
                                        @for($y=0; $y<$joDeductions->countBy('deduction_group')['OTHER']; $y++)
                                          <td>
                                            @foreach ($payrollUser->user_deductions['OTHER'] as $otherDed)
                                              @if ($otherDed['sort_position'] == $y+1)
                                                {{ number_format((float)$otherDed['pivot']['amount'], 2) }}
                                              @endif
                                            @endforeach
                                          </td>
                                        @endfor
                                    @else
                                        @for($y=1; $y<=$joDeductions->countBy('deduction_group')['OTHER']; $y++)
                                          <td></td>
                                        @endfor
                                    @endif
                                
                                  @endif

                                  <td scope="row">
                                    <strong>{{ number_format($payrollUser->total_user_deduction, 2) }}</strong>
                                  </td>
      
                                  <td scope="row" style="border-left: 1.5px solid black; position: relative;">
                                    <div >
                                      <strong>{{ number_format(bcdiv(round($payrollUser->first_half + $payrollUser->second_half, 5), 1, 2), 2) }}</strong>
                                    </div>
                                    @if($isLessFifteen != 'all' && $isLessFifteen != 'full_month')
                                      <span style="position: absolute; display:inline; margin-left: 51px; margin-top:-2px; font-size:8px;" >{{ $counter }}</span>
                                    @endif
                                  </td>

                                  {{-- @if($isLessFifteen == false) --}}
                                  @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                                  <td scope="row" style="position: relative;">
                                    <strong> {{ number_format(bcdiv($payrollUser->first_half, 1, 2), 2) }}</strong>
                                  </td>
                                  <td scope="row" style="position: relative;">
                                    <div>
                                      <strong style="display:inline;">{{ number_format(bcdiv($payrollUser->second_half, 1, 2), 2) }}</strong>
                                    </div>
                                    @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                                      <span style="position: absolute; display:inline; margin-left: 51px; margin-top:-2px; font-size:8px;" >{{ $counter }}</span>
                                    @endif
                                  </td>
                                  @endif

                                  @php
                                    $sectionCode = $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['section_code'];

                                    if (!isset($itemNo[$sectionCode])) {
                                        // If it doesn't exist, create a new entry with an array containing the counter
                                        $itemNo[$sectionCode] = [$counter];
                                    } else {
                                        // If it exists, append the counter to the existing array
                                        $itemNo[$sectionCode][] = $counter;
                                    }
                                      $counter ++;

                                  @endphp

                                </tr>
                              @endif
                            @endif
                            


                          @endif

                        @endforeach
                    @php
                      $counter = 1;

                      // dd($itemNo)
                    @endphp
                    </tbody>
              
                    <tfoot class="fw-bold">
                      <tr>
                        <td style="border-right: 1.5px solid black; text-align:left;font-size:10px;"><strong>TOTAL NET PAY</strong></td>
                        <td colspan="3" style="font-size:8px; text-align:left;"><strong>{{ strtoupper(Helper::numberToWord(sprintf("%.2f", $payrollUserSection->total_net_pay))) }}</strong></td>
                        <td style="border-right: 1.5px solid black; background-color: #b4c1e3f1;"><strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_basic_pay),2)}}</strong></td>
    
                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                        {{-- TOTAL PERA --}}
                          @if ($hasPERA)
                            @if(isset($payrollUserSection->grand_total_allowance))
                              @for($y=0; $y<$joAllowances->countBy('allowance_group')['PERA']; $y++)
                              <td style="border-left: 1.5px solid black; background-color: #b4c1e3f1;">
                                @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                  @if($grandTotalAllowance['allowance_group'] == 'PERA')
                                    @if ($grandTotalAllowance['sort_position'] == $y+1)
                                      <strong>{{ number_format((float)$grandTotalAllowance['total'],2) }}</strong>
                                    @endif
                                  @endif
                                @endforeach
                              </td>
                              @endfor
                            @else
                              @for($y=1; $y<=$joAllowances->countBy('allowance_group')['PERA']; $y++)
                                <td style="background-color: #b4c1e3f1;"></td>
                              @endfor
                            @endif
                          @endif
                          
                          {{-- TOTAL MEDICAL --}}
                          @if ($hasMEDICAL)
                            @if(isset($payrollUserSection->grand_total_allowance))
                              @for($y=0; $y<$joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                              <td style="background-color: #b4c1e3f1;">
                                @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                  @if($grandTotalAllowance['allowance_group'] == 'MEDICAL')
                                    @if ($grandTotalAllowance['sort_position'] == $y+1)
                                      <strong>{{ number_format((float)$grandTotalAllowance['total'],2) }}</strong>
                                    @endif
                                  @endif
                                @endforeach
                              </td>
                              @endfor
                            @else
                              @for($y=1; $y<=$joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                <td style="background-color: #b4c1e3f1;"></td>
                              @endfor
                            @endif
                          @endif

                          {{-- TOTAL MEAL --}}
                          @if ($hasMEAL)
                            @if(isset($payrollUserSection->grand_total_allowance))
                              @for($y=0; $y<$joAllowances->countBy('allowance_group')['MEAL']; $y++)
                              <td style="background-color: #b4c1e3f1;">
                                @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                  @if($grandTotalAllowance['allowance_group'] == 'MEAL')
                                    @if ($grandTotalAllowance['sort_position'] == $y+1)
                                      <strong>{{ number_format((float)$grandTotalAllowance['total'],2) }}</strong>
                                    @endif
                                  @endif
                                @endforeach
                              </td>
                              @endfor
                            @else
                              @for($y=1; $y<=$joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                <td style="background-color: #b4c1e3f1;"></td>
                              @endfor
                            @endif
                          @endif

                          {{-- TOTAL CHILD --}}
                          @if ($hasCHILD)
                            @if(isset($payrollUserSection->grand_total_allowance))
                              @for($y=0; $y<$joAllowances->countBy('allowance_group')['CHILD']; $y++)
                              <td style="background-color: #b4c1e3f1;">
                                @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                  @if($grandTotalAllowance['allowance_group'] == 'CHILD')
                                    @if ($grandTotalAllowance['sort_position'] == $y+1)
                                      <strong>{{ number_format((float)$grandTotalAllowance['total'],2) }}</strong>
                                    @endif
                                  @endif
                                @endforeach
                              </td>
                              @endfor
                            @else
                              @for($y=1; $y<=$joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                <td style="background-color: #b4c1e3f1;"></td>
                              @endfor
                            @endif
                          @endif

                          <td style="background-color: #b4c1e3f1;"><strong>{{ number_format((float)$payrollUserSection->total_allowance, 2) }}</strong></td>
                          <td style="background-color: #b4c1e3f1;"><strong>{{ number_format((float)($payrollUserSection->total_allowance + $payrollUserSection->total_basic_pay), 2) }}</strong></td>
                        @endif

                      {{-- TOTAL TAX --}}
                      @if($hasTAX)

                        @if(isset($payrollUserSection->grand_total_deduction))
                          @for($y=0; $y<$joDeductions->countBy('deduction_group')['TAX']; $y++)
                          <td style="background-color: #b4c1e3f1;">
                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                              @if($grandTotalDeduction['deduction_group'] == 'TAX')
                                @if ($grandTotalDeduction['sort_position'] == $y+1)
                                <strong>{{ number_format((float)$grandTotalDeduction['total'], 2) }}</strong>
                                @endif
                              @endif
                            @endforeach
                          </td>
                          @endfor
                        @else
                          @for($y=1; $y<=$joDeductions->countBy('deduction_group')['TAX']; $y++)
                            <td style="background-color: #b4c1e3f1;"></td>
                          @endfor
                        @endif
                        @else
                        <td style="background-color: #b4c1e3f1;"></td>
                     @endif


                    {{-- TOTAL GSIS --}}
                    @if($hasGSIS)

                        @if(isset($payrollUserSection->grand_total_deduction))
                          @for($y=0; $y<$joDeductions->countBy('deduction_group')['GSIS']; $y++)
                          <td style="background-color: #b4c1e3f1;">
                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                              @if($grandTotalDeduction['deduction_group'] == 'GSIS')
                                @if ($grandTotalDeduction['sort_position'] == $y+1)
                                <strong>{{ number_format((float)$grandTotalDeduction['total'], 2) }}</strong>
                                @endif
                              @endif
                            @endforeach
                          </td>
                          @endfor
                        @else
                          @for($y=1; $y<=$joDeductions->countBy('deduction_group')['GSIS']; $y++)
                            <td style="background-color: #b4c1e3f1;"></td>
                          @endfor
                        @endif
                        @else
                        <td style="background-color: #b4c1e3f1;"></td>
                    @endif


                      {{-- TOTAL HDMF --}}

                      @if($hasHDMF)
                      @if(isset($payrollUserSection->grand_total_deduction))
                        @for($y=0; $y<$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                          <td style="background-color: #b4c1e3f1;">  
                            
                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                              @if($grandTotalDeduction['deduction_group'] == 'HDMF')
                                @if ($grandTotalDeduction['sort_position'] == $y+1)
                            <strong>{{ number_format((float)$grandTotalDeduction['total'], 2) }}</strong>

                                @endif
                              @endif
                            @endforeach
                          </td>

                          @endfor
                        @else
                          @for($y=1; $y<=$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                            <td style="background-color: #b4c1e3f1;"></td>
                          @endfor
                        @endif
                      @else
                      <td style="background-color: #b4c1e3f1;"></td>
                   @endif

    
    

                          {{-- TOTAL PHIC --}}
                      @if($hasPHIC)
                          @if(isset($payrollUserSection->grand_total_deduction))
                            @for($y=0; $y<$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                            <td style="background-color: #b4c1e3f1;">  
                              @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                                @if($grandTotalDeduction['deduction_group'] == 'PHIC')
                                  @if ($grandTotalDeduction['sort_position'] == $y+1)
                                  <strong>{{ number_format((float)$grandTotalDeduction['total'], 2) }}</strong>
                                  @endif
                                @endif
                              @endforeach
                              </td>
                            @endfor
                          @else
                            @for($y=1; $y<=$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                              <td style="background-color: #b4c1e3f1;"></td>
                            @endfor
                          @endif

                      @else
                        <td style="background-color: #b4c1e3f1;"></td>
                     @endif
    
                      {{-- TOTAL COOP LOAN --}}

                      @if($hasCOOP)
                        @if(isset($payrollUserSection->grand_total_deduction))
                          @for($y=0; $y<$joDeductions->countBy('deduction_group')['COOP']; $y++)
                          <td style="background-color: #b4c1e3f1;">
                            @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                              @if($grandTotalDeduction['deduction_group'] == 'COOP')
                                @if ($grandTotalDeduction['sort_position'] == $y+1)
                                <strong>{{ number_format((float)$grandTotalDeduction['total'], 2) }}</strong>
                                @endif
                              @endif
                            @endforeach
                            </td>
                          @endfor
                        @else
                          @for($y=1; $y<=$joDeductions->countBy('deduction_group')['COOP']; $y++)
                            <td style="background-color: #b4c1e3f1;"></td>
                          @endfor
                        @endif
                    @else
                        <td style="background-color: #b4c1e3f1;"></td>
                     @endif

                  {{-- TOTAL DISALLOWANCE --}}
                  @if($hasDISALLOWANCE)
                    @if(isset($payrollUserSection->grand_total_deduction))
                      @for($y=0; $y<$joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                      <td style="background-color: #b4c1e3f1;">
                        @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                          @if($grandTotalDeduction['deduction_group'] == 'DISALLOWANCE')
                            @if ($grandTotalDeduction['sort_position'] == $y+1)
                            <strong>{{ number_format((float)$grandTotalDeduction['total'],2) }}</strong>
                            @endif
                          @endif
                        @endforeach
                        </td>
                      @endfor
                    @else
                      @for($y=1; $y<=$joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                        <td style="background-color: #b4c1e3f1;"></td>
                      @endfor
                    @endif
                @else
                <td style="background-color: #b4c1e3f1;"></td>
               @endif
                  
              {{-- TOTAL OTHER --}}
              @if($hasOTHER)
                @if(isset($payrollUserSection->grand_total_deduction))
                    @for($y=0; $y<$joDeductions->countBy('deduction_group')['OTHER']; $y++)
                    <td style="background-color: #b4c1e3f1;">
                      @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                        @if($grandTotalDeduction['deduction_group'] == 'OTHER')
                          @if ($grandTotalDeduction['sort_position'] == $y+1)
                          <strong>{{ number_format((float)$grandTotalDeduction['total'],2) }}</strong>
                          @endif
                        @endif
                      @endforeach
                      </td>
                    @endfor
                  @else
                    @for($y=1; $y<=$joDeductions->countBy('deduction_group')['OTHER']; $y++)
                      <td style="background-color: #b4c1e3f1;"></td>
                    @endfor
                  @endif
              @else
              {{-- <td style="background-color: #b4c1e3f1;"></td> --}}
            @endif
    
            <td style="background-color: #b4c1e3f1;"><strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_deduction), 2)}}</strong></td>
            <td style="border-left: 1.5px solid black; background-color: #b4c1e3f1;"><strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_net_pay), 2)}}</strong> </td>
            
            {{-- @if($isLessFifteen == false) --}}
            @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
              <td style="background-color: #b4c1e3f1;"><strong>{{ number_format((float) sprintf('%.2f', $payrollUserSection->total_first_half), 2) }}</strong>
              </td>
              <td style="background-color: #b4c1e3f1;"><strong>{{ number_format((float) sprintf('%.2f', $payrollUserSection->total_second_half), 2) }}</strong>
              </td>
            @endif

          </tr>


      </tfoot>
  </table>
</div>


          {{-- SIGNAOTRIES --}}


          {{-- <div class="">test</div> --}}
          <div class="footer" style="page-break-inside: avoid;">
            <table class="signatory">
              <tr style="vertical-align: text-top;">
                <td style="width:20%;">
                  <p class="signatory-job">
                    <strong>A. PREPARED BY:</strong>
                    <br>
                  </p>
                </td>
                
                {{-- <td style="width:5%;">&nbsp;</td> --}}
                
                <td style="width:25%;">
                  <p class="signatory-job" style="margin-bottom: 0px;">
                    <strong>B. CERTIFIED:</strong> Service duly rendered as stated.
                  </p>

                  <table style="border: 0;">
                    <tr style="border 0;">
                      @php
                        // Count how many 'Box B [Section Chief Concerned]' signatories there are
                        $boxBCount = $signatories->where('type', 'Box B [Section Chief Concerned]')->count();
                        $processedNames = [];
                        $processedSig = [];
                      @endphp
                      {{-- @foreach ($signatories as $signatory)
                        @if($signatory->type == 'Box B [Section Chief Concerned]')
                          @if($boxBCount > 1)
                            <td style="border:0;">
                              {{ formatRanges($itemNo[$signatory->agencySection()->get()[0]->section_code]) }}
                            </td>
                          @endif
                        @endif
                      @endforeach --}}

                      @foreach ($signatories as $signatory)
                          @if(!in_array($signatory->name, $processedNames) && $signatory->type == 'Box B [Section Chief Concerned]')
                            @php
                                array_push($processedNames, $signatory->name);
                                array_push($processedSig, $signatory);
                            @endphp
                          @endif
                      @endforeach
                  

                      @foreach ($processedSig as $mySig)
                      {{-- @dd($processedSig) --}}
                          @if(count($processedNames) > 1)
                              <td style="border:0;">
                                  {{ formatRanges($itemNo[$mySig->agencySection()->get()[0]->section_code]) }}
                              </td>
                          @endif
                      @endforeach
                      @php
                        $processedNames = [];
                      @endphp
                  </tr>
                    </table>
                </td>

                {{-- <td style="width:8%;">&nbsp;</td> --}}

                <td style="width: 20%">
                  <p class="signatory-job">
                    <strong>C. CERTIFIED:</strong> Supporting documents are complete and proper, computations are correct, and ASA and cash is available amounting to...
                    <br>
                    <u>
                      <b style="margin-left:60px;">
                        &nbsp; Php &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong> {{ number_format((float)sprintf("%.2f", $payrollUserSection->total_basic_pay + $payrollUserSection->total_allowance),2) }}</strong>
                      </b>
                    </u>
                  </p>
                  {{-- <p style="margin-top: 0; margin-bottom: 0; border-bottom:1px solid">
                  </p> --}}
           
                </td>

          {{-- OBLIGATION --}}
                <td rowspan="5">
                  <table class="obligation">
                    <tr>
                      <td width="45%">
                        <strong>{{ $payrollFund->fund_obligation_description }}</strong>
                      </td>
                      <td style="text-align: right;" width="20%">
                        <strong>{{ $payrollFund->fund_uacs_code }}</strong>
                      </td>
                      <td width="10%">
                        &nbsp;
                      </td>
                      <td style="text-align: left;">
                        <strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_basic_pay),2) }}</strong>
                      </td>
                    </tr>
          

                        {{-- OBLIGATION PERA --}}
                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                          @if ($joAllowances->countBy('allowance_group')['PERA'] >= 1)
                            @foreach ($joAllowances->where('allowance_group', '=', 'PERA')->sortBy('sort_position')->all() as $peraAllowance)
                              <tr>
                                <td>
                                  <strong>{{ $peraAllowance->account_title }}</strong>
                                </td>
                                <td style="text-align: right;">
                                  {{-- @if ($payrollFund->fund_description != 2) --}}
                                  @if (strpos($payrollFund->fund_description, 'LFPS') === true)
                                    <strong>{{ $peraAllowance->uacs_code_lfps }}</strong>
                                  @elseif(strpos($payrollFund->fund_description, 'COB') === true)
                                    <strong>{{ $peraAllowance->uacs_code_cob }}</strong>
                                  @else
                                    <strong>{{ $peraAllowance->uacs_code_lfps }}</strong>
                                  @endif
                                </td>
                                <td>
                                  &nbsp;
                                </td>
                                <td style="text-align: left;">
                                  @if(isset($payrollUserSection->grand_total_allowance))
                                    @if(isset($payrollUserSection->grand_total_allowance[$peraAllowance->allowance_type]))
                                      <strong>{{ number_format((float)$payrollUserSection->grand_total_allowance[$peraAllowance->allowance_type]['total'], 2) }}</strong>
                                    @else
                                      &nbsp;
                                    @endif
                                  @else
                                    &nbsp;
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                          @endif
                        @endif

                        {{-- OBLIGATION MEDICAL --}}
                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                          @if ($joAllowances->countBy('allowance_group')['MEDICAL'] >= 1)
                            @foreach ($joAllowances->where('allowance_group', '=', 'MEDICAL')->sortBy('sort_position')->all() as $medicalAllowance)
                              <tr>
                                <td>
                                  <strong>{{ $medicalAllowance->account_title }}</strong>
                                </td>
                                <td style="text-align: right;">
                                  {{-- @if ($payrollFund->fund_description != 2) --}}
                                  @if (strpos($payrollFund->fund_description, 'LFPS') === true)
                                    <strong>{{ $medicalAllowance->uacs_code_lfps }}</strong>
                                  @elseif(strpos($payrollFund->fund_description, 'COB') === true)
                                    <strong>{{ $medicalAllowance->uacs_code_cob }}</strong>
                                  @else
                                    <strong>{{ $medicalAllowance->uacs_code_lfps }}</strong>
                                  @endif
                                </td>
                                <td>
                                  &nbsp;
                                </td>
                                <td style="text-align: left;">
                                  @if(isset($payrollUserSection->grand_total_allowance))
                                    @if(isset($payrollUserSection->grand_total_allowance[$medicalAllowance->allowance_type]))
                                      <strong>{{ number_format((float)$payrollUserSection->grand_total_allowance[$medicalAllowance->allowance_type]['total'], 2) }}</strong>
                                    @else
                                      &nbsp;
                                    @endif
                                  @else
                                    &nbsp;
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                          @endif
                        @endif

                        {{-- OBLIGATION MEAL --}}
                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                          @if ($joAllowances->countBy('allowance_group')['MEAL'] >= 1)
                            @foreach ($joAllowances->where('allowance_group', '=', 'MEAL')->sortBy('sort_position')->all() as $mealAllowance)
                              <tr>
                                <td>
                                  <strong>{{ $mealAllowance->account_title }}</strong>
                                </td>
                                <td style="text-align: right;">
                                  {{-- @if ($payrollFund->fund_description != 2) --}}
                                  @if (strpos($payrollFund->fund_description, 'LFPS') === true)
                                    <strong>{{ $mealAllowance->uacs_code_lfps }}</strong>
                                  @elseif(strpos($payrollFund->fund_description, 'COB') === true)
                                    <strong>{{ $mealAllowance->uacs_code_cob }}</strong>
                                  @else
                                    <strong>{{ $mealAllowance->uacs_code_lfps }}</strong>
                                  @endif
                                </td>
                                <td>
                                  &nbsp;
                                </td>
                                <td style="text-align: left;">
                                  @if(isset($payrollUserSection->grand_total_allowance))
                                    @if(isset($payrollUserSection->grand_total_allowance[$mealAllowance->allowance_type]))
                                      <strong>{{ number_format((float)$payrollUserSection->grand_total_allowance[$mealAllowance->allowance_type]['total'], 2) }}</strong>
                                    @else
                                      &nbsp;
                                    @endif
                                  @else
                                    &nbsp;
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                          @endif
                        @endif
                            

                        {{-- OBLIGATION CHILD --}}
                        @if (strtoupper($payrollEmploymentStatus) != 'JOB ORDER' && strtoupper($payrollEmploymentStatus) != 'CONTRACT OF SERVICE')
                          @if ($joAllowances->countBy('allowance_group')['CHILD'] >= 1)
                            @foreach ($joAllowances->where('allowance_group', '=', 'CHILD')->sortBy('sort_position')->all() as $childAllowance)
                              <tr>
                                <td>
                                  <strong>{{ $childAllowance->account_title }}</strong>
                                </td>
                                <td style="text-align: right;">
                                  {{-- @if ($payrollFund->fund_description != 2) --}}
                                  @if (strpos($payrollFund->fund_description, 'LFPS') === true)
                                    <strong>{{ $childAllowance->uacs_code_lfps }}</strong>
                                  @elseif(strpos($payrollFund->fund_description, 'COB') === true)
                                    <strong>{{ $childAllowance->uacs_code_cob }}</strong>
                                  @else
                                    <strong>{{ $childAllowance->uacs_code_lfps }}</strong>
                                  @endif
                                </td>
                                <td>
                                  &nbsp;
                                </td>
                                <td style="text-align: left;">
                                  @if(isset($payrollUserSection->grand_total_allowance))
                                    @if(isset($payrollUserSection->grand_total_allowance[$childAllowance->allowance_type]))
                                      <strong>{{ number_format((float)$payrollUserSection->grand_total_allowance[$childAllowance->allowance_type]['total'], 2) }}</strong>
                                    @else
                                      &nbsp;
                                    @endif
                                  @else
                                    &nbsp;
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                          @endif
                        @endif
      
      
                        {{-- OBLIGATION TAX --}}
                        @if($hasTAX)
                          @if ($joDeductions->countBy('deduction_group')['TAX'] >= 1)
                            @foreach ($joDeductions->where('deduction_group', '=', 'TAX')->sortBy('sort_position')->all() as $taxDeduction)
                            <tr>
                              <td>
                                <strong>{{ $taxDeduction->account_title }}</strong>
                              </td>
                              <td style="text-align: right;">
                                @if ($payrollFund->id != 2)
                                  <strong>{{ $taxDeduction->uacs_code_lfps }}</strong>
                                @else
                                  <strong>{{ $taxDeduction->uacs_code_cob }}</strong>
                                @endif
                              </td>
                              <td>
                                &nbsp;
                              </td>
                              <td style="text-align: right;">
                                @if(isset($payrollUserSection->grand_total_deduction))
                                  @if(isset($payrollUserSection->grand_total_deduction[$taxDeduction->deduction_type]))
                                    <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$taxDeduction->deduction_type]['total'], 2) }}</strong>
                                  @else
                                    &nbsp;
                                  @endif
                                @else
                                  &nbsp;
                                @endif
                              </td>
                            </tr>
                            @endforeach
                            
                          @endif
                        @endif

                    {{-- END OBLIGATION TAX --}}

                    {{-- OBLIGATION GSIS --}}
                    @if($hasGSIS)
                      @if ($joDeductions->countBy('deduction_group')['GSIS'] >= 1)
                        @foreach ($joDeductions->where('deduction_group', '=', 'GSIS')->sortBy('sort_position')->all() as $gsisDeduction)
                        <tr>
                          <td>
                            <strong>{{ $gsisDeduction->account_title }}</strong>
                          </td>
                          <td style="text-align: right;">
                            @if ($payrollFund->id != 2)
                              <strong>{{ $gsisDeduction->uacs_code_lfps }}</strong>
                            @else
                              <strong>{{ $gsisDeduction->uacs_code_cob }}</strong>
                            @endif
                          </td>
                          <td>
                            &nbsp;
                          </td>
                          <td style="text-align: right;">
                            @if(isset($payrollUserSection->grand_total_deduction))
                              @if(isset($payrollUserSection->grand_total_deduction[$gsisDeduction->deduction_type]))
                                <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$gsisDeduction->deduction_type]['total'], 2) }}</strong>
                              @else
                                &nbsp;
                              @endif
                            @else
                              &nbsp;
                            @endif
                          </td>
                        </tr>
                        @endforeach
                        
                      @endif
                    @endif

                {{-- END OBLIGATION GSIS --}}

                    {{-- OBLIGATION HDMF --}}
                    @if($hasHDMF)

                      @if ($joDeductions->countBy('deduction_group')['HDMF'] >= 1)
                        @foreach ($joDeductions->where('deduction_group', '=', 'HDMF')->sortBy('sort_position')->all() as $hdmfDeduction)
                        <tr>
                          <td>
                            <strong>{{ $hdmfDeduction->account_title }}</strong>
                          </td>
                          <td style="text-align: right;">
                            @if ($payrollFund->id != 2)
                              <strong>{{ $hdmfDeduction->uacs_code_lfps }}</strong>
                            @else
                              <strong>{{ $hdmfDeduction->uacs_code_cob }}</strong>
                            @endif
                          </td>
                          <td>
                            &nbsp;
                          </td>
                          <td style="text-align: right;">
                            @if(isset($payrollUserSection->grand_total_deduction))
                              @if(isset($payrollUserSection->grand_total_deduction[$hdmfDeduction->deduction_type]))
                                <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$hdmfDeduction->deduction_type]['total'], 2) }}</strong>
                              @else
                                &nbsp;
                              @endif
                            @else
                              &nbsp;
                            @endif
                          </td>
                        </tr>
                        @endforeach
                        
                      @endif
                    @endif

                    {{-- END OBLIGATION HDMF --}}

                    {{-- OBLIGATION PHIC --}}
                    @if($hasPHIC)

                    @if ($joDeductions->countBy('deduction_group')['PHIC'] >= 1)
                    @foreach ($joDeductions->where('deduction_group', '=', 'PHIC')->sortBy('sort_position')->all() as $phicDeduction)
                    <tr>
                      <td>
                        <strong>{{ $phicDeduction->account_title }}</strong>
                      </td>
                      <td style="text-align: right;">
                        @if ($payrollFund->id != 2)
                          <strong>{{ $phicDeduction->uacs_code_lfps }}</strong>
                        @else
                          <strong>{{ $phicDeduction->uacs_code_cob }}</strong>
                        @endif
                      </td>
                      <td>
                      
                      </td>
                      <td style="text-align: right;">
                        @if(isset($payrollUserSection->grand_total_deduction))
                          @if(isset($payrollUserSection->grand_total_deduction[$phicDeduction->deduction_type]))
                            <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$phicDeduction->deduction_type]['total'], 2) }}</strong>
                          @else
                            &nbsp;
                          @endif
                        @else
                          &nbsp;
                        @endif
                      </td>
                    </tr>
                    @endforeach

                    @endif
                    @endif
                    {{-- END OBLIGATION PHIC --}}
                          
                    {{-- OBLIGATION COOP --}}
                    @if($hasCOOP)

                    @if ($joDeductions->countBy('deduction_group')['COOP'] >= 1)
                    @foreach ($joDeductions->where('deduction_group', '=', 'COOP')->sortBy('sort_position')->all() as $coopLoanDeduction)
                    <tr>
                      <td>
                        <strong>{{ $coopLoanDeduction->account_title }}</strong>
                      </td>
                      <td style="text-align: right;">
                        @if ($payrollFund->id != 2)
                          <strong>{{ $coopLoanDeduction->uacs_code_lfps }}</strong>
                        @else
                          <strong>{{ $coopLoanDeduction->uacs_code_cob }}</strong>
                        @endif
                      </td>
                      <td>
                      
                      </td>
                      <td style="text-align: right;">
                        @if(isset($payrollUserSection->grand_total_deduction))
                          @if(isset($payrollUserSection->grand_total_deduction[$coopLoanDeduction->deduction_type]))
                            <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$coopLoanDeduction->deduction_type]['total'], 2) }}</strong>
                          @else
                            &nbsp;
                          @endif
                        @else
                          &nbsp;
                        @endif
                      </td>
                    </tr>
                    @endforeach

                    @endif
                    @endif
                    {{-- END OBLIGATION COOP --}}
      
                    {{-- OBLIGATION DISALLOWANCE --}}
                    @if($hasDISALLOWANCE)

                    @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] >= 1)
                    @foreach ($joDeductions->where('deduction_group', '=', 'DISALLOWANCE')->sortBy('sort_position')->all() as $disallowanceDeduction)
                    <tr>
                      <td>
                        <strong> {{ $disallowanceDeduction->account_title }}</strong>
                      </td>
                      <td style="text-align: right;">
                        @if ($payrollFund->id != 2)
                          <strong>{{ $disallowanceDeduction->uacs_code_lfps }}</strong>
                        @else
                          <strong>{{ $disallowanceDeduction->uacs_code_cob }}</strong>
                        @endif
                      </td>
                      <td>
                      
                      </td>
                      <td style="text-align: right;">
                        @if(isset($payrollUserSection->grand_total_deduction))
                          @if(isset($payrollUserSection->grand_total_deduction[$disallowanceDeduction->deduction_type]))
                            <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$disallowanceDeduction->deduction_type]['total'], 2) }}</strong>
                          @else
                            &nbsp;
                          @endif
                        @else
                          &nbsp;
                        @endif
                      </td>
                    </tr>
                    @endforeach
                    @endif
                    @endif
                    {{-- END OBLIGATION DISALLOWANCE --}}


                    {{-- OBLIGATION OHTER DEDS --}}
                      @forelse ($additionalDeductions as $additionalDeduction)
                          @foreach ($additionalDeductions->where('deduction_group', '=', $additionalDeduction->deduction_type)->sortBy('sort_position')->all() as $otherDeduction)
                          <tr>
                            <td>
                              <strong> {{ $otherDeduction->account_title }}</strong>
                            </td>
                            <td style="text-align: right;">
                              @if ($payrollFund->id != 2)
                                <strong>{{ $otherDeduction->uacs_code_lfps }}</strong>
                              @else
                                <strong>{{ $otherDeduction->uacs_code_cob }}</strong>
                              @endif
                            </td>
                            <td>
                            
                            </td>
                            <td style="text-align: right;">
                              @if(isset($payrollUserSection->grand_total_deduction))
                                @if(isset($payrollUserSection->grand_total_deduction[$otherDeduction->deduction_type]))
                                  <strong>{{ number_format((float)$payrollUserSection->grand_total_deduction[$otherDeduction->deduction_type]['total'], 2) }}</strong>
                                @else
                                  &nbsp;
                                @endif
                              @else
                                &nbsp;
                              @endif
                            </td>
                          </tr>
                          @endforeach
                      @endforeach
                    {{-- @endif --}}
                    {{-- END OTHER DEDS --}}


      
                    {{-- OBLIGATION CIB,LCCA --}}
                    <tr>
                      <td>
                        <strong>Cash in Bank - Local Currency, Current Account</strong>
                      </td>
                      <td style="text-align: right;">
                        <strong>111 (1-01-02-020)</strong>
                      </td>
                      <td>
                        &nbsp;
                      </td>
                      <td style="text-align: right;">
                        <strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_net_pay), 2) }}</strong>
                      </td>
                    </tr>
                    {{-- END OBLIGATION CIB,LCCA --}}


                    <tr>
                      <td>
                        <br>
                        <br>
                        <br>
                      </td>
                    </tr>


                    {{-- OBLIGATION CIB,LCCA --}}
                    <tr class="mt-3">
                      <td class="">
                        <strong>Checked by: </strong>
                      </td>
                      <td style="text-align: right; border-bottom: 1px solid black;" colspan="2">

                      </td>
                      {{-- <td>
                        &nbsp;
                      </td> --}}
                      <td style="text-align: right;">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        
                      </td>
                      <td style="text-align: center;" colspan="2">
                        <i>Finance Unit Personnel</i>
                      </td>
                      <td>
                        &nbsp;
                      </td> 
                    </tr>

                    {{-- END OBLIGATION CIB,LCCA --}}


                  </table>
                </td>

              </tr>
        
              <tr>
                <td>
                  {{-- @isset($payrollUserSection->signatories)
                    @forelse ($signatories as $signatory) --}}
                  @isset($signatories)
                    @forelse ($signatories as $signatory)
                    @if ($signatory->type == 'Box A [Preparer]')
                      <p class="signatory-name">
                        <strong>{{ $signatory->name }}</strong>
                        <hr>
                      </p>
                      <p class="signatory-position">
                        <i>{{ $signatory->position }}</i>
                      </p>
                    @endif
                  @empty
                    <p class="signatory-name">
                      <strong>N/A</strong>
                      <hr>
                    </p>
                    <p class="signatory-position">
                      <i>N/A</i>
                    </p>
                  @endforelse
              </td>
          {{-- 
              <td>
                &nbsp;
              </td> --}}

              <td>
                <table style="border: 1;">
                <tr style="">
                  @php
                        $processedNames2 = [];
                  @endphp
                  @forelse ($signatories as $signatory)
                    @if (!in_array($signatory->name, $processedNames2) && $signatory->type == 'Box B [Section Chief Concerned]')

                      <td style="">
                        <p class="signatory-name">
                          <strong>{{ $signatory->name }}</strong>
                          <hr>
                        </p>

                        <p class="signatory-position">
                          <i>{{ $signatory->position }}</i>
                        </p>
                      </td>
                      @php
                        // Add the name to the processed list
                        $processedNames2[] = $signatory->name;
                      @endphp
                    @endif
                  @empty
                  <td style="">

                    <p class="signatory-name">
                      <strong>N/A</strong>
                      <hr>
                    </p>
                    <p class="signatory-position">
                      <i>N/A</i>
                    </p>
                  </td>

                @endforelse
              </tr>
                </table>
              </td>

       
          
              {{-- <td>
                &nbsp;
              </td> --}}



              <td>

                @forelse ($signatories as $signatory)
                  @if ($signatory->type == 'Box C [Finance Unit Head]')
                    <p class="signatory-name">
                      <strong>{{ $signatory->name }}</strong>
                      <hr>
                    </p>
                    <p class="signatory-position">
                      <i>{{ $signatory->position }}</i>
                    </p>
                  @endif
                @empty
                  <p class="signatory-name">
                    <strong>N/A</strong>
                    <hr>
                  </p>
                  <p class="signatory-position">
                    <i>N/A</i>
                  </p>
                @endforelse
                  @endisset
              </td>
              </tr>

              <tr>
                <td style="padding:0px; margin-top: 0px; padding-left: 70px; vertical-align: text-top;" colspan="3" >
                  <div style="overflow:hidden;">
                  Date:___________________________
                  </div>
                </td>
              </tr>

              <tr style="vertical-align: text-top;">
                <td>
                  <p class="signatory-job">
                    <strong>D. APPROVED FOR PAYMENT</strong>
                    <br>
                    {{ strtoupper(Helper::numberToWord(sprintf("%.2f", $payrollUserSection->total_net_pay)))}}
                  </p>
                </td>
                {{-- <td>
                  &nbsp;
                </td> --}}
                <td>
                  <p class="signatory-job">
                    <strong>
                      E. CERTIFIED:
                    </strong>
                    <br>
                    <i>Each employee whose name appears on the payroll has been paid and the corresponding net amount opposite his/her name was credited to his/her LBP payroll account</i>
                    <br>
                  </p>
                </td>
                {{-- <td>
                  &nbsp;
                </td> --}}
                {{-- <td></td> --}}
                <td>
                  &nbsp;
                </td>
              </tr>

              {{-- @isset($payrollUserSection->signatories) --}}
              <tr>
                <td>
                  {{-- @forelse ($signatories as $signatory)
                    @if ($signatory->type == 'Box D [Approver]')
                      <p class="signatory-name">
                        <strong>{{ $signatory->name }}</strong>
                        <hr>
                      </p>
                      <p class="signatory-position">
                        <i>{{ $signatory->position }}</i>
                      </p>
                    @endif
                  @empty
                    <p class="signatory-name">
                      <strong>N/A</strong>
                      <hr>
                    </p>
                    <p class="signatory-position">
                      <i>N/A</i>
                    </p>
                  @endforelse --}}

            
                  @forelse ($signatories as $signatory)
                    @if ($signatory->type == 'Box D [Approver]')
                      <p class="signatory-name">
                        <strong>{{ $signatory->name }}</strong>
                        <hr>
                      </p>
                      <p class="signatory-position">
                        <i>{{ $signatory->position }}</i>
                      </p>
                    @endif
                  @empty

                    <p class="signatory-name">
                      <strong>N/A</strong>
                      <hr>
                    </p>
                    <p class="signatory-position">
                      <i>N/A</i>
                    </p>
                  @endforelse
         


                </td>
                {{-- <td> &nbsp;</td> --}}
                <td>
                  @forelse ($signatories as $signatory)
                    @if ($signatory->type == 'Box E [Certified]')
                      <p class="signatory-name">
                        <strong>{{ $signatory->name }}</strong>
                        <hr>
                      </p>
                      <p class="signatory-position">
                        <i>{{ $signatory->position }}</i>
                      </p>
                    @endif
                  @empty
                    <p class="signatory-name">
                      <strong>N/A</strong>
                      <hr>
                    </p>
                    <p class="signatory-position">
                      <i>N/A</i>
                    </p>
                  @endforelse
                  </td>
                  <td>&nbsp;</td>

                </tr>
                {{-- @endisset --}}
            </table>

          </div>
        </div>
        {{-- @endforeach --}}
      {{-- @endforeach  --}}
  </div>
    <script type="text/php">
      if ( isset($pdf) ) {

          $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
          $font = $fontMetrics->get_font("cambria", "bold");
          $size = 10;

          $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
          $x = ($pdf->get_width() - $width) / 2;
          $y = $pdf->get_height() - 20;

          $color = array(0,0,0);
          $word_space = 0.0;  //  default
          $char_space = 0.0;  //  default
          $angle = 0.0;   //  default

          $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

          // Add additional text in the left footer
          $leftText = "";
          $leftWidth = $fontMetrics->get_text_width($leftText, $font, $size);
          $pdf->page_text(20, $y, $leftText, $font, $size, $color, $word_space, $char_space, $angle);
      }
  </script>
</body>
</html>
