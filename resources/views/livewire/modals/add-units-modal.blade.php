<div>
  <div wire:ignore.self class="modal fade" id="addUnitsModal" tabindex="-1" aria-labelledby="addUnitsModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">

          <h5 class="modal-title" id="addUnitsModalLabel">Units</h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent = "addUnitForm" id="submitAddUnitForm">
              <input type="hidden" wire:model="unitId">
          
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('unitCode') is-invalid @enderror" placeholder="Unit Code" wire:model="unitCode">
                    <label for="floatingInput">Unit Code</label>
                      @error('unitCode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('unitDescription') is-invalid @enderror" placeholder="Unit Description" wire:model="unitDescription">
                    <label for="floatingInput">Description</label>
                      @error('unitDescription')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
                  <div class="form-floating">
                    <select class="form-select @error('agencySectionId') is-invalid @enderror" aria-label="section" wire:model="agencySectionId">
                        @foreach ($listOfSections as $section)
                            <option value="{{ $section->id }}">{{ $section->section_description }}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Section</label>
                    @error('agencySectionId')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="submitAddUnitForm" wire:key="{{ Str::random(10) }}">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>