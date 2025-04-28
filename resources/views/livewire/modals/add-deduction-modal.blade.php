
<div>
    <div wire:ignore.self class="modal fade" id="addDeductionModal" tabindex="-1" aria-labelledby="addDeductionModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
  
            <h5 class="modal-title" id="addDeductionModalLabel">Deduction</h5>
  
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent = "addDeductionForm" id="submitAddDeductionForm">
                <input type="hidden" wire:model="deductionId">
            
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('deductionDescription') is-invalid @enderror" placeholder="Deduction Description" wire:model="deductionDescription">
                      <label for="floatingInput">Description</label>
                        @error('deductionDescription')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('accountTitle') is-invalid @enderror" placeholder="Account Title" wire:model="accountTitle">
                      <label for="floatingInput">Account Title</label>
                        @error('accountTitle')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('uacsLfps') is-invalid @enderror" placeholder="UACS Code (LFPS)" wire:model="uacsLfps">
                      <label for="floatingInput">UACS Code (LFPS)</label>
                        @error('uacsLfps')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('uacsCob') is-invalid @enderror" placeholder="UACS Code (COB/CARP)" wire:model="uacsCob">
                      <label for="floatingInput">UACS Code (COB/CARP)</label>
                        @error('uacsCob')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
          
                    <div class="form-floating mb-3">
                      <select class="form-select @error('deductionGroup') is-invalid @enderror" aria-label="section" wire:model="deductionGroup">
                          <option value="OTHER" selected>N/A</option>
                          <option value="HDMF">HDMF</option>
                          <option value="PHIC">PHIC</option>
                          <option value="COOP">COOP LOAN</option>
                          <option value="DISALLOWANCE">DISALLOWANCE</option>
                          <option value="TAX">TAX</option>
                          <option value="GSIS">GSIS</option>
                      </select>
                      <label for="floatingSelect">Group</label>
                      @error('deductionGroup')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="form-floating mb-3">
                    <select class="form-select @error('status') is-invalid @enderror" aria-label="status" wire:model="status">
                        {{-- <option value="" selected>N/A</option> --}}
                        <option value="ACTIVE" selected>ACTIVE</option>
                        <option value="INACTIVE">INACTIVE</option>
                    </select>
                    <label for="floatingSelect">Status</label>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                  <div class="form-floating mb-3 d-none">
                    <select class="form-select @error('deductionFor') is-invalid @enderror" aria-label="deductionFor" wire:model="deductionFor">
                        {{-- <option value="" hidden>-</option> --}}
                        {{-- <option value="" selected>N/A</option> --}}
                        {{-- <option value="both" selected>ALL</option> --}}
                        {{-- <option value="monthly">PERMANENT, COTERMINOUS, CASUAL</option> --}}
                        <option value="monthly" selected>PERMANENT, COTERMINOUS</option>
                    </select>
                    <label for="floatingSelect">Deduction For</label>
                    @error('deductionFor')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                  <div class="form-floating mb-3 d-none">
                    <input type="text" class="form-control @error('sortPosition') is-invalid @enderror" placeholder="Sort Position" wire:model="sortPosition">
                    <label for="floatingInput">Sort Position</label>
                      @error('sortPosition')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
  

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="submitAddDeductionForm" wire:key="{{ Str::random(10) }}">Save</button>
          </form>
          </div>
        </div>
      </div>
    </div>
    

</div>
  
