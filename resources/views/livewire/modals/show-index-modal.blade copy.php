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

                {{-- Assuming you have passed the JSON data to the view as $jsonData --}}
                @php
                  // Decode the JSON data to convert it into a PHP array
                  $data = json_decode($jsonOutput, true);
                  // dd($data)
                @endphp

                @isset($data)
                  @foreach ($data as $year => $yearAndPeriods)
                  {{-- SET KEY YEAR --}}
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="panelsStayOpen-headingOne{{ $yearAndPeriods['period_covered_from'] }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne{{ $year }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne{{ $year }}">
                           {{ date('Y', strtotime( $yearAndPeriods['period_covered_from'] )) }}
                        </button>
                      </h2>
                      <div id="panelsStayOpen-collapseOne{{ $year }}" class="accordion-collapse collapse @if ($loop->first) show @endif" aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col" rowspan="3" class="text-center align-middle">PERIOD COVERED</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">NO. OF DAYS WORKED</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">DAILY RATE</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">BASIC PAY</th>
                                <th scope="col" colspan="{{ ($numberOfDedCols) }}" class="text-center align-middle">D E D U C T I O N S</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">TOTAL DEDUCTIONS</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">NET PAY</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">FUNDING CHARGES</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">DV/PAYROLL NO.</th>
                                <th scope="col" rowspan="3" class="text-center align-middle">REMARKS</th>
                              </tr> 
                              <tr>

                              <th @if(count($columDeductions['tax']) > 1) rowspan="1" colspan="{{ count($columDeductions['tax']) }}" @else rowspan="2" @endif class="text-center align-middle">
                                  WHT
                              </th>

                                <th rowspan="1" colspan="{{ count($columDeductions['hdmf']) }}" class="text-center align-middle">
                                  HDMF
                                </th>

                                <th rowspan="2" class="text-center align-middle">
                                  PHIC
                                </th>

                                <th rowspan="2" class="text-center align-middle">
                                  COOP LOAN
                                </th>

                                <th rowspan="2" class="text-center align-middle">
                                  DISALLOWANCE
                                </th>

                            </tr>

                            <tr>
                              @if(count($columDeductions['tax']) > 1)
                                <th class="text-center align-middle">
                                  PREMIUM
                                </th>
                                <th class="text-center align-middle">
                                  PREMIUM
                                </th>
                              @endif
                              <th class="text-center align-middle">
                                PREMIUM
                              </th>
                              <th class="text-center align-middle">
                                M2
                              </th>
                              <th class="text-center align-middle">
                                MPLOAN
                              </th>
                              <th class="text-center align-middle">
                                CAL
                              </th>
                          </tr>

                            </thead>

                            <tbody>





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
              <button type="button" class="btn btn-primary" wire:click="downloadModifiedTemplate">Download</button>
            </div>
          </div>
        </div>
      </div>
  </font>
    </div>
