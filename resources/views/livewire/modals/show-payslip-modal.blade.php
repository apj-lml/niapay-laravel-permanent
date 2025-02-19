<div>
  <h4>{{ $user->full_name }}</h4>
    <font size="1.75" face="Cambria" >
                @forelse ($payrollsByYear as $year => $monthPayslips)
                {{-- @dd($data) --}}
                    {{-- SET KEY YEAR --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne{{ $year }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne{{ $year }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne{{ $year }}">
                        {{ $year }} <span class="badge bg-primary rounded-pill ms-1">{{ $monthPayslips->count() }}</span>
                      </button>
                        </h2>
                        <div class="accordion-body">
                        <div id="panelsStayOpen-collapseOne{{ $year }}" class="accordion-collapse collapse @if ($loop->first) show @endif" aria-labelledby="panelsStayOpen-headingOne">
                          <div class="accordion accordion-flush" id="accordionFlushMonths">
                          
                            <ol class="list-group list-group-numbered">
                            @foreach ($monthPayslips as $month => $payslips)
                              <li class="list-group-item d-flex justify-content-between align-items-start">
                                  <div class="ms-2 me-auto">
                                    {{-- @dd(end($payslips)) --}}
                                    <div class="fw-bolder">{{ $month }} - 
                                      <a class="fs-6 fw-lighter"
                                        href="#"
                                        target="_blank"
                                        style="z-index: 999;"
                                        onclick="return false;"
                                    @php
                                        $firstPeriod = \Carbon\Carbon::parse($payslips[0]->period_covered_from)->firstOfMonth()->toDateString();
                                        $lastPeriod = \Carbon\Carbon::parse($payslips[count($payslips) - 1]->period_covered_to)->lastOfMonth()->toDateString();
                                    @endphp
                                      @if(count($payslips)-1 == 0)
                                        wire:click="downloadPayslip('{{ $firstPeriod }}', '{{ $lastPeriod }}')">
                                      @else
                                        wire:click="downloadPayslip('{{ $payslips[0]->period_covered_from }}', '{{ $payslips[count($payslips)-1]->period_covered_to }}')">
                                      @endif
                                        <span style="font-size: 9px;"> Download 1 month payslip</span>
                                      </a>
                                    </div>
                                      @foreach ($payslips as $payslip)
                                      <a href="#" 
                                        target="_blank" 
                                        class="d-block" 
                                        onclick="return false;"
                                        wire:click="downloadPayslip('{{ $payslip->period_covered_from }}', '{{ $payslip->period_covered_to }}')">
                                        {{ $payslip->period_covered }}
                                      </a>
                                      @endforeach
                                  </div>
                                  <span class="badge bg-primary rounded-pill">{{ $payslips->count() }}</span>
                              </li>
                          @endforeach
                        </ol>

                          </div>

                            {{-- @foreach ($payslips as $payslip)
                            <a href="#" wire:click="downloadPayslip({{ $payslip->id }}, {{ $payslip->period_covered_from }}, {{ $payslip->period_covered_to }})">{{ $payslip->period_covered }}</a>
                              
                            @endforeach   --}}

                        </div>
                        </div>

                    </div>                    

                @empty
                <h3 class="text-muted">No data to be shown</h3>
                @endforelse
             
    </font>
      </div>
  