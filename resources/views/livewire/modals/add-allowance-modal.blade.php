<div>
    <div wire:ignore.self class="modal fade" id="addAllowanceModal" tabindex="-1" aria-labelledby="addAllowanceModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
  
            <h5 class="modal-title" id="addAllowanceModalLabel">Allowance</h5>
  
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent = "addAllowanceForm" id="submitAddAllowanceForm">
                <input type="hidden" wire:model="allowanceId">
            
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('allowanceDescription') is-invalid @enderror" placeholder="Allowance Description" wire:model="allowanceDescription">
                      <label for="floatingInput">Description</label>
                        @error('allowanceDescription')
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
                      <select class="form-select @error('allowanceGroup') is-invalid @enderror" aria-label="section" wire:model="allowanceGroup">
                          <option value="" selected>N/A</option>
                          <option value="CHILD">CHILD</option>
                          <option value="MEAL">MEAL</option>
                          <option value="MEDICAL">MEDICAL</option>
                          <option value="PERA">PERA</option>
                      </select>
                      <label for="floatingSelect">Group</label>
                      @error('allowanceGroup')
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
                    <select class="form-select @error('allowanceFor') is-invalid @enderror" aria-label="allowanceFor" wire:model="allowanceFor">
                        <option value="" hidden>-</option>
                        {{-- <option value="" selected>N/A</option> --}}
                        <option value="both" selected>BOTH</option>
                        <option value="monthly">MONTHLY EMPLOYEES</option>
                        <option value="daily">DAILY EMPLOYEES</option>
                    </select>
                    <label for="floatingSelect">Allowance For</label>
                    @error('allowanceFor')
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
  
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="submitAddAllowanceForm" wire:key="{{ Str::random(10) }}">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>