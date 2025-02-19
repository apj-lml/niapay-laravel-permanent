

<div>
    <div wire:ignore.self class="modal fade" id="addFundModal" tabindex="-1" aria-labelledby="addFundModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
  
            <h5 class="modal-title" id="addFundModalLabel">Fund</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent="addFundForm" id="submitAddFundForm">
                <input type="hidden" wire:model="fundId">
            
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('fundDescription') is-invalid @enderror" placeholder="Fund" wire:model="fundDescription">
                      <label for="floatingInput">Fund</label>
                        @error('fundDescription')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('fundObligation') is-invalid @enderror" placeholder="Fund" wire:model="fundObligation">
                      <label for="floatingInput">Fund Obligation Description</label>
                        @error('fundObligation')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('fundUacs') is-invalid @enderror" placeholder="Fund" wire:model="fundUacs">
                      <label for="floatingInput">Fund UACS Code</label>
                        @error('fundUacs')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
  
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"  form="submitAddFundForm" wire:key="{{ Str::random(10) }}">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>