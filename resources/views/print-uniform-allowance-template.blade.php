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


 @page { size: 21.59cm 33.02cm landscape; margin-top: 0;}
 /* @page { size: 21.59cm 39cm landscape; margin-top: 0; } */

     body {
         /* background: #fb887c; */
         /* color: #fff; */
         /* margin-left: -25px;
         margin-right: -25px; */
         margin-left: -15px;
         margin-right: -15px;
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
      /* border: 1.5px solid black; */
      border: 0;
      margin-top: 45px;
     }

     caption>h2 {
      margin-top: 0;
      margin-bottom: 0;
      font-size: 14px;
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

  .header-container{
    position: relative;
  }
  .header-img{
    position: absolute;
    width: 100%;
    margin-top: -20px;
    margin-left: -15px;
    z-index: -999;
  }

  .main-table{
    border: 0;
  }
  .main-table tr>td{
    /* border-bottom: none; */
    border-top: none;
  }

  .main-table tfoot{
    border-top: solid;
  }

  table tfoot {
                inset-block-end: 0;
                /* "bottom" */
                color: #0a090a;
                background-color: #bdd9fe;
                text-align: right;
                font-weight: bold;
            }

.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
    background-color: #bdd9fe;
}

</style>

</head>

<body>
  <font size="2.5" face="Cambria">
            @php
                $counter = 0;
                $hasNegativeNetpay = 0;
                $total_uniform_allowance = 0;

                $payrollUsers = $payrollFund->users(null, false, null, null, $office)
                    ->where('employment_status', 'PERMANENT') // Filter by employment_status
                    ->orWhere('employment_status', 'COTERMINOUS') // Filter by employment_status
                    ->where('is_active', 1) // Filter by active users
                    ->get()
                    ->sortBy('full_name'); // Sort by full name

            @endphp

                    {{-- @foreach ($payrollSection as $office => $payrollUserSections) --}}
                        <div class="border border-light shadow p-3 mb-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="header-container">
                                        {{-- <img class="header-img" src="{{ public_path('img/a4-landscape-header.png') }}" width="450.315px" alt="nia header"> --}}
                                        <img class="header-img" src="{{ public_path('img/a4-landscape-header.png') }}" alt="nia header">
                                    </div>
                                    <caption>
                                          <h2>G E N E R A L &nbsp; P A Y R O L L</h2>
                                          <div style="margin-left: 5px; margin-bottom: 3px;">
                                    </caption>
                                        <div class="col-sm-12">
                                            <span class="fw-sm text-start" style="font-size: 7px;">
                                            @foreach ($payrollUsers as $payrollUser)
                                                @if($payrollUser->uniformAllowances->where('year', $year)->first()->mc != "" && $payrollUser->uniformAllowances->where('year', $year)->first()->mc != null)
                                                    {{ $payrollUser->uniformAllowances->where('year', $year)->first()->mc }}
                                                @else
                                                    NIA MC No.__________series of_________
                                                @endif
                                                @break
                                            @endforeach
                                        </span>
                                        </div>
                                        <div class="col-sm-12">
                                            <i class="fw-sm" style="font-size: 7px;">UNIFORM ALLOWANCE FOR CY {{ $year }}</i>
                                        </div>
                                          </div>
                                        <table class="main-table">
                                            <thead class="sticky-top">
                                                <tr>
                                                    <th scope="col" style="width:3%"
                                                        class="text-center align-middle">NO.
                                                    </th>
                                                    <th scope="col" style="width:25%"
                                                        class="text-center align-middle ">NAME
                                                    </th>
                                                    <th scope="col" style="width:25%"
                                                        class="text-center align-middle">POSITION TITLE / JG
                                                    </th>
                                                    <th scope="col" style="width:15%"
                                                        class="text-center align-middle">UNIFORM ALLOWANCE
                                                    </th>
                                                    <th scope="col" style="width:25%"
                                                        class="text-center align-middle">REMARKS
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($payrollUsers as $payrollUser)
                                                {{-- @if ($payrollFund->id == $payrollUser->fund_id) --}}
                                                   {{-- @if ($office == $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office']) --}}
                                                   {{-- @if ($office == $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office']) --}}
                                                   @php
                                                        $counter = $counter + 1;
                                                        $total_uniform_allowance += $payrollUser->uniformAllowances->where('year', $year)->first()->uniform_allowance;

                                                   @endphp

                                                    @php
                                                        if (
                                                            number_format(
                                                                $payrollUser->basic_pay -
                                                                    $payrollUser->total_user_deduction,
                                                                2,
                                                            ) < 0
                                                        ) {
                                                            $hasNegativeNetpay++;
                                                        }
                                                    @endphp
                                                        <tr
                                                        @if (number_format($payrollUser->basic_pay - $payrollUser->total_user_deduction, 2) < 0) style="background-color: rgba(245, 94, 39, 0.172)" @endif>
                                                        <td scope="row" style="width: 50px; text-align:center;"
                                                            class="text-center align-middle">
                                                            <div class="form-switch w-0 p-0 pt-1">
                                                                <span
                                                                    style="">{{ $counter }}</span>
                                                                <br>
                                     

                                                            </div>
                                                        </td>
                                                        <td scope="row" class="text-start">
                                                
                                                                {{ $payrollUser->full_name }}
                                                      
                                                        </td>
                                                        <td scope="row" class="text-start">
                                                            {{ $payrollUser->position }} /
                                                            {{ $payrollUser->sg_jg }}</td>
                                                        <td scope="row" style="text-align: center;"
                                                            class="text-center align-middle p-0">
                                                            {{ number_format((float)$payrollUser->uniformAllowances->where('year', $year)->first()->uniform_allowance, 2) }}
                                                        </td>
                                                        <td
                                                            class="text-center align-middle p-0">
                                                            {{ $payrollUser->uniformAllowances->where('year', $year)->first()->remarks }}

                                                        </td>
                                                    </tr>
                                                        {{-- @endif --}}
                                                    {{-- @endif --}}
                                            @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan=3 style="text-align: right;"><b>TOTAL</b></td>
                                                    <td colspan=2 style="padding-left: 46px; text-align: left;"><b>{{ number_format(bcdiv((float) $total_uniform_allowance, 1, 2), 2) }}</b></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>


                                @php
                                    $preparer = $signatories ? $signatories->firstWhere('type', 'Box A [Preparer]') : 'N/A';
                                    $certifier = $signatories ? $signatories->firstWhere('type', 'Box B [Certified]') : 'N/A';
                                    $approver = $signatories ? $signatories->firstWhere('type', 'Box C [Approved for Payment]') : 'N/A';
                                    $afsChief = $signatories ? $signatories->firstWhere('type', 'Box D [Certified]') : 'N/A';
                                @endphp

                                <div class="footer" style="page-break-inside: avoid;">
                                    <table class="signatory">
                                        <tr>
                                            <td>A. Prepared by:</td>
                                            <td>B. Certified:</td>
                                        </tr>
                                        <tr>
                                            <td><br></td>
                                            <td>Supporting documents are complete and proper, computations are correct, and ASA and cash is available</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 50px;">
                                                <table style="width: 50%; border: 0px;">
                                                    <tr>
                                                        <td style="padding: 0px; padding-top: 20px;">
                                                            {{ $preparer->name ?? '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px;">
                                                            {{ $preparer->position ?? '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px; padding-top: 8px;">Date:_____________________</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top; padding-left: 50px;">
                                                <table style="width: 50%; border: 0px;">
                                                    <tr>
                                                        <td style="padding: 0px; padding-top: 20px;">
                                                            {{ $certifier->name ?? '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px;">
                                                            {{ $certifier->position ?? '' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 30px;">
                                                C. Approved for Payment:
                                            </td>
                                            <td style="padding-top: 30px;">
                                                D. Certified:
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><br></td>
                                            <td>Each employee whose name appears on the payroll has been paid and the corresponding net amount opposite his/her name was credited to his/</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 50px;">
                                                <table style="width: 50%; border: 0px;">
                                                    <tr>
                                                        <td style="padding: 0px; padding-top: 20px;">
                                                            {{ $approver->name ?? '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px;">
                                                            {{ $approver->position ?? '' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="padding-left: 50px;">
                                                <table style="width: 50%; border: 0px;">
                                                    <tr>
                                                        <td style="padding: 0px; padding-top: 20px;">
                                                            {{ $afsChief->name ?? '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px;">
                                                            {{ $afsChief->position ?? '' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                            @php
                                $counter = 0;
                                $total_uniform_allowance = 0;

                            @endphp
                            {{-- @endforeach --}}

</font>



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
