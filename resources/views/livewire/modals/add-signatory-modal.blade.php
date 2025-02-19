

<div>

    <div wire:ignore.self class="modal fade" id="addSignatoryModal" tabindex="-1" aria-labelledby="addSignatoryModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
  
            <h5 class="modal-title" id="addSignatoryModalLabel">Signatory</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent="addSignatoryForm" id="submitAddSignatoryForm">
                <input type="hidden" wire:model="signatoryId">

                <div class="form-floating mb-3">
                  <select class="form-select @error('type') is-invalid @enderror" aria-label="signatoryDocu" wire:model="signatoryDocu">
                    <option value="wages">Wages</option>
                    <option value="yeb">Year-end Bonus & Cash Gift</option>
                  </select>
                  <label for="floatingSelect">Payroll Type</label>
                  @error('type')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
                <div class="form-floating mb-3">
                  <select class="form-select @error('type') is-invalid @enderror" aria-label="signatory" wire:model="type">
                    @if($signatoryDocu == "wages")
                      <option value="Box A [Preparer]">Box A [Preparer]</option>
                      <option value="Box B [Section Chief Concerned]">Box B [Section Chief Concerned]</option>
                      <option value="Box C [Finance Unit Head]">Box C [Finance Unit Head]</option>
                      <option value="Box D [Approver]">Box D [Approver]</option>
                      <option value="Box E [Certified]">Box E [Certified]</option>
                    @elseif($signatoryDocu == "yeb")
                      <option value="Box A [Preparer]">Box A [Preparer]</option>
                      <option value="Box B [Certified]">Box B [Certified]</option>
                      <option value="Box C [Approved for Payment]">Box C [Approved for Payment]</option>
                      <option value="Box D [Certified]">Box D [Certified]</option>
                    @endif
                  </select>
                  <label for="floatingSelect">Type</label>
                  @error('type')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>

              <div class="form-floating mb-3">
                <input type="text" class="form-control @error('signatoryName') is-invalid @enderror" placeholder="Name" wire:model="signatoryName">
                <label for="floatingInput">Name of Signatory</label>
                  @error('signatoryName')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control @error('position') is-invalid @enderror" placeholder="Position" wire:model="position">
                <label for="floatingInput">Position Title</label>
                  @error('position')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </div>

              @if($signatoryDocu == "wages")

              <div class="form-floating mb-3">
                <select class="form-select @error('section') is-invalid @enderror" aria-label="section" wire:model="section">
                  @foreach ($listOfSections as $section)
                    <option value="{{ $section->id }}">{{ $section->section_description }}</option>
                  @endforeach
                </select>
                <label for="floatingSelect">Section</label>
                @error('section')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
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

              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"  form="submitAddSignatoryForm" wire:key="{{ Str::random(10) }}">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>