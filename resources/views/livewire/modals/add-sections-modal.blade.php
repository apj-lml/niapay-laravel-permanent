
<div>
    <div wire:ignore.self class="modal fade" id="addSectionsModal" tabindex="-1" aria-labelledby="addSectionsModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
  
            <h5 class="modal-title" id="addSectionsModalLabel">Add Section</h5>
  
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent="addSectionForm" id="submitAddSectionForm">
                <input type="hidden" wire:model="sectionId">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="newOffice" wire:model="newOffice">
                  <label class="form-check-label" for="newOffice">Create new office?</label>
                </div>
                @if($newOffice)
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('office') is-invalid @enderror" placeholder="Office" wire:model="office">
                    <label for="floatingInput">Office</label>
                      @error('office')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
                @else
                  <div class="form-floating mb-3">
                    <select class="form-select @error('office') is-invalid @enderror" aria-label="office" wire:model="office">
                      @foreach ($listOfOffices as $office)
                          <option value="{{ $office->office }}">{{ $office->office }}</option>
                      @endforeach
                    </select>
                    <label for="floatingSelect">Office</label>
                    @error('office')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              @endif
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('sectionCode') is-invalid @enderror" placeholder="Section Code" wire:model="sectionCode">
                      <label for="floatingInput">Section Code</label>
                        @error('sectionCode')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control @error('sectionDescription') is-invalid @enderror" placeholder="Section Description" wire:model="sectionDescription">
                      <label for="floatingInput">Description</label>
                        @error('sectionDescription')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="submitAddSectionForm" wire:key="{{ Str::random(10) }}">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>