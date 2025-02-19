<div>
  <font size="1.75" face="Cambria" >

    <div class="modal fade" id="showIndexModal" tabindex="-1" aria-labelledby="showIndexModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="showIndexModalLabel">INDEXING</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="accordion" id="accordionPanelsStayOpenExample">

                @isset($payrollIndexUserPerYear)
                  @foreach ($payrollIndexUserPerYear as $year)
                  {{-- SET KEY YEAR --}}
                  @php
                    $carbonDate = \Carbon\Carbon::parse($year->period_covered_from);
                    $carbonYear = $carbonDate->year;
                    $carbonMonth = $carbonDate->month;
                    $carbonDay = $carbonDate->day;
                    $keyYear = 'year'.$carbonYear;
                    $keyMonth = 'month'.$carbonMonth.$carbonDay;
                  @endphp


                    <div class="accordion-item">
                      <h2 class="accordion-header" id="panelsStayOpen-headingOne{{ $year->myyear }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne{{ $year->myyear }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne{{ $year->myyear }}">
                          {{ $year->myyear }}
                        </button>
                      </h2>
                      <div id="panelsStayOpen-collapseOne{{ $year->myyear }}" class="accordion-collapse collapse @if ($loop->first) show @endif" aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col" rowspan="3" class="text-center align-middle">PERIOD COVERED</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">NO. OF DAYS WORKED</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">DAILY RATE</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">BASIC PAY</th>
                                <th scope="col" colspan="{{ (8 + $numberOfDedCols) }}" class="text-center align-middle">D E D U C T I O N S</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">TOTAL DEDUCTIONS</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">NET PAY</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">FUNDING CHARGES</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">DV/PAYROLL NO.</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">REMARKS</th>
                              </tr>
                              <tr>
                                  <th
                                      @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX']))
                                          colspan="{{ ($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX']
                                      ->where('deduction_type', '<>', 'TAX')
                                      ->count() + 1) }}"
                                      @else
                                          rowspan="2"
                                      @endif
                                      class="text-center align-middle">
                                      WHT
                                  </th>

                                  <th scope="col"
                                    @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['HDMF']))
                                      colspan="{{ ($myDeductionCollection["year".$year->myyear][$keyMonth]['HDMF']
                                      ->where('deduction_type', '<>', 'HDMF_PREMIUM')
                                      ->where('deduction_type', '<>', 'HDMF_MP2')
                                      ->where('deduction_type', '<>', 'HDMF_MPL')
                                      ->where('deduction_type', '<>', 'HDMF_CAL')
                                      ->count() + 4) }}"
                                    @else
                                      colspan="4"
                                    @endif
                                    class="text-center align-middle">
                                    HDMF
                                  </th>

                                  <th
                                    @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC']))
                                      colspan="{{ ($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC']
                                      ->where('deduction_type', '<>', 'PHIC')
                                      ->count() + 1) }}"
                                    @else
                                      rowspan="2"
                                    @endif
                                    class="text-center align-middle">
                                    PHIC
                                  </th>

                                  <th
                                      @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP']))
                                          colspan="{{ ($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP']
                                            ->where('deduction_type', '<>', 'COOP')
                                            ->count() + 1) }}"
                                      @else
                                          rowspan="2"
                                      @endif
                                      class="text-center align-middle">
                                      COOP LOANz
                                  </th>

                                  <th
                                      @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['DISALLOWANCE']))
                                          colspan="{{ ($myDeductionCollection["year".$year->myyear][$keyMonth]['DISALLOWANCE']
                                      ->where('deduction_type', '<>', 'DISALLOWANCE')
                                      ->count() + 1) }}"
                                      @else
                                          rowspan="2"
                                      @endif
                                      class="text-center align-middle">
                                      DISALLOWANCE
                                  </th>

                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                        ADDITIONAL DEDUCTIONS' HEADER                       */
                                  /* -------------------------------------------------------------------------- */ --}}
                                  @foreach ($myAdditionalDeductionCollection["year".$year->myyear][$keyMonth]->sortBy('sort_position') as $additionalDeduction)
                                    <th rowspan="2" class="text-center align-middle">{{ $additionalDeduction[0]['description'] }}</th>
                                  @endforeach
                              </tr>
                              <tr>

                                  @isset($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX'])
                                      @if (($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX']
                                      ->where('deduction_type', '<>', 'TAX')
                                      ->count()) > 0)
                                          <th scope="col" class="text-center align-middle">WHT</th>
                                      @endif
                                  @endisset

                                  @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX']))
                                      @foreach ($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX']
                                      ->where('deduction_type', '<>', 'TAX')
                                      ->sortBy('sort_position')->all() as $taxDeduction)
                                          <th scope="col" class="text-center align-middle ">{{ $taxDeduction[0]->description }}</th>
                                      @endforeach
                                  @endif

                              <th scope="col" class="text-center align-middle">PREMIUM</th>
                              <th scope="col" class="text-center align-middle">M2</th>
                              <th scope="col" class="text-center align-middle">MPLOAN</th>
                              <th scope="col" class="text-center align-middle">CAL</th>

                              @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['HDMF']))
                                  @foreach ($myDeductionCollection["year".$year->myyear][$keyMonth]['HDMF']
                                  ->where('deduction_type', '<>', 'HDMF_PREMIUM')
                                  ->where('deduction_type', '<>', 'HDMF_MP2')
                                  ->where('deduction_type', '<>', 'HDMF_MPL')
                                  ->where('deduction_type', '<>', 'HDMF_CAL')
                                  ->sortBy('sort_position')->all() as $hdmfDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $hdmfDeduction[0]->description }}</th>
                                  @endforeach
                              @endif

                                  @isset($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC'])
                                      @if (($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC']
                                      ->where('deduction_type', '<>', 'PHIC')
                                      ->count()) > 0)
                                        <th scope="col" class="text-center align-middle">PHIC</th>
                                      @endif
                                  @endisset

                              @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC']))
                                  @foreach ($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC']
                                  ->where('deduction_type', '<>', 'PHIC')
                                  ->sortBy('sort_position')->all() as $phicDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $phicDeduction[0]->description }}</th>
                                  @endforeach
                              @endif

                              @isset($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP'])
                                  @if (($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP']
                                  ->where('deduction_type', '<>', 'COOP')
                                  ->count()) > 0)
                                      <th scope="col" class="text-center align-middle">COOP</th>
                                  @endif
                              @endisset

                              @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP']))
                                  @foreach ($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP']
                                  ->where('deduction_type', '<>', 'COOP')
                                  ->sortBy('sort_position')->all() as $coopDeduction)
                                      <th scope="col" class="text-center align-middle ">{{ $coopDeduction[0]->description }}</th>
                                  @endforeach
                              @endif

                              @isset($myDeductionCollection["year".$year->myyear][$keyMonth]['DISALLOWANCE'])
                                  @if (($myDeductionCollection["year".$year->myyear][$keyMonth]['DISALLOWANCE']
                                  ->where('deduction_type', '<>', 'DISALLOWANCE')
                                  ->count()) > 0)
                                      <th scope="col" class="text-center align-middle">DISALLOWANCE</th>
                                  @endif
                              @endisset

                              @if (isset($myDeductionCollection[$keyYear]['DISALLOWANCE']))
                                  @foreach ($myDeductionCollection[$keyYear]['DISALLOWANCE']
                                  ->where('deduction_type', '<>', 'DISALLOWANCE')
                                  ->sortBy('sort_position')->all() as $disallowanceDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $disallowanceDeduction->description }}</th>
                                  @endforeach
                              @endif

                            </tr>

                            </thead>
                            <tbody>

                            @foreach ($lolpayrollIndexPerUser->get() as $perUser)

                            @php
                              $carbonDate = Carbon\Carbon::parse($perUser->period_covered_from);
                              $carbonYear = $carbonDate->year;
                              $carbonMonth = $carbonDate->month;
                              $carbonDay = $carbonDate->day;
                              $keyYear = 'year'.$carbonYear;
                              $keyMonth = 'month'.$carbonMonth.$carbonDay;
                            @endphp

                              @if($carbonYear == $year->myyear)

                              <tr>
                                <td class="text-center align-middle">{{ $perUser->period_covered_from }}</td>
                                <td class="text-center align-middle">{{ $perUser->days_rendered }}</td>
                                <td class="text-center align-middle">{{ $perUser->daily_monthly_rate }}</td>
                                <td class="text-center align-middle">{{ $perUser->basic_pay }}</td>

                           {{-- /* -------------------------------------------------------------------------- */
                                /*                                     TAX                                    */
                                /* -------------------------------------------------------------------------- */ --}}
                                  @php
                                      // $year = 2023;
                                      $excludedTypes = ['TAX'];
                                      $deductionGroup = 'TAX';
                                      $deductionTypes = App\Models\indexDeduction::getDeductionTypesByYear($year->myyear, $deductionGroup, $excludedTypes);

                                  @endphp

                                  @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX']))
                                      @foreach($myDeductionCollection["year".$year->myyear][$keyMonth]['TAX'] as $parentTax)
                                          @for($y=0; $y < 1+$deductionTypes->count(); $y++)
                                              <td class="text-center align-middle">
                                                  @foreach ($parentTax as $tax)
                                                      @if ($tax->sort_position == $y+1)
                                                          @if ($perUser->period_covered_from == $tax->payrollIndex->period_covered_from)
                                                              {{ number_format((float)$tax->amount, 2) }}
                                                          @endif
                                                      @endif
                                                  @endforeach
                                              </td>
                                          @endfor
                                      @endforeach
                                  @else
                                      @for($y = 0; $y < 1 + $deductionTypes->count(); $y++)
                                          <td></td>
                                      @endfor
                                  @endif

                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                                    HDMF                                    */
                                  /* -------------------------------------------------------------------------- */ --}}

                                  @php
                                      // $year = 2023;
                                      $excludedTypes = ['HDMF_PREMIUM','HDMF_MP2','HDMF_MPL','HDMF_CAL'];
                                      $deductionGroup = 'HDMF';
                                      $deductionTypes = App\Models\indexDeduction::getDeductionTypesByYear($year->myyear, $deductionGroup, $excludedTypes);

                                  @endphp

                                  @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['HDMF']))
                                      @foreach($myDeductionCollection["year".$year->myyear][$keyMonth]['HDMF'] as $parentHdmf)

                                            @for($y=0; $y < 4 + $deductionTypes->count(); $y++)
                                              <td class="text-center align-middle">
                                                @foreach ($parentHdmf as $hdmf)
                                                  @if ($hdmf->sort_position == $y+1)
                                                    @if ($perUser->period_covered_from == $hdmf->payrollIndex->period_covered_from)
                                                      {{ number_format((float)$hdmf->amount, 2) }}
                                                    @endif
                                                  @endif
                                                @endforeach
                                              </td>
                                            @endfor

                                      @endforeach
                                  @else
                                    @for($y=0; $y<4 + $deductionTypes->count(); $y++)
                                      <td></td>
                                    @endfor
                                  @endif

                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                                    PHIC                                    */
                                  /* -------------------------------------------------------------------------- */ --}}

                                  @php
                                      // $year = 2023;
                                      $excludedTypes = ['PHIC'];
                                      $deductionGroup = 'PHIC';
                                      $deductionTypes = App\Models\indexDeduction::getDeductionTypesByYear($year->myyear, $deductionGroup, $excludedTypes);

                                  @endphp

                                  @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC']))
                                      @foreach($myDeductionCollection["year".$year->myyear][$keyMonth]['PHIC'] as $parentPhic)
                                            @for($y=0; $y < 1+$deductionTypes->count(); $y++)
                                              <td class="text-center align-middle">
                                                @foreach ($parentPhic as $phic)
                                                  @if ($phic->sort_position == $y+1)
                                                    @if ($perUser->period_covered_from == $phic->payrollIndex->period_covered_from)
                                                      {{ number_format((float)$phic->amount, 2) }}
                                                    @endif
                                                  @endif
                                                @endforeach
                                              </td>
                                            @endfor
                                      @endforeach
                                  @else
                                    @for($y = 0; $y < 1 + $deductionTypes->count(); $y++)
                                        <td></td>
                                    @endfor
                                  @endif

                           {{-- /* -------------------------------------------------------------------------- */
                                /*                                  COOP LOAN                                 */
                                /* -------------------------------------------------------------------------- */ --}}
                                  @php
                                      $excludedTypes = ['COOP'];
                                      $deductionGroup = 'COOP';
                                      $deductionTypes = App\Models\indexDeduction::getDeductionTypesByYear($year->myyear, $deductionGroup, $excludedTypes);

                                      // $year = 2023;
                                      $payrollPeriodFrom = $year->period_covered_from;
                                      $payrollPeriodTo = $year->period_covered_to;

                                      $coopDeductions = App\Models\indexDeduction::getDeductionByPayrollPeriod($year->myyear, $payrollPeriodFrom, $payrollPeriodTo, $deductionGroup);
                                  @endphp

                                  @if (isset($coopDeductions))
                                      @for($y=0; $y < 1 + $deductionTypes->count(); $y++)
                                          <td class="text-center align-middle">
                                          @foreach($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP'][0] as $parentCoop)
                                                @if ($parentCoop->sort_position == $y+1)
                                                    @if ($perUser->period_covered_from == $parentCoop->payrollIndex->period_covered_from)
                                                        {{ number_format((float)$parentCoop->amount, 2) }}
                                                    @endif
                                                @endif
                                            @endforeach
                                          </td>

                                          @endfor

                                  @else
                                      @for($y = 0; $y < 1 + $deductionTypes->count(); $y++)
                                          <td>ASD</td>
                                      @endfor
                                  @endif

{{--                              @php--}}
{{--                                  // $year = 2023;--}}
{{--                                  $excludedTypes = ['COOP'];--}}
{{--                                  $deductionGroup = 'COOP';--}}
{{--                                  $deductionTypes = App\Models\indexDeduction::getDeductionTypesByYear($year->myyear, $deductionGroup, $excludedTypes);--}}

{{--                                  @endphp--}}
{{--                                  @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP']))--}}
{{--                                      @foreach($myDeductionCollection["year".$year->myyear][$keyMonth]['COOP'] as $COOP)--}}
{{--                                          @for($y=0; $y < 1+$deductionTypes->count(); $y++)--}}
{{--                                              <td class="text-center align-middle">--}}
{{--                                                  @foreach ($COOP as $coop)--}}
{{--                                                      @if ($coop->sort_position == $y+1)--}}
{{--                                                          @if ($perUser->period_covered_from == $coop->payrollIndex->period_covered_from)--}}
{{--                                                              {{ number_format((float)$coop->amount, 2) }}--}}
{{--                                                          @endif--}}
{{--                                                      @endif--}}
{{--                                                  @endforeach--}}
{{--                                              </td>--}}
{{--                                          @endfor--}}
{{--                                      @endforeach--}}
{{--                                  @else--}}
{{--                                      @for($y = 0; $y < 1 + $deductionTypes->count(); $y++)--}}
{{--                                          <td></td>--}}
{{--                                      @endfor--}}
{{--                                  @endif--}}

                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                                DISALLOWANCE                                */
                                  /* -------------------------------------------------------------------------- */ --}}
                                  @php
                                      // $year = 2023;
                                      $excludedTypes = ['DISALLOWANCE'];
                                      $deductionGroup = 'DISALLOWANCE';
                                      $deductionTypes = App\Models\indexDeduction::getDeductionTypesByYear($year->myyear, $deductionGroup, $excludedTypes);

                                  @endphp

                                  @if (isset($myDeductionCollection["year".$year->myyear][$keyMonth]['DISALLOWANCE']))
                                      @foreach($myDeductionCollection["year".$year->myyear][$keyMonth]['DISALLOWANCE'] as $parentDisallowance)
                                          @for($y=0; $y < 1+$deductionTypes->count(); $y++)
                                              <td class="text-center align-middle">
                                                  @foreach ($parentDisallowance as $disallow)
                                                      @if ($disallow->sort_position == $y+1)
                                                          @if ($perUser->period_covered_from == $disallow->payrollIndex->period_covered_from)
                                                              {{ number_format((float)$disallow->amount, 2) }}
                                                          @endif
                                                      @endif
                                                  @endforeach
                                              </td>
                                          @endfor
                                      @endforeach
                                  @else
                                      @for($y = 0; $y < 1 + $deductionTypes->count(); $y++)
                                          <td></td>
                                      @endfor
                                  @endif


                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                            ADDITIONAL DEDUCTION                            */
                                  /* -------------------------------------------------------------------------- */ --}}
                                @if(!$myAdditionalDeductionCollection["year".$year->myyear][$keyMonth]->isEmpty())
                                  @forelse ($myAdditionalDeductionCollection["year".$year->myyear][$keyMonth]->sortBy('sort_position') as $additionalDeduction)
                                    <td class="text-center align-middle">
                                      @if ($additionalDeduction != '' && $additionalDeduction != null)
                                          {{ $additionalDeduction[0]->amount }}
                                      @endif
                                    </td>
                                  @empty

                                  <td>6</td>

                                  @endforelse
                                @endif
                                <td class="text-center align-middle">{{ $perUser->total_deductions ? ($perUser->total_deductions) : "0.00" }}</td>

                                <td class="text-center align-middle">{{ $perUser->net_pay ? ($perUser->net_pay) : "0.00" }}</td>

                                <td class="text-center align-middle">{{ $perUser->funding_charges }}</td>

                                <td class="text-center align-middle">{{ $perUser->dv_payroll_no }}</td>

                                <td class="text-center align-middle">{{ $perUser->remarks }}</td>

                              </tr>
                              @endif
                            @endforeach


                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                  @endforeach
                @endisset

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Download</button>
            </div>
          </div>
        </div>
      </div>
  </font>
    </div>
