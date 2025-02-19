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
                  {{-- @dd($payrollIndexUserPerYear) --}}
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
                                {{-- @dd($numberOfDedCols) --}}
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
                                <th scope="col"

                                  @if (isset($myDeductionCollection->$keyYear['TAX']))
                                    @if ($myDeductionCollection->$keyYear['TAX']->count() <= 1)
                                      rowspan="2"
                                    @else
                                      colspan="{{ $myDeductionCollection->$keyYear['TAX']->count() }}"
                                    @endif
                                  @else
                                    rowspan="2" 
                                  @endif
                                    class="text-center align-middle">
                                    WHT
                                  </th>
    
                                  <th scope="col"
                                    @if (isset($myDeductionCollection->$keyYear['HDMF']))
                                      colspan="{{ ($myDeductionCollection->$keyYear['HDMF']
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

                                  <th scope="col"  
                                    @if (isset($myDeductionCollection->$keyYear['PHIC']))
                                      @if ($myDeductionCollection->$keyYear['PHIC']->count() <= 1)
                                        rowspan="2" 
                                      @else
                                      colspan="{{ $myDeductionCollection->$keyYear['PHIC']->count() }}"
                                      @endif
                                    @else
                                      rowspan="2" 
                                    @endif
                                    class="text-center align-middle">
                                    PHIC
                                  </th>

                                  <th scope="col"  
                                    @if (isset($myDeductionCollection->$keyYear['COOP']))
                                      @if ($myDeductionCollection->$keyYear['COOP']->count() <= 1)
                                        rowspan="2" 
                                      @else
                                      colspan="{{ $myDeductionCollection->$keyYear['COOP']->count() }}"
                                      @endif
                                    @else
                                      rowspan="2" 
                                    @endif
                                    class="text-center align-middle">
                                    COOP LOAN
                                  </th>

                                  <th scope="col"
                                    @if (isset($myDeductionCollection->$keyYear['DISALLOWANCE']))
                                      @if ($myDeductionCollection->$keyYear['DISALLOWANCE']->count() <= 1)
                                      rowspan="2" 
                                      @else
                                        colspan="{{ $myDeductionCollection->$keyYear['DISALLOWANCE']->count() }}"
                                      @endif
                                    @else
                                      rowspan="2" 
                                    @endif
                                    class="text-center align-middle">
                                    DISALLOWANCE
                                  </th>
                                 
                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                        ADDITIONAL DEDUCTIONS' HEADER                       */
                                  /* -------------------------------------------------------------------------- */ --}}
                                  @foreach ($myAdditionalDeductionCollection[$keyYear][$keyMonth]->sortBy('sort_position') as $additionalDeduction)
                                    <th rowspan="2" class="text-center align-middle">{{ $additionalDeduction[0]['description'] }}</th>                    
                                  @endforeach
                                  
                              </tr>

                              <tr>

                              @if (isset($myDeductionCollection->$keyYear['TAX']))
                                @if ($myDeductionCollection->$keyYear['TAX']->count() > 1)
                                  @foreach ($myDeductionCollection->$keyYear['TAX']->sortBy('sort_position')->all() as $taxDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $taxDeduction->description }}</th>
                                  @endforeach
                                @endif
                              @endif

                              <th scope="col" class="text-center align-middle ">PREMIUM</th>
                              <th scope="col" class="text-center align-middle ">M2</th>
                              <th scope="col" class="text-center align-middle ">MPLOAN</th>
                              <th scope="col" class="text-center align-middle ">CAL</th>

                              @if (isset($myDeductionCollection->$keyYear['HDMF']))
                                  @foreach ($myDeductionCollection->$keyYear['HDMF']
                                  ->where('deduction_type', '<>', 'HDMF_PREMIUM')
                                  ->where('deduction_type', '<>', 'HDMF_MP2')
                                  ->where('deduction_type', '<>', 'HDMF_MPL')
                                  ->where('deduction_type', '<>', 'HDMF_CAL')
                                  ->sortBy('sort_position')->all() as $hdmfDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $hdmfDeduction->description }}</th>
                                  @endforeach
                              @endif

                              @if (isset($myDeductionCollection->$keyYear['PHIC']))
                                  @foreach ($myDeductionCollection->$keyYear['PHIC']
                                  ->where('deduction_type', '<>', 'PHIC')
                                  ->sortBy('sort_position')->all() as $phicDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $phicDeduction->description }}</th>
                                  @endforeach
                              @endif
                              
                              @if (isset($myDeductionCollection->$keyYear['COOP']))
                                  @foreach ($myDeductionCollection->$keyYear['COOP']
                                  ->where('deduction_type', '<>', 'COOP')
                                  ->sortBy('sort_position')->all() as $coopDeduction)
                                    <th scope="col" class="text-center align-middle ">{{ $coopDeduction->description }}</th>
                                  @endforeach
                              @endif

                              @if (isset($myDeductionCollection->$keyYear['DISALLOWANCE']))
                                  @foreach ($myDeductionCollection->$keyYear['DISALLOWANCE']
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
                                <td class="text-center align-middle">{{ $perUser->period_covered }}</td>
                                <td class="text-center align-middle">{{ $perUser->days_rendered }}</td>
                                <td class="text-center align-middle">{{ $perUser->daily_monthly_rate }}</td>
                                <td class="text-center align-middle">{{ $perUser->basic_pay }}</td>

                                {{-- /* -------------------------------------------------------------------------- */
                                /*                                     TAX                                    */
                                /* -------------------------------------------------------------------------- */ --}}
                                  @if (isset($myDeductionCollection->$keyYear->keyMonth['TAX']))
                                    @for($y=0; $y<
                                      ($myDeductionCollection->$keyYear['TAX']
                                      ->where('deduction_type', '<>', 'TAX')
                                      ->count() + 1); $y++)

                                        <td class="text-center align-middle">
                                          asd
                                          @foreach ($myDeductionCollection->$keyYear['TAX'] as $tax)
                                            @if ($tax->sort_position == $y+1)
                                              {{ number_format((float)$tax->amount, 2) }}
                                            @endif
                                          @endforeach
                                        </td>

                                    @endfor
                                  @else
                                  <td>asd tax</td>
                                  @endif

                                  {{-- /* -------------------------------------------------------------------------- */
                                  /*                                    HDMF                                    */
                                  /* -------------------------------------------------------------------------- */ --}}
                                  @if (isset($myDeductionCollectionTemp->$keyYear->$keyMonth['HDMF']))
                                            @for($y=0; $y<4; $y++)
                                              <td class="text-center align-middle">
                                                @foreach ($myDeductionCollectionTemp->$keyYear->$keyMonth['HDMF'] as $hdmf)
                                                  @if ($hdmf->sort_position == $y+1)
                                                    @if ($perUser->period_covered_from == $hdmf->payrollIndex->period_covered_from)
                                                      {{ number_format((float)$hdmf->amount, 2) }}
                                                    @endif
                                                  @endif
                                                @endforeach
                                              </td>
                                            @endfor
                                  @else
                                    @for($y=0; $y<4; $y++)
                                      <td>sad hdmf</td>
                                    @endfor
                                  @endif

                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                                    PHIC                                    */
                                  /* -------------------------------------------------------------------------- */ --}}
                                  @if (isset($myDeductionCollectionTemp->$keyYear->$keyMonth['PHIC']))
                                      <td class="text-center align-middle">
                                        @foreach ($myDeductionCollectionTemp->$keyYear->$keyMonth['PHIC'] as $phic)
                                            @if ($perUser->period_covered_from == $phic->payrollIndex->period_covered_from)
                                              {{ number_format((float)$phic->amount, 2) }}
                                            @endif
                                        @endforeach
                                      </td>
                                @else
                                    <td>phic</td>
                                @endif

                           {{-- /* -------------------------------------------------------------------------- */
                                /*                                  COOP LOAN                                 */
                                /* -------------------------------------------------------------------------- */ --}}
                                @if (isset($myDeductionCollectionTemp->$keyYear->$keyMonth['COOP']))
                                      <td class="text-center align-middle">
                                        @foreach ($myDeductionCollectionTemp->$keyYear->$keyMonth['COOP'] as $COOP)
                                            @if ($perUser->period_covered_from == $COOP->payrollIndex->period_covered_from)
                                              {{ number_format((float)$COOP->amount, 2) }}
                                            @endif
                                        @endforeach
                                      </td>
                                @else
                                    <td>COOP</td>
                                @endif

                             {{-- /* -------------------------------------------------------------------------- */
                                  /*                                DISALLOWANCE                                */
                                  /* -------------------------------------------------------------------------- */ --}}
                                  @if (isset($myDeductionCollection->$keyYear['DISALLOWANCE']))
                                    @for($y=0; $y<
                                      ($myDeductionCollection->$keyYear['DISALLOWANCE']
                                      ->where('deduction_type', '<>', 'DISALLOWANCE')
                                      ->count() + 1); $y++)
                                        <td class="text-center align-middle">
                                          @foreach ($myDeductionCollection->$keyYear['DISALLOWANCE'] as $disallowance)
                                            @if ($disallowance->sort_position == $y+1)
                                              {{ number_format((float)$disallowance->amount, 2) }}
                                            @endif
                                          @endforeach
                                        </td>
                                      @endfor
                                  @else
                                    <td></td>
                                  @endif

                                  @foreach ($myAdditionalDeductionCollection[$keyYear][$keyMonth]->sortBy('sort_position') as $additionalDeduction)
                                    <td class="text-center align-middle">
                                        {{ $additionalDeduction[0]->amount }}
                                    </td>      
                                  @endforeach

                                  {{-- @foreach ($myAdditionalDeductionCollection->sortBy('sort_position') as $additionalDeduction)
                                    <td class="text-center align-middle">
                                      {{ $additionalDeduction->amount }}
                                   </td>                   
                                  @endforeach --}}

                                <td class="text-center align-middle">{{ $perUser->total_deductions }}</td>

                                <td class="text-center align-middle">{{ $perUser->net_pay }}</td>
                                
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
