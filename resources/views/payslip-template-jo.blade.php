<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NIAPay - Generated Payslip</title>

<style type="text/css">


 @page { size: 33.02cm 21.59cm portrait; }
     body {
         margin:-15px;
         font-family: Cambria,Georgia,serif; 
     }

     .calibri{
         font-family: "Calibri", sans-serif;
     }
     table {
         margin: 0px;
         table-layout: fixed;
         width: 100%;
     }

     table.payroll td{
      text-align: right;
     } 

     table, th, td {
         border-collapse: collapse;
     }

     th, td {
         padding: 5px;
         font-size:10px;
         border: 0.5px solid black;
     }

     th {
      font-size:10px;
      font-weight: bolder;
      word-wrap: break-word;
     }

     .page-break {
        page-break-after: always;
    }

    .main{
      padding: auto;
    }

    .deduction-table{
      border:0;

    }

    table.deduction-table td{
      border:0;
      padding: 0;
      vertical-align: top;
    }

    table.gross-table td{
      border:0;
      padding: 0;
      vertical-align: top;
    }

    table.netpay-table td{
      border:0;
      padding: 0;
    }

    table.signatory-table td{
      width: 100%;
      border:0;
      padding: 0;
      vertical-align: top;
    }

    table.total-table td{
      width: 100%;
      border:0;
      padding: 0;
      vertical-align: top;
    }

    .gross-table-header td{
      border: 0.5px solid black !important;
      padding: 5px !important;
      vertical-align: center !important;
      text-align: center !important;
      font-weight: bold;
    }

    .gross-table-body td{
      vertical-align: center !important;
      text-align: center !important;
    }

    
    table.payslip-details td{
      vertical-align: top;
    }

  hr{
    border: 0.2px solid ;
    width: 200px;
    margin-top: 0;
    margin-bottom: 0;
  }

  .header-container{
    position: relative;
  }

  .header-img{
    position: absolute;
    margin-top: -30px;
    margin-left: -15px;
    z-index: -999;
  }

  .footer-container{
    position: relative;
    width: 100%;
  }

  .footer-img-old{
    position: absolute;
    margin-left: 630px;
    margin-top: 20px;
  }

  .footer-img{
    position: absolute;
    margin-left: 0px;
    margin-top: -60px;
    z-index: -999;
  }

</style>

