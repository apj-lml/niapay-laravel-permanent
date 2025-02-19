<div>
  <font size="1.75" face="Cambria" >

    <div class="modal fade" id="showIndexModal" tabindex="-1" aria-labelledby="showIndexModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="showIndexModalLabel">Index</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              @forelse ($payrollsByYear as $year => $monthPayslips)
              {{-- @dd($data) --}}
                  {{-- SET KEY YEAR --}}
                  <div class="accordion-item">
                      <h2 class="accordion-header" id="panelsStayOpen-headingOne{{ $year }}">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne{{ $year }}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne{{ $year }}">
                      {{ $year }}
                    </button>
                      </h2>
                      <div class="accordion-body">
                      <div id="panelsStayOpen-collapseOne{{ $year }}" class="accordion-collapse collapse @if ($loop->first) show @endif" aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion accordion-flush" id="accordionFlushMonths">
                        @foreach ($monthPayslips as $month => $payrollIndices)
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading{{ $year }}{{ $month }}">
                              {{-- @dd($monthPayslips) --}}
                              <button class="accordion-button collapsed fs-6 fw-lighter" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $year }}{{ $month }}" aria-expanded="false" aria-controls="flush-collapseOne">
                                {{ $month }}
                              </button>
                            </h2>
                            <div id="flush-collapse{{ $year }}{{ $month }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushMonths">
                                <div class="accordion-body">
                                  @foreach ($payrollIndices as $payrollIndex)
                                      <p><a href="#" wire:click="downloadPayslip({{ $payrollIndex->id }}, {{ $payrollIndex->period_covered_from }}, {{ $payrollIndex->period_covered_to }})">{{ $payrollIndex->period_covered }}</a></p>
                                  @endforeach
                                </div>
                            </div>
                          </div>
                        @endforeach
                        </div>


                          {{-- @foreach ($payrollIndices as $payrollIndex)
                          <a href="#" wire:click="downloadPayslip({{ $payrollIndex->id }}, {{ $payrollIndex->period_covered_from }}, {{ $payrollIndex->period_covered_to }})">{{ $payrollIndex->period_covered }}</a>
                            
                          @endforeach   --}}


                      </div>
                      </div>

                  </div>                    

              @empty
              <h3 class="text-muted">No data to be shown</h3>
              @endforelse
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              {{-- <button type="button" class="btn btn-primary" wire:click="downloadModifiedTemplate">Download</button> --}}
            </div>
          </div>
        </div>
      </div>
  </font>
    </div>
