<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<head>
    <meta charset="utf-8">
    <title>NIAPay</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Customized Bootstrap Stylesheet -->
    {{-- <link type="text/css" href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link type="text/css" href="{{ base_path().'/public/css/bootstrap.min.css' }}" rel="stylesheet"> --}}

    <!-- Template Stylesheet -->
    {{-- <link type="text/css" href="{{ base_path().'/public/css/style.css' }}" rel="stylesheet"> --}}
    {{-- <link type="text/css" href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}
<style type="text/css">

 /* @page { size: 21.59cm 33.02cm landscape; } */
 @page { size: 22cm 36cm landscape; }

     body {
         /* background: #fb887c; */
         /* color: #fff; */
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
         margin: auto;
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
         
         /* border-left: 2px solid black;
         border-right: 2px solid black;
         border-bottom: 2px solid black; */
         border-collapse: collapse;
     }

     th, td {
         padding: 5px;
         font-size:10px;
         border: 0.5px solid black;
         /* height:15px */
     }

     th {
      font-size:8px;
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
      border:0;

    }

    table.obligation td{
      border:0;
      padding: 0;
    }

    table.signatory td{
      border:0;
      /* padding: 0; */
    }

    table.signatory{
      border:0;
      /* padding: 0; */
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
  width: 200px;
  margin-top: 0;
  margin-bottom: 0;
}
</style>

</head>

<body>
  <div class="grid-container">
      {{-- @foreach ($payrollFunds as $payrollFund) --}}
        {{-- @foreach ($payrollUserSections as $payrollUserSection) --}}
        <div class="">
          {{-- <div @if(!$loop->last) class="page-break" @endif> --}}
          <div class="main">
            <caption>
              <h2>L A B O R &nbsp; P A Y R O L L</h2>
              <p>For the period {{ $payrollDateFrom->format('F') }} {{ $payrollDateFrom->format('j') }}-{{ $payrollDateTo->format('j') }}, {{ $payrollDateFrom->format('Y') }}</p>
              <div style="margin-left: 5px; margin-bottom: 15px;">
                <p class="fw-sm" style="display: inline; float: left;">Entity Name: <strong>NATIONAL IRRIGATION ADMINISTRATION - PANGASINAN IMO / {{ $payrollUserSection->section_description }}</strong></p>
                {{-- <p class="fw-sm" style="display: inline; float: left;">We acknowledge receipt of cash shown opposite our name as full compensation for services rendered for the period covered.</p> --}}
                <p class="fw-sm" style="display: inline; float: right; margin-right:60px;">CHARGED TO: <strong>{{ $payrollFund->fund_description }}</strong></p>
              </div>
            </caption>
              <table class="payroll">
                <thead>
                  <tr>
                    {{-- <th scope="col">ID</th> --}}
                    <th scope="col" rowspan="3" style="width:15%; border-right: 1.5px solid black;">N A M E</th>
                    <th scope="col" rowspan="3" style="width:15%;">POSITION TITLE / SG</th>
                    <th scope="col" rowspan="3" style="">NUMBER OF DAYS WORKED</th>
                    <th scope="col" rowspan="3" style="">DAILY RATE</th>
                    <th scope="col" rowspan="3" style="border-right: 1.5px solid black;">BASIC PAY</th>

                    @if (strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' && strtoupper($payrollEmploymentStatus) != 'PERMANENT')
                    <th scope="col" colspan="{{ $joAllowances->count() }}" >A L L O W A N C E S</th>
                    @endif
                    <th scope="col" colspan="{{ $joDeductions->count() }}"> D E D U C T I O N S</th>
                    <th scope="col" rowspan="3" style="">Total Deductions</th>
                    <th scope="col" rowspan="3" style="border-left: 1.5px solid black;">Net Pay</th>
                    {{-- <th scope="col" rowspan="3" class="text-center align-middle">Signature</th> --}}
                  </tr>
    
                  <tr>
                    @if (strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' && strtoupper($payrollEmploymentStatus) != 'PERMANENT')
                    <th scope="col"
                      @if ($joAllowances->countBy('allowance_group')['PERA'] <= 1)
                        rowspan="2" 
                      @else
                      colspan="{{ $joAllowances->countBy('allowance_group')['PERA'] }}"
                      @endif
                      class="text-center align-middle">
                      PERA
                    </th>

                    <th scope="col"  
                      @if ($joAllowances->countBy('allowance_group')['MEDICAL'] <= 1)
                        rowspan="2" 
                      @else
                      colspan="{{ $joAllowances->countBy('allowance_group')['MEDICAL'] }}"
                      @endif
                      class="text-center align-middle">
                      MEDICAL
                    </th>

                    <th scope="col"  
                      @if ($joAllowances->countBy('allowance_group')['MEAL'] <= 1)
                        rowspan="2" 
                      @else
                      colspan="{{ $joAllowances->countBy('allowance_group')['MEAL'] }}"
                      @endif
                      class="text-center align-middle">
                      MEAL
                    </th>

                    <th scope="col"  
                      @if ($joAllowances->countBy('allowance_group')['CHILD'] <= 1)
                        rowspan="2" 
                      @else
                      colspan="{{ $joAllowances->countBy('allowance_group')['CHILD'] }}"
                      @endif
                      class="text-center align-middle">
                      CHILD
                    </th>
                    @endif

                    <th scope="col" style=""
                    @if ($joDeductions->countBy('deduction_group')['TAX'] <= 1)
                    rowspan="2"
                    @else
                    colspan="{{ $joDeductions->countBy('deduction_group')['TAX'] }}"
                    @endif
    
                    class="text-center align-middle">
                    WHT
                    </th>
    
                    <th scope="col"  style=""
                      colspan="{{ $joDeductions->countBy('deduction_group')['HDMF'] }}" 
                      class="text-center align-middle">
                      HDMF
                    </th>
                  
                    <th scope="col"  style=""
                    @if ($joDeductions->countBy('deduction_group')['PHIC'] <= 1)
                      rowspan="2" 
                    @else
                    colspan="{{ $joDeductions->countBy('deduction_group')['PHIC'] }}"
                    @endif
                      class="text-center align-middle">
                      PHIC
                    </th>
    
                    <th scope="col" style=""
                    @if ($joDeductions->countBy('deduction_group')['COOP'] <= 1)
                      rowspan="2"
                    @else
                      colspan="{{ $joDeductions->countBy('deduction_group')['COOP'] }}"
                    @endif
                      class="text-center align-middle">
                      COOP LOAN
                    </th>
    
                    <th scope="col" style=""
                    @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] <= 1)
                      rowspan="2" 
                    @else
                      colspan="{{ $joDeductions->countBy('deduction_group')['DISALLOWANCE'] }}"
                    @endif
                      class="text-center align-middle">
                      DISALLOWANCE
                    </th>
    
                    @foreach ($uniqueAdditionalDeductionGroups as $additionalDeduction)
                      <th scope="col" rowspan="2" style="">{{ $additionalDeduction->description }}</th>                    
                    @endforeach
                  </tr>
    
                  <tr>
                    @if (strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' && strtoupper($payrollEmploymentStatus) != 'PERMANENT')
                      @if ($joAllowances->countBy('allowance_group')['PERA'] > 1)
                        @foreach ($joAllowances->where('allowance_group', '=', 'PERA')->sortBy('sort_position')->all() as $peraAllowance)
                        <th scope="col" class="text-center align-middle ">{{ $peraAllowance->description }}</th>
                        @endforeach
                      @endif

                      @if ($joAllowances->countBy('allowance_group')['MEDICAL'] > 1)
                        @foreach ($joAllowances->where('allowance_group', '=', 'MEDICAL')->sortBy('sort_position')->all() as $medicalAllowance)
                        <th scope="col" class="text-center align-middle ">{{ $medicalAllowance->description }}</th>
                        @endforeach
                      @endif

                      @if ($joAllowances->countBy('allowance_group')['MEAL'] > 1)
                        @foreach ($joAllowances->where('allowance_group', '=', 'MEAL')->sortBy('sort_position')->all() as $mealAllowance)
                        <th scope="col" class="text-center align-middle ">{{ $mealAllowance->description }}</th>
                        @endforeach
                      @endif

                      @if ($joAllowances->countBy('allowance_group')['CHILD'] > 1)
                        @foreach ($joAllowances->where('allowance_group', '=', 'MEAL')->sortBy('sort_position')->all() as $childAllowance)
                        <th scope="col" class="text-center align-middle ">{{ $childAllowance->description }}</th>
                        @endforeach
                      @endif
                    @endif

                    @if ($joDeductions->countBy('deduction_group')['TAX'] > 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'TAX')->sortBy('sort_position')->all() as $taxDeduction)
                      <th scope="col" style="">{{ $taxDeduction->description }}</th>
                      @endforeach
                    @endif
    
                    @if ($joDeductions->countBy('deduction_group')['HDMF'] > 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'HDMF')->sortBy('sort_position')->all() as $hdmfDeduction)
                      <th scope="col" style="">{{ $hdmfDeduction->description }}</th>
                      @endforeach
                    @endif
                    
    
                    @if ($joDeductions->countBy('deduction_group')['PHIC'] > 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'PHIC')->sortBy('sort_position')->all() as $phicDeduction)
                      <th scope="col" style="">{{ $phicDeduction->description }}</th>
                      @endforeach
                    @endif
    
                    @if ($joDeductions->countBy('deduction_group')['COOP'] > 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'COOP')->sortBy('sort_position')->all() as $coopDeduction)
                      <th scope="col" style="">{{ $coopDeduction->description }}</th>
                      @endforeach
                    @endif
                  
                    @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] > 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'DISALLOWANCE')->sortBy('sort_position')->all() as $disallowanceDeduction)
                      <th scope="col" style="">{{ $disallowanceDeduction->description }}</th>
                      @endforeach
                    @endif
                  </tr>
                </thead>
                <tr>
                  <td colspan="4" style="font-size: 8px; height:2px; padding:0px;"></td>
                  <td style="border-right: 1.5px solid black; text-align:center; font-size: 8px; height:2px; padding:0px;">A</td>
                  @if (strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' && strtoupper($payrollEmploymentStatus) != 'PERMANENT')
                    <td colspan="{{ $joAllowances->count() }}" style="font-size: 8px; height:2px; padding:0px;"></td>
                  @endif
                  <td colspan="{{ $joDeductions->count() }}" style="font-size: 8px; height:2px; padding:0px;"></td>
                  <td style="border-right: 1.5px solid black; text-align:center; font-size: 8px; height:2px; padding:0px;">B</td>
                  <td style="border-right: 1.5px solid black; text-align:center; font-size: 8px; height:2px; padding:0px;">C = A + B</td>
                </tr>
    
                  <tbody>
                    @php
                      $counter = 1;
                    @endphp
                        @foreach ($payrollFund->users->where('employment_status', strtoupper($payrollEmploymentStatus)) as $payrollUser)
                          @if(!$payrollUser->attendances->isEmpty())

                            @if ($payrollFund->id == $payrollUser->fund_id)
                              @if ($payrollUserSection->id == $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['id'])
                                <tr @if(number_format(($payrollUser->basic_pay - $payrollUser->total_user_deduction), 2) < 0) style="background-color: rgba(245, 94, 39, 0.172)" @endif>
                                  <td scope="row" style="border-right: 1.5px solid black; font-size:10px; height:20px; text-align:left; position: relative;">
                                    <span style="position:absolute; margin-left: -20px; margin-top:3.97px;">{{ $counter }}</span>{{ $payrollUser->full_name }}
                                  </td>
                                  @php
                                    $counter ++; 
                                  @endphp
                                  <td scope="row" style="font-size: 8px; text-align:left;">{{ $payrollUser->position }}</td>
      
                                  <td scope="row">
                                    @foreach ($payrollUser->attendances as $attendance)
                                    {{-- @dd($attendance->start_date) --}}
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
                                  <td scope="row" style="border-right: 1.5px solid black;">{{ number_format((float)$payrollUser->basic_pay, 2) }}</td>
                                  {{-- <td scope="row" style="border-right: 1.5px solid black;">{{ number_format((float)$payrollUser->basic_pay, 2) }}</td> --}}
                                  

                                  @if (strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' && strtoupper($payrollEmploymentStatus) != 'PERMANENT')
                                  {{-- TOTAL PERA --}}
                                    @if(isset($payrollUserSection->grand_total_allowance))
                                      @for($y=0; $y<$joAllowances->countBy('allowance_group')['PERA']; $y++)
                                      <td>
                                        @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                          @if($grandTotalAllowance['allowance_group'] == 'PERA')
                                            @if ($grandTotalAllowance['sort_position'] == $y+1)
                                              {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                            @endif
                                          @endif
                                        @endforeach
                                      </td>
                                      @endfor
                                    @else
                                      @for($y=1; $y<=$joAllowances->countBy('allowance_group')['PERA']; $y++)
                                        <td></td>
                                      @endfor
                                    @endif
                                    
                                    {{-- TOTAL MEDICAL --}}
                                    @if(isset($payrollUserSection->grand_total_allowance))
                                      @for($y=0; $y<$joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                      <td>
                                        @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                          @if($grandTotalAllowance['allowance_group'] == 'MEDICAL')
                                            @if ($grandTotalAllowance['sort_position'] == $y+1)
                                              {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                            @endif
                                          @endif
                                        @endforeach
                                      </td>
                                      @endfor
                                    @else
                                      @for($y=1; $y<=$joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                                        <td></td>
                                      @endfor
                                    @endif
          
                                    {{-- TOTAL MEAL --}}
                                    @if(isset($payrollUserSection->grand_total_allowance))
                                      @for($y=0; $y<$joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                      <td>
                                        @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                          @if($grandTotalAllowance['allowance_group'] == 'MEAL')
                                            @if ($grandTotalAllowance['sort_position'] == $y+1)
                                              {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                            @endif
                                          @endif
                                        @endforeach
                                      </td>
                                      @endfor
                                    @else
                                      @for($y=1; $y<=$joAllowances->countBy('allowance_group')['MEAL']; $y++)
                                        <td></td>
                                      @endfor
                                    @endif
          
                                    {{-- TOTAL CHILD --}}
                                    @if(isset($payrollUserSection->grand_total_allowance))
                                      @for($y=0; $y<$joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                      <td>
                                        @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                          @if($grandTotalAllowance['allowance_group'] == 'CHILD')
                                            @if ($grandTotalAllowance['sort_position'] == $y+1)
                                              {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                            @endif
                                          @endif
                                        @endforeach
                                      </td>
                                      @endfor
                                    @else
                                      @for($y=1; $y<=$joAllowances->countBy('allowance_group')['CHILD']; $y++)
                                        <td></td>
                                      @endfor
                                    @endif
                                  @endif


                                  @if(isset($payrollUser->user_deductions['TAX']))
                                    @for($y=0; $y<$joDeductions->countBy('deduction_group')['TAX']; $y++)
                                      <td>
                                        @foreach ($payrollUser->user_deductions['TAX'] as $tax)
                                          @if ($tax['sort_position'] == $y+1)
                                            {{ number_format((float)$tax['pivot']['amount'], 2) }}
                                          @endif
                                        @endforeach
                                      </td>
                                    @endfor
                                  @else
                                    @for($y=1; $y<=$joDeductions->countBy('deduction_group')['TAX']; $y++)
                                      <td></td>
                                    @endfor
                                  @endif
      
                                  @if(isset($payrollUser->user_deductions['HDMF']))
                                    @for($y=0; $y<$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                      <td>
                                        @foreach ($payrollUser->user_deductions['HDMF'] as $hdmf)
                                          @if ($hdmf['sort_position'] == $y+1)
                                            {{ number_format((float)$hdmf['pivot']['amount'], 2) }}
                                          @endif
                                        @endforeach
                                      </td>
                                    @endfor
                                  @else
                                      @for($y=1; $y<=$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                                        <td></td>
                                      @endfor
                                    @endif
      
                                    @if(isset($payrollUser->user_deductions['PHIC']))
                                      @for($y=0; $y<$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                        <td>
                                          @foreach ($payrollUser->user_deductions['PHIC'] as $phic)
                                            @if ($phic['sort_position'] == $y+1)
                                              {{ number_format((float)$phic['pivot']['amount'], 2) }}
                                            @endif
                                          @endforeach
                                        </td>
                                      @endfor
                                      @else
                                        @for($y=1; $y<=$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                                          <td></td>
                                        @endfor
                                      @endif
      
                                  
                                      @if(isset($payrollUser->user_deductions['COOP']))
                                      @for($y=0; $y<$joDeductions->countBy('deduction_group')['COOP']; $y++)
                                        <td>
                                          @foreach ($payrollUser->user_deductions['COOP'] as $COOP)
                                            @if ($COOP['sort_position'] == $y+1)
                                              {{ number_format((float)$COOP['pivot']['amount'], 2) }}
                                            @endif
                                          @endforeach
                                        </td>
                                      @endfor
                                      @else
                                        @for($y=1; $y<=$joDeductions->countBy('deduction_group')['COOP']; $y++)
                                          <td></td>
                                        @endfor
                                      @endif
      
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
                                      
                                    @foreach ($uniqueAdditionalDeductionGroups as $additionalDeduction)
                
                                      @if(isset($additionalDeduction->deduction_group))
                                          @if(isset($payrollUser->user_deductions[$additionalDeduction->deduction_group]))
                                            @foreach ($payrollUser->user_deductions[$additionalDeduction->deduction_group] as $genDeduction)
                                              <td> {{ number_format($genDeduction->pivot->amount, 2) }} </td>
                                            @endforeach
                                          @else
                                            <td></td>
                                          @endif
      
                                      @endif
                                    @endforeach
      
                                  <td scope="row">
                                    {{ number_format($payrollUser->total_user_deduction, 2) }}
                                  </td>
      
                                  <td scope="row" style="border-left: 1.5px solid black;">
                                    {{ number_format(bcdiv($payrollUser->basic_pay - $payrollUser->total_user_deduction, 1, 2), 2) }}
                                  </td>
                                  
                                </tr>
                              @endif
                            @endif
                            
                          @endif

                        @endforeach
                    @php
                      $counter = 1;
                    @endphp
                    </tbody>
              
                    <tfoot class="fw-bold">
                      <tr>
                        <td style="border-right: 1.5px solid black; text-align:left;"><strong>TOTAL NET PAY</strong></td>
                        <td colspan="3" style="font-size:9px; text-align:left;"><strong>{{ strtoupper(Helper::numberToWord(sprintf("%.2f", $payrollUserSection->total_net_pay)))}}</strong></td>
                        <td style="border-right: 1.5px solid black; background-color: #b4e3b9f1;"><strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_basic_pay),2)}}</strong></td>
    
                        @if (strtoupper($payrollEmploymentStatus) != 'COTERMINOUS' && strtoupper($payrollEmploymentStatus) != 'PERMANENT')
                        {{-- TOTAL PERA --}}
                          @if(isset($payrollUserSection->grand_total_allowance))
                            @for($y=0; $y<$joAllowances->countBy('allowance_group')['PERA']; $y++)
                            <td>
                              @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                @if($grandTotalAllowance['allowance_group'] == 'PERA')
                                  @if ($grandTotalAllowance['sort_position'] == $y+1)
                                    {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                  @endif
                                @endif
                              @endforeach
                            </td>
                            @endfor
                          @else
                            @for($y=1; $y<=$joAllowances->countBy('allowance_group')['PERA']; $y++)
                              <td></td>
                            @endfor
                          @endif
                          
                          {{-- TOTAL MEDICAL --}}
                          @if(isset($payrollUserSection->grand_total_allowance))
                            @for($y=0; $y<$joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                            <td>
                              @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                @if($grandTotalAllowance['allowance_group'] == 'MEDICAL')
                                  @if ($grandTotalAllowance['sort_position'] == $y+1)
                                    {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                  @endif
                                @endif
                              @endforeach
                            </td>
                            @endfor
                          @else
                            @for($y=1; $y<=$joAllowances->countBy('allowance_group')['MEDICAL']; $y++)
                              <td></td>
                            @endfor
                          @endif

                          {{-- TOTAL MEAL --}}
                          @if(isset($payrollUserSection->grand_total_allowance))
                            @for($y=0; $y<$joAllowances->countBy('allowance_group')['MEAL']; $y++)
                            <td>
                              @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                @if($grandTotalAllowance['allowance_group'] == 'MEAL')
                                  @if ($grandTotalAllowance['sort_position'] == $y+1)
                                    {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                  @endif
                                @endif
                              @endforeach
                            </td>
                            @endfor
                          @else
                            @for($y=1; $y<=$joAllowances->countBy('allowance_group')['MEAL']; $y++)
                              <td></td>
                            @endfor
                          @endif

                          {{-- TOTAL CHILD --}}
                          @if(isset($payrollUserSection->grand_total_allowance))
                            @for($y=0; $y<$joAllowances->countBy('allowance_group')['CHILD']; $y++)
                            <td>
                              @foreach ($payrollUserSection->grand_total_allowance as $grandTotalAllowance)
                                @if($grandTotalAllowance['allowance_group'] == 'CHILD')
                                  @if ($grandTotalAllowance['sort_position'] == $y+1)
                                    {{ number_format((float)$grandTotalAllowance['total'],2) }}
                                  @endif
                                @endif
                              @endforeach
                            </td>
                            @endfor
                          @else
                            @for($y=1; $y<=$joAllowances->countBy('allowance_group')['CHILD']; $y++)
                              <td></td>
                            @endfor
                          @endif
                        @endif

                        {{-- TOTAL TAX --}}
                        @if(isset($payrollUserSection->grand_total_deduction))
                          @for($y=0; $y<$joDeductions->countBy('deduction_group')['TAX']; $y++)
                          <td style="background-color: #b4e3b9f1;">
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
                            <td style="background-color: #b4e3b9f1;"></td>
                          @endfor
                        @endif
    
                      {{-- TOTAL HDMF --}}
                      @if(isset($payrollUserSection->grand_total_deduction))
                        @for($y=0; $y<$joDeductions->countBy('deduction_group')['HDMF']; $y++)
                        <td style="background-color: #b4e3b9f1;">  
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
                          <td style="background-color: #b4e3b9f1;"></td>
                        @endfor
                      @endif
    
    
                      {{-- TOTAL PHIC --}}
                      @if(isset($payrollUserSection->grand_total_deduction))
                        @for($y=0; $y<$joDeductions->countBy('deduction_group')['PHIC']; $y++)
                        <td style="background-color: #b4e3b9f1;">  
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
                          <td style="background-color: #b4e3b9f1;"></td>
                        @endfor
                      @endif
                      
    
                      {{-- TOTAL COOP LOAN --}}
                      @if(isset($payrollUserSection->grand_total_deduction))
                        @for($y=0; $y<$joDeductions->countBy('deduction_group')['COOP']; $y++)
                        <td style="background-color: #b4e3b9f1;">  
                          @foreach ($payrollUserSection->grand_total_deduction as $grandTotalDeduction)
                            @if($grandTotalDeduction['deduction_group'] == 'COOP')
                              @if ($grandTotalDeduction['sort_position'] == $y+1)
                              <strong>{{ number_format((float)$grandTotalDeduction['total'],2) }}</strong>
                              @endif
                            @endif
                          @endforeach
                          </td>
                        @endfor
                      @else
                        @for($y=1; $y<=$joDeductions->countBy('deduction_group')['COOP']; $y++)
                          <td style="background-color: #b4e3b9f1;"></td>
                        @endfor
                      @endif
    
    
                    {{-- TOTAL DISALLOWANCE --}}
                    @if(isset($payrollUserSection->grand_total_deduction))
                      @for($y=0; $y<$joDeductions->countBy('deduction_group')['DISALLOWANCE']; $y++)
                      <td style="background-color: #b4e3b9f1;">  
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
                        <td style="background-color: #b4e3b9f1;"></td>
                      @endfor
                    @endif
    

                    {{-- TOTAL OTHER DEDUCTIONS --}}
                    @if(isset($payrollUserSection->grand_total_deduction))
                      @foreach ($payrollUserSection->grand_total_deduction->sortBy('sort_position') as $grandTotalDeduction)
    
                      {{-- <td>{{ $grandTotalDeduction }}</td> --}}
                        @if($grandTotalDeduction['deduction_group'] != 'DISALLOWANCE' 
                                && $grandTotalDeduction['deduction_group'] != 'COOP' 
                                && $grandTotalDeduction['deduction_group'] != 'PHIC' 
                                && $grandTotalDeduction['deduction_group'] != 'HDMF' 
                                && $grandTotalDeduction['deduction_group'] != 'TAX'
                                && $grandTotalDeduction['deduction_group'] != 'GSIS')
    
                          <td style="background-color: #b4e3b9f1;">
                            <strong>
                              {{ number_format((float)$grandTotalDeduction['total'], 2) }}
                            </strong>
                          </td>
                        @else
                          @if ($loop->first)
                            @if ($grandTotalDeduction['deduction_group'] == 'DISALLOWANCE' 
                                || $grandTotalDeduction['deduction_group'] == 'COOP' 
                                || $grandTotalDeduction['deduction_group'] == 'PHIC' 
                                || $grandTotalDeduction['deduction_group'] == 'HDMF' 
                                || $grandTotalDeduction['deduction_group'] == 'TAX'
                                || $grandTotalDeduction['deduction_group'] == 'GSIS')
                              @if ((int)$joDeductions->countBy('deduction_group')->count() > 5)
                                <td style="background-color: #b4e3b9f1;"></td>
                              @endif    
                            @endif
                          @endif
                        @endif
                      @endforeach
                    @else
                      @if ((int)$joDeductions->countBy('deduction_group')->count() > 5)
                        <td style="background-color: #b4e3b9f1;"></td>
                      @endif
                  @endif
    
                  <td style="background-color: #b4e3b9f1;"><strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_deduction), 2)}}</strong></td>
                  <td style="border-left: 1.5px solid black; background-color: #b4e3b9f1;"><strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_net_pay), 2)}}</strong></td>
                  
                </tr>
                    </tfoot>
              
                  </table>
          </div>


          {{-- SIGNAOTRIES --}}


          {{-- <div class="">test</div> --}}
          {{-- OBLIGATION --}}
          <div class="footer" style="page-break-inside: avoid;">
            <table class="signatory">
              <tr style="vertical-align: text-top;">
                <td style="width:20%;">
                  <p class="signatory-job">
                    <strong>A. PREPARED BY:</strong>
                    <br>
                  </p>
                </td>
                
                <td style="width:8%;">&nbsp;</td>
                
                <td style="width:25%;">
                  <p class="signatory-job">
                    <strong>B. CERTIFIED:</strong> Service duly rendered as stated.
                    <br>
                  </p>
                </td>

                <td style="width:10%;">&nbsp;</td>

                <td >
                  <p class="signatory-job">
                    <strong>C. CERTIFIED:</strong> Supporting documents complete and proper, and cash available in the amount of
                    <br>
                    <u>
                      <b style="margin-left:60px;">
                        &nbsp; Php &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong> {{ number_format((float)sprintf("%.2f", $payrollUserSection->total_basic_pay),2) }}</strong>
                      </b>
                    </u>
                  </p>
                  {{-- <p style="margin-top: 0; margin-bottom: 0; border-bottom:1px solid">
                  </p> --}}
           
                </td>
              </tr>
        
              <tr>
                <td>
                  @isset($payrollUserSection->signatories)
                  @forelse ($payrollUserSection->signatories as $signatory)
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
              <td>
                &nbsp;
              </td>

              <td>
                @forelse ($payrollUserSection->signatories as $signatory)
                  @if ($signatory->type == 'Box B [Section Chief Concerned]')
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
              <td>
                &nbsp;
              </td>
              <td>
                @forelse ($payrollUserSection->signatories as $signatory)
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

              <tr style="vertical-align: text-top;">
                <td>
                  <p class="signatory-job">
                    <strong>D. APPROVED PAYMENT</strong>
                    <br>
                    {{ strtoupper(Helper::numberToWord(sprintf("%.2f", $payrollUserSection->total_net_pay)))}}
                  </p>
                </td>
                <td></td>
                <td>
                  <p class="signatory-job">
                    <strong>
                      E. CERTIFIED:
                    </strong>
                    <br>
                    <i>Each employee whose name appears on the payroll has been paid the amount as credited the net amount indicated opposite his/her name to his/her LBP payroll account</i>
                    <br>
                  </p>
                </td>
                <td></td>
                {{-- <td></td> --}}
                <td rowspan="2">

                  <table class="obligation">
                    <tr>
                      <td>
                        <strong>LABOR & WAGES</strong>
                      </td>
                      <td style="text-align: right;">
                        <strong>969-4(5-02-99-990 D)</strong>
                      </td>
                      <td style="text-align: right;">
                        <strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_basic_pay),2) }}</strong>
                      </td>
                    </tr>
      
                        {{-- OBLIGATION TAX --}}
                        @if ($joDeductions->countBy('deduction_group')['TAX'] >= 1)
                          @foreach ($joDeductions->where('deduction_group', '=', 'TAX')->sortBy('sort_position')->all() as $taxDeduction)
                          <tr>
                            <td>
                              <strong>{{ $taxDeduction->description }}</strong>
                            </td>
                            <td style="text-align: right;">
                              @if ($payrollFund->id != 2)
                                <strong>{{ $taxDeduction->uacs_code_lfps }}</strong>
                              @else
                                <strong>{{ $taxDeduction->uacs_code_cob }}</strong>
                              @endif
                            </td>
                            <td >
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
                    {{-- END OBLIGATION TAX --}}

                    {{-- OBLIGATION HDMF --}}
                    @if ($joDeductions->countBy('deduction_group')['HDMF'] >= 1)
                      @foreach ($joDeductions->where('deduction_group', '=', 'HDMF')->sortBy('sort_position')->all() as $hdmfDeduction)
                      <tr>
                        <td>
                          <strong>{{ str_replace('_', ' ', $hdmfDeduction->deduction_group) }} {{ $hdmfDeduction->description }}</strong>
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
                    {{-- END OBLIGATION HDMF --}}

                    {{-- OBLIGATION PHIC --}}
                    @if ($joDeductions->countBy('deduction_group')['PHIC'] >= 1)
                    @foreach ($joDeductions->where('deduction_group', '=', 'PHIC')->sortBy('sort_position')->all() as $phicDeduction)
                    <tr>
                      <td>
                        <strong>{{ str_replace('_', ' ', $phicDeduction->deduction_group) }} {{ $phicDeduction->description }}</strong>
                      </td>
                      <td style="text-align: right;">
                        @if ($payrollFund->id != 2)
                          <strong>{{ $phicDeduction->uacs_code_lfps }}</strong>
                        @else
                          <strong>{{ $phicDeduction->uacs_code_cob }}</strong>
                        @endif
                      </td>
                      <td>
                        &nbsp;
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
                    {{-- END OBLIGATION PHIC --}}
                          
                          
                    {{-- OBLIGATION COOP --}}
                    @if ($joDeductions->countBy('deduction_group')['COOP'] >= 1)
                    @foreach ($joDeductions->where('deduction_group', '=', 'COOP')->sortBy('sort_position')->all() as $coopLoanDeduction)
                    <tr>
                      <td>
                        <strong>{{ str_replace('_', ' ', $coopLoanDeduction->deduction_group) }} {{ $coopLoanDeduction->description }}</strong>
                      </td>
                      <td style="text-align: right;">
                        @if ($payrollFund->id != 2)
                          <strong>{{ $coopLoanDeduction->uacs_code_lfps }}</strong>
                        @else
                          <strong>{{ $coopLoanDeduction->uacs_code_cob }}</strong>
                        @endif
                      </td>
                      <td>
                        &nbsp;
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
                    {{-- END OBLIGATION COOP --}}
      
                    {{-- OBLIGATION DISALLOWANCE --}}
                    @if ($joDeductions->countBy('deduction_group')['DISALLOWANCE'] >= 1)
                    @foreach ($joDeductions->where('deduction_group', '=', 'DISALLOWANCE')->sortBy('sort_position')->all() as $disallowanceDeduction)
                    <tr>
                      <td>
                        <strong>{{ str_replace('_', ' ', $disallowanceDeduction->deduction_group) }} {{ $disallowanceDeduction->description }}</strong>
                      </td>
                      <td style="text-align: right;">
                        @if ($payrollFund->id != 2)
                          <strong>{{ $disallowanceDeduction->uacs_code_lfps }}</strong>
                        @else
                          <strong>{{ $disallowanceDeduction->uacs_code_cob }}</strong>
                        @endif
                      </td>
                      <td>
                        &nbsp;
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
                    {{-- END OBLIGATION DISALLOWANCE --}}
      
                    {{-- OBLIGATION CIB,LCCA --}}
                    <tr>
                      <td>
                        <strong>CIB,LCCA</strong>
                      </td>
                      <td style="text-align: right;">
                        <strong>111(1-01-02-020)</strong>
                      </td>
                      <td>
                        &nbsp;
                      </td>
                      <td style="text-align: right;">
                        <strong>{{ number_format((float)sprintf("%.2f", $payrollUserSection->total_net_pay), 2) }}</strong>
                      </td>
                    </tr>
                    {{-- END OBLIGATION CIB,LCCA --}}
      
                  </table>
                </td>
              </tr>

              @isset($payrollUserSection->signatories)
                              <tr>
                <td>
                  @forelse ($payrollUserSection->signatories as $signatory)
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
                <td></td>
                <td>
                  @forelse ($payrollUserSection->signatories as $signatory)
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
                <td></td>

              </tr>
              @endisset



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
          $size = 6;

          $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
          $x = ($pdf->get_width() - $width) / 2;
          $y = $pdf->get_height() - 35;

          $color = array(0,0,0);
          $word_space = 0.0;  //  default
          $char_space = 0.0;  //  default
          $angle = 0.0;   //  default

          $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
      }
  </script></body></html>