<body>

  <div class="main">
    <div class="header-container">
      {{-- <img class="header-img" src="{{ public_path('img/test-header.jpg') }}" width="600.315px" alt="nia header"> --}}
      <img class="header-img" src="{{ public_path('img/test-header-test.jpg') }}" width="450.315px" alt="nia header">
    </div>
    <table class="payslip-details" style="margin-top: 57px; z-index:999;" width="100%">
      <thead>
        <tr>
          <td style="width: 15%;">Employee Name: </td>
          <td colspan="3">{{ $employee[0]->name }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Position Title / SG / JG: </td>
          <td>{{ $employee[0]->position_title }} / {{ $employee[0]->sg_jg }}</td>
          <td style="width: 15%;">Employment Status: </td>
          <td>{{ $employee[0]->employment_status }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Office / Section / Unit: </td>
          <td colspan="3">PANGASINAN IRRIGATION MANAGEMENT OFFICE</td>
        </tr>
        <tr>
          <td colspan="4">
            <h1 style="text-align: center; margin:0px;">P A Y S L I P</h1>
          </td>
        </tr>
      </thead>
    <tbody>
        <tr>
          <td colspan="3" width="70%">
            <table class="gross-table" style="margin-bottom: 15px;">
              <thead>
                <tr>
                  <td colspan="4" style="vertical-align: center">
                    <h3 style="text-align: center;">G R O S S</h3>
                  </td>
                </tr>
                <tr class="gross-table-header">
                  <td>Period Covered</td>
                  <td>No. of Days Worked</td>
                  <td>Daily Rate</td>
                  <td>Basic Pay</td>
                </tr>
                @foreach ($employee->sortBy('period_covered_from') as $employeeData)
                  <tr class="gross-table-body">
                    <td style="text-align: left !important; padding-left: 10px;">{{ \Carbon\Carbon::parse($employeeData->period_covered_from)->format('F') }} {{ \Carbon\Carbon::parse($employeeData->period_covered_from)->format('j') }}-{{ \Carbon\Carbon::parse($employeeData->period_covered_to)->format('j') }}, {{ \Carbon\Carbon::parse($employeeData->period_covered_to)->year }}</td>
                    <td>
                        {{ (number_format((float)$employeeData->days_rendered, 3)) }}
                    </td>
                    <td>
                        {{ number_format(bcdiv($employeeData->daily_monthly_rate, 1, 2), 2) }}
                    </td>
                    <td>
                        {{ number_format(bcdiv($employeeData->basic_pay, 1, 2), 2) }}
                    </td>
                  </tr>

                @endforeach


                @php
                $allowanceTotals = array();
                $overAllAllowanceTotal = 0.00;
  
                foreach ($employee as $employeeData){
              
                  if(isset($employeeData->user_allowances['PERA'])){
                    foreach ($joAllowances->where('allowance_group', 'PERA')->sortBy('sort_position') as $joAllowance){
                      if (!isset($allowanceTotals['PERA'][$joAllowance->description])) {
                            $allowanceTotals['PERA'][$joAllowance->description] = 0.00;
                        }
                      foreach ($employeeData->user_allowances['PERA'] as $allowance){
                        if ($allowance->npiad_sort_position == $joAllowance->sort_position){

                          $allowanceTotals['PERA'][$allowance->npiad_description] += $allowance->npiad_amount;
                          $overAllAllowanceTotal += $allowance->npiad_amount;
                        }
                      }
                    }
                  }
  
                  if(isset($employeeData->user_allowances['MEDICAL'])){
                    foreach ($joAllowances->where('allowance_group', 'MEDICAL')->sortBy('sort_position') as $joAllowance){
                      if (!isset($allowanceTotals['MEDICAL'][strpos($joAllowance->description, 'MEDICAL') !== false ? $joAllowance->description : 'MEDICAL' . ' ' . $joAllowance->description])) {
                            $allowanceTotals['MEDICAL'][strpos($joAllowance->description, 'MEDICAL') !== false ? $joAllowance->description : 'MEDICAL' . ' ' . $joAllowance->description] = 0.00;
                        }
                      foreach ($employeeData->user_allowances['MEDICAL'] as $allowance){
                        if ($allowance->npiad_sort_position == $joAllowance->sort_position){
                          $allowanceTotals['MEDICAL'][strpos($allowance->npiad_description, 'MEDICAL') !== false ? $allowance->npiad_description : 'MEDICAL' . ' ' . $allowance->npiad_description] += $allowance->npiad_amount;
                          $overAllAllowanceTotal += $allowance->npiad_amount;
                        }
                      }
                    }
                  }
                  
                  if(isset($employeeData->user_allowances['MEAL'])){
                    foreach ($joAllowances->where('allowance_group', 'MEAL')->sortBy('sort_position') as $joAllowance){
  
                      if (!isset($allowanceTotals['MEAL'][strpos($joAllowance->description, 'MEAL') !== false ? $joAllowance->description : 'MEAL' . ' ' . $joAllowance->description])) {
                            $allowanceTotals['MEAL'][strpos($joAllowance->description, 'MEAL') !== false ? $joAllowance->description : 'MEAL' . ' ' . $joAllowance->description] = 0.00;
                        }
  
                      foreach ($employeeData->user_allowances['MEAL'] as $allowance){
                        if ($allowance->npiad_sort_position == $joAllowance->sort_position){
                          // Initialize the array element if it doesn't exist
                          $allowanceTotals['MEAL'][strpos($allowance->npiad_description, 'MEAL') !== false ? $allowance->npiad_description : 'MEAL' . ' ' . $allowance->npiad_description] += $allowance->npiad_amount;
                          $overAllAllowanceTotal += $allowance->npiad_amount;
  
                        }
  
                      }
                    }
                  }
  
                  if(isset($employeeData->user_allowances['CHILDREN'])){
                    foreach ($joAllowances->where('allowance_group', 'CHILDREN')->sortBy('sort_position') as $joAllowance){
                      if (!isset($allowanceTotals['CHILDREN'][$joAllowance->description])) {
                            $allowanceTotals['CHILDREN'][$joAllowance->description] = 0.00;
                        }
                      foreach ($employeeData->user_allowances['CHILDREN'] as $allowance){
                        if ($allowance->npiad_sort_position == $joAllowance->sort_position){
                          $allowanceTotals['CHILDREN'][$allowance->npiad_description] += $allowance->npiad_amount;
                          $overAllAllowanceTotal += $allowance->npiad_amount;
  
                        }
                      }
                    }
                  }
                }
                @endphp



                <tr style="vertical-align: center; text-align: center;">
                  <td height="25px"></td>
                  <td></td>
                  <td></td>
                  <td>-</td>
                </tr>
                <tr>
                  <td colspan="4" style="font-weight: bold">ALLOWANCES</td>
                </tr>
                    {{-- PERA --}}
                    @if(isset($allowanceTotals['PERA']))
                        @foreach ($allowanceTotals['PERA'] as $allowDescription => $joAllowance)
                          <tr>
                              {{-- DED DESC --}}
                              <td colspan="3">{{ $allowDescription }}</td>
                              <td style="vertical-align: center; text-align: center;"> {{ number_format(bcdiv($joAllowance, 1, 2), 2) }}</td>
                            

                          </tr>    
                        @endforeach
                    @else
                    <tr>
                      <td colspan="3">PERA</td>
                      <td style="vertical-align: center; text-align: center;">-</td>
                    </tr>
                    @endif

                  {{-- MEDICAL --}}
                  @if(isset($allowanceTotals['MEDICAL']))
                    @foreach ($allowanceTotals['MEDICAL'] as $allowDescription => $joAllowance)
                      <tr>
                        {{-- DED DESC --}}
                          <td colspan="3">{{ $allowDescription }}</td>
                          <td style="vertical-align: center; text-align: center;"> {{ number_format(bcdiv($joAllowance, 1, 2), 2) }}</td>
                      </tr>    
                    @endforeach
                    @else
                    <tr>
                    <td colspan="3">MEDICAL</td>
                    <td style="vertical-align: center; text-align: center;">-</td>
                    </tr>
                    @endif

                  {{-- MEAL --}}
                  @if(isset($allowanceTotals['MEAL']))
                    @foreach ($allowanceTotals['MEAL'] as $allowDescription => $joAllowance)
                      <tr>
                        {{-- DED DESC --}}
                          <td colspan="3">{{ $allowDescription }}</td>
                          <td style="vertical-align: center; text-align: center;"> {{ number_format(bcdiv($joAllowance, 1, 2), 2) }}</td>
                      </tr>    
                    @endforeach
                    @else
                    <tr>
                    <td colspan="3">MEAL</td>
                    <td style="vertical-align: center; text-align: center;">-</td>
                    </tr>
                    @endif

                  {{-- CHILDREN --}}
                  @if(isset($allowanceTotals['CHILDREN']))
                    @foreach ($allowanceTotals['CHILDREN'] as $allowDescription => $joAllowance)
                      <tr>
                        {{-- DED DESC --}}
                          <td colspan="3">{{ $allowDescription }}</td>
                          <td style="vertical-align: center; text-align: center;"> {{ number_format(bcdiv($joAllowance, 1, 2), 2) }}</td>
                      </tr>    
                    @endforeach
                    @else
                    <tr>
                    <td colspan="3">CHILDREN</td>
                    <td style="vertical-align: center; text-align: center;">-</td>
                    </tr>
                    @endif

                <tr>
                  <td colspan="3"></td>
                  <td style="border-bottom: 0.5px solid #000; border-top: 0.5px solid #000; vertical-align: center; text-align: center;"><b>
                    {{ number_format(bcdiv(number_format($overAllAllowanceTotal, 6, ".", ""),1 ,2), 2) }}
                    
                  </b></td>
                </tr>


              </thead>
            </table>
          </td>
          <td colspan="1" width="30%">
              <table class="deduction-table">
                <thead>
                  <tr>
                    <td colspan="2" style="vertical-align: center">
                      <h3 style="text-align: center;">D E D U C T I O N S</h3>
                    </td>
                  </tr>
                </thead>
                <tbody>

            @php
              $deductionTotals = array();
              $overAllDeductionTotal = 0.00;
              foreach ($employee as $employeeData){
                if(isset($employeeData->user_deductions['TAX'])){
                  foreach ($joDeductions->where('deduction_group', 'TAX')->sortBy('sort_position') as $joDeduction){
                    if (!isset($deductionTotals['TAX'][$joDeduction->description])) {
                          $deductionTotals['TAX'][$joDeduction->description] = 0.00;
                      }
                    foreach ($employeeData->user_deductions['TAX'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        $deductionTotals['TAX'][$deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;
                      }
                    }
                  }
                }

                if(isset($employeeData->user_deductions['GSIS'])){
                  foreach ($joDeductions->where('deduction_group', 'GSIS')->sortBy('sort_position') as $joDeduction){
                    if (!isset($deductionTotals['GSIS'][strpos($joDeduction->description, 'GSIS') !== false ? $joDeduction->description : 'GSIS' . ' ' . $joDeduction->description])) {
                          $deductionTotals['GSIS'][strpos($joDeduction->description, 'GSIS') !== false ? $joDeduction->description : 'GSIS' . ' ' . $joDeduction->description] = 0.00;
                      }
                    foreach ($employeeData->user_deductions['GSIS'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        $deductionTotals['GSIS'][strpos($deduction->npiad_description, 'GSIS') !== false ? $deduction->npiad_description : 'GSIS' . ' ' . $deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;
                      }
                    }
                  }
                }
                
                if(isset($employeeData->user_deductions['HDMF'])){
                  foreach ($joDeductions->where('deduction_group', 'HDMF')->sortBy('sort_position') as $joDeduction){

                    if (!isset($deductionTotals['HDMF'][strpos($joDeduction->description, 'HDMF') !== false ? $joDeduction->description : 'HDMF' . ' ' . $joDeduction->description])) {
                          $deductionTotals['HDMF'][strpos($joDeduction->description, 'HDMF') !== false ? $joDeduction->description : 'HDMF' . ' ' . $joDeduction->description] = 0.00;
                      }

                    foreach ($employeeData->user_deductions['HDMF'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        // Initialize the array element if it doesn't exist
                        $deductionTotals['HDMF'][strpos($deduction->npiad_description, 'HDMF') !== false ? $deduction->npiad_description : 'HDMF' . ' ' . $deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;

                      }

                    }
                  }
                }

                if(isset($employeeData->user_deductions['PHIC'])){
                  foreach ($joDeductions->where('deduction_group', 'PHIC')->sortBy('sort_position') as $joDeduction){
                    if (!isset($deductionTotals['PHIC'][$joDeduction->description])) {
                          $deductionTotals['PHIC'][$joDeduction->description] = 0.00;
                      }
                    foreach ($employeeData->user_deductions['PHIC'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        $deductionTotals['PHIC'][$deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;

                      }
                    }
                  }
                }

                if(isset($employeeData->user_deductions['COOP'])){
                  foreach ($joDeductions->where('deduction_group', 'COOP')->sortBy('sort_position') as $joDeduction){
                    if (!isset($deductionTotals['COOP'][$joDeduction->description])) {
                          $deductionTotals['COOP'][$joDeduction->description] = 0.00;
                      }
                    foreach ($employeeData->user_deductions['COOP'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        $deductionTotals['COOP'][$deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;

                      }
                    }
                  }
                }

                if(isset($employeeData->user_deductions['DISALLOWANCE'])){
                  foreach ($joDeductions->where('deduction_group', 'DISALLOWANCE')->sortBy('sort_position') as $joDeduction){
                    if (!isset($deductionTotals['DISALLOWANCE'][$joDeduction->description])) {
                          $deductionTotals['DISALLOWANCE'][$joDeduction->description] = 0.00;
                      }
                    foreach ($employeeData->user_deductions['DISALLOWANCE'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        $deductionTotals['DISALLOWANCE'][$deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;

                      }
                    }
                  }
                }

                if(isset($employeeData->user_deductions['OTHER'])){
                  foreach ($joDeductions->where('deduction_group', 'OTHER')->sortBy('sort_position') as $joDeduction){
                    if (!isset($deductionTotals['OTHER'][$joDeduction->description])) {
                          $deductionTotals['OTHER'][$joDeduction->description] = 0.00;
                      }
                    foreach ($employeeData->user_deductions['OTHER'] as $deduction){
                      if ($deduction->npiad_sort_position == $joDeduction->sort_position){
                        $deductionTotals['OTHER'][$deduction->npiad_description] += $deduction->npiad_amount;
                        $overAllDeductionTotal += $deduction->npiad_amount;

                      }
                    }
                  }
                }

              }
              // dd($deductionTotals);
            @endphp
            {{-- @foreach ($employee as $employeeData) --}}
                  {{-- TAX --}}
                    @if(isset($deductionTotals['TAX']))
                        @foreach ($deductionTotals['TAX'] as $dedDescription => $joDeduction)
                          <tr>
                            {{-- DED DESC --}}
                              <td>{{ $dedDescription }}</td>
                              <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                          </tr>    
                      @endforeach

                    @else
                    <tr>
                      <td> WHT </td>
                      <td style="text-align: right;">0.00</td>
                    </tr>
                    @endif

                  {{-- GSIS --}}
                    @if(isset($deductionTotals['GSIS']))
                      @foreach ($deductionTotals['GSIS'] as $dedDescription => $joDeduction)
                        <tr>
                          {{-- DED DESC --}}
                            <td>{{ $dedDescription }}</td>
                            <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                        </tr>    
                      @endforeach

                      @else
                      <tr>
                        <td> GSIS PREMIUM </td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      <tr>
                        <td> GSIS CONSOLOAN </td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      <tr>
                        <td> GSIS SALARY LOAN </td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      <tr>
                        <td> GSIS CASH ADV </td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      <tr>
                        <td> GSIS MPL </td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      @endif


                  {{-- HDMF --}}
                  
                  @if(isset($deductionTotals['HDMF']))
                    @foreach ($deductionTotals['HDMF'] as $dedDescription => $joDeduction)
                        <tr>
                          {{-- DED DESC --}}
                            <td>{{ $dedDescription }}</td>
                            <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                        </tr>    
                    @endforeach
                  @else
                    <tr>
                      <td> HDMF PREMIUM </td>
                      <td style="text-align: right;">0.00</td>
                    </tr>
                    <tr>
                      <td> HDMF MP2 </td>
                      <td style="text-align: right;">0.00</td>
                    </tr>
                    <tr>
                      <td> HDMF MPL </td>
                      <td style="text-align: right;">0.00</td>
                    </tr>
                    <tr>
                      <td> HDMF CAL </td>
                      <td style="text-align: right;">0.00</td>
                    </tr>
                  @endif

                  
                  {{-- PHIC --}}
                  @if(isset($deductionTotals['PHIC']))
                        @foreach ($deductionTotals['PHIC'] as $dedDescription => $joDeduction)
                          <tr>
                            {{-- DED DESC --}}
                              <td>{{ $dedDescription }}</td>
                              <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                          </tr>    
                      @endforeach

                    @else
                  <tr>
                    <td> PHIC PREMIUM </td>
                    <td style="text-align: right;">0.00</td>
                  </tr>
                  @endif


                  {{-- COOP --}}
                  @if(isset($deductionTotals['COOP']))
                        @foreach ($deductionTotals['COOP'] as $dedDescription => $joDeduction)
                          <tr>
                            {{-- DED DESC --}}
                              <td>{{ $dedDescription }}</td>
                              <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                          </tr>    
                      @endforeach

                    @else
                  <tr>
                    <td> COOP LOAN </td>
                    <td style="text-align: right;">0.00</td>
                  </tr>
                  @endif


                  {{-- DISALLOWANCE --}}
                  @if(isset($deductionTotals['DISALLOWANCE']))
                        @foreach ($deductionTotals['DISALLOWANCE'] as $dedDescription => $joDeduction)
                          <tr>
                            {{-- DED DESC --}}
                              <td>{{ $dedDescription }}</td>
                              <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                          </tr>    
                      @endforeach

                    @else
                  <tr>
                    <td> DISALLOWANCE </td>
                    <td style="text-align: right;">0.00</td>
                  </tr>
                  @endif


                  {{-- OTHER --}}
                  @if(isset($deductionTotals['OTHER']))
                        @foreach ($deductionTotals['OTHER'] as $dedDescription => $joDeduction)
                          <tr>
                            {{-- DED DESC --}}
                              <td>{{ $dedDescription }}</td>
                              <td style="text-align: right;"> {{ number_format(bcdiv($joDeduction, 1, 2), 2) }}</td>
                          </tr>    
                      @endforeach

                    @else
                    @foreach ($joDeductions->where('deduction_group', 'OTHER')->sortBy('sort_position') as $joDeduction)
                      <tr>
                          <td>{{ $joDeduction->description }}</td>
                          <td style="text-align: right;">0.00</td>
                      </tr>
                    @endforeach

                  @endif

                {{-- @endforeach --}}

              </tbody>

              </table>
          </td>
        </tr>
        <tr>
          @php
            $totalGrossPay = 0.00;
            foreach ($employee->sortBy('period_covered_from') as $employeeData){
              $totalGrossPay += $employeeData->basic_pay;
            }
            $totalGrossPay += $overAllAllowanceTotal;
          @endphp
          <td style="font-weight: bold;" width="70%" colspan="3">
            <table class="total-table">
              <tr>
                <td style="text-align: right; width: 80%;">TOTAL GROSS PAY:</td>
                <td style="text-align: center; width: 20%;">{{ number_format(bcdiv($totalGrossPay, 1, 2), 2) }}</td>
              </tr>
            </table>
          </td>
          <td style="font-weight: bold;" width="30%" colspan="1">
            <table class="total-table">
              <tr style="vertical-align: left; text-align: left;">
                <td>TOTAL DEDUCTIONS:</td>
                <td style="text-align: right; width: 20%;">{{ number_format(bcdiv($overAllDeductionTotal, 1, 2), 2) }}</td>
              </tr>

            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <table class="signatory-table">
              <tr>
                {{-- <td><i>Prepared by:</i></td> --}}
                <td style="vertical-align: bottom; text-align:center"><i>Certified correct:</i></td>
              </tr>
              <tr>
                {{-- <td height="40" style="vertical-align: bottom;"><b>EDNA V. NANALES</b></td> --}}
                <td height="40" style="vertical-align: bottom; text-align:center"><b>RYAN A. RIVERA</b></td>
              </tr>
              <tr>
                {{-- <td><i>Data Encoder</i></td> --}}
                <td style="vertical-align: bottom; text-align:center"><i>Chief, Administrative and Finance Section</i></td>
              </tr>
            </table>
          </td>
          <td colspan="1" style="width:30%">
            <table class="netpay-table">
              <tr style="vertical-align: bottom;">
                <td style="vertical-align: bottom;"><b><h2 style="margin-bottom:0%;">N E T  P A Y :</h2></b></td>
     
                <td style="font-weight: bold; border-bottom: 3px double #000; vertical-align: bottom; text-align:right;margin-bottom:0px;" >
                  <h2 style="margin-bottom:0px;">
                    {{ number_format(bcdiv(number_format($totalGrossPay, 6, ".", "") - number_format($overAllDeductionTotal, 6, ".", ""), 1, 2), 2) }}
                  </h2>
                </td>    
              
              </tr>
              <tr>
                <td colspan="2">
                  <p>
                  <b>
                    {{ strtoupper(Helper::numberToWord(sprintf("%.2f", bcdiv(number_format($totalGrossPay - $overAllDeductionTotal, 6, ".", ""), 1, 2) ))) }}
                  </b>
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
    </tbody>
    </table>

    {{-- </caption> --}}
    <div class="footer-container">
      {{-- <img class="footer-img" src="{{ public_path('img/test-footer-test.jpg') }}" width="827.315px" height="98px" alt="nia footer"> --}}
      <p style="margin:0; font-size: 9px; position:absolute; margin-top:40px; margin-left:8px;" class="calibri"><b>Brgy. Bayaoas, Urdaneta City, Pangasinan, Ilocos Region, 2428 Philippines</b></p>
      <p style="margin:0; font-size: 9px; position:absolute; margin-top:52px; margin-left:8px;" class="calibri">Telephone / Telefax No: (075) 632-2775</p>
      <p style="margin:0; font-size: 9px; position:absolute; margin-top:64px; margin-left:8px;" class="calibri">Email: r1.pangasinan-imo@nia.gov.ph &#x2022; Website: https://region1.nia.gov.ph &#x2022; TIN: 000916415</p>
      {{-- <p style="margin:0; font-size: 11px; position:absolute; margin-top:66px; margin-left:8px;">TIN: 000916415</p> --}}
      <img class="footer-img" src="{{ public_path('img/test-footer-test.jpg') }}" width="100%" alt="nia footer">
      <p style="margin:0%; font-size: 9px; position:absolute; margin-top:98px; margin-left:8px;">
        <i>NIA-PIMO-AFS-ADM-INT-Form35 Rev.03</i>
      </p>
    </div>

  </div>

    <script type="text/php">
      if ( isset($pdf) ) {

          // $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
          $font = $fontMetrics->get_font("cambria", "bold");
          $size = 10;

          // $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
          // $x = ($pdf->get_width() - $width) / 2;
          $y = $pdf->get_height() - 35;

          $color = array(0,0,0);
          $word_space = 0.0;  //  default
          $char_space = 0.0;  //  default
          $angle = 0.0;   //  default

          // $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

          // Add additional text in the left footer
          $leftText = "";
          $leftWidth = $fontMetrics->get_text_width($leftText, $font, $size);
          $pdf->page_text(20, $y, $leftText, $font, $size, $color, $word_space, $char_space, $angle);
      }
  </script>
</body>
</html>
