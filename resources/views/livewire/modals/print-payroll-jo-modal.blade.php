<div>
    <div class="modal fade" id="printPayrollJoModal" tabindex="-1" aria-labelledby="printPayrollJoModalLabel" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
  
            <h5 class="modal-title" id="printPayrollJoModalLabel">Print Payroll</h5>
  
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent = "addAllowanceForm" id="submitAddAllowanceForm">
                <input type="hidden" wire:model="allowanceId">
                {{-- THIS IS NOT SHOWING IN THE DISPLAY --}}
                <div class="col-md-12 mb-2 d-none">
                  <div class="form-floating">
                      <select class="form-select @error('sectionOrFund') is-invalid @enderror" aria-label="sectionOrFund" wire:model="sectionOrFund">
                        <option value="perFund">Print Per Fund</option>
                        <option value="perSection">Print Per Section</option>
                      </select>
                      <label for="section">Select Option</label>
                      @error('section')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
              </div>
              {{-- THIS IS NOT SHOWING IN THE DISPLAY --}}

              <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('section') is-invalid @enderror" aria-label="section" wire:model="fund">
                      <option value="0">All</option>
                      @foreach($listOfFunds as $fund)
                        <option value="{{ $fund->id }}">{{ $fund->fund_description }}</option>
                      @endforeach
                    </select>
                    <label for="fund">Select Fund</label>
                    @error('fund')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
              </div> 

              {{-- @if ($this->sectionOrFund != "perFund") --}}
                <div class="col-md-12">
                  <div class="form-floating">
                      <select class="form-select @error('section') is-invalid @enderror" aria-label="section" wire:model="section">
                        <option value="0">All</option>
                        @foreach($listOfSections as $office => $section)
                          <option value="{{ $office }}">{{ $office }}</option>
                        @endforeach
                      </select>
                      <label for="section">Select Section</label>
                      @error('section')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                </div> 
              {{-- @else --}}

              {{-- @endif --}}

              </form>
              @if ($isProcessed)
                <div class="alert alert-warning mt-3" role="alert">
                  <i class="bi bi-info-circle-fill"></i> <b>CAUTION:</b> Continuing with this action will replace the previously processed payroll. Are you sure you want to proceed?
                </div>
              @endif
          </div>
          <div class="modal-footer">
            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
            <button type="button" class="btn btn-primary" wire:click="print()" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#printPreviewJoPayrollModal"><i class="bi bi-printer"></i> Proceed</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
  <script type="text/javascript">

  </script>
  @endpush