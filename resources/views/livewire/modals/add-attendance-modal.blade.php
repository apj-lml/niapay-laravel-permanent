

  <!-- Modal -->

    <div class="modal fade " id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addAttendanceModalLabel">Add Bulk No. of Days </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="addAttendance" class="needs-validation" novalidate id="addAttendance">
                    <div class="mb-3">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                    <label for="isLessFifteen" class="form-label">Payroll Frequency</label>
    
                                    <select class="form-select @error('isLessFifteen') is-invalid @enderror" wire:model="isLessFifteen" aria-label="isLessFifteen" disabled>
                                        <option value="full_month">1 month</option>
                                        <option value="less_fifteen_first_half">Less than 15 (First Half)</option>
                                        <option value="less_fifteen_second_half">Less than 15 (Second Half)</option>
                                    </select>
                                    @error('isLessFifteen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="startDate" wire:model.debounce.500ms="startDate" aria-describedby="helpStartDate" readonly>
                                @error('startDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                {{-- <div id="helpStartDate" class="form-text">We'll never share your email with anyone else.</div> --}}
                            </div>
                            <div class="col-sm-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('endDate') is-invalid @enderror" id="endDate" wire:model.debounce.500ms="endDate" aria-describedby="helpEndDate" onchange="getDaysRendered()" readonly>
                                @error('endDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                {{-- <div id="helpStartDate" class="form-text">We'll never share your email with anyone else.</div> --}}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="daysRendered" class="form-label">Days Rendered (Full Month)</label>
                        <input type="text" class="form-control @error('endDate') is-invalid @enderror" id="daysRendered" wire:model.debounce.500ms="daysRendered" aria-describedby="">
                        @error('daysRendered')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstHalf" class="form-label">Days Rendered (01-15)</label>
                                <input type="text" class="form-control" id="firstHalf" wire:model.debounce.500ms="firstHalf" aria-describedby="" @if($isLessFifteen != 'full_month' && $isLessFifteen != 'less_fifteen_first_half') disabled @endif>
                                {{-- <div id="helpStartDate" class="form-text">We'll never share your email with anyone else.</div> --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="secondHalf" class="form-label">Days Rendered (16-31)</label>
                                <input type="text" class="form-control" id="secondHalf" wire:model.debounce.500ms="secondHalf" aria-describedby="" @if($isLessFifteen != 'full_month' && $isLessFifteen != 'less_fifteen_second_half') disabled @endif>
                                {{-- <div id="helpStartDate" class="form-text">We'll never share your email with anyone else.</div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 d-none">
                        <div class="col-md-12">

                            {{ $employmentStatus }}
                                <label for="employmentStatus" class="form-label">Employment Status</label>

                                <select class="form-select @error('employmentStatus') is-invalid @enderror" wire:model="employmentStatus" aria-label="employmentStatus">
                                    <option value="PERMANENT" selected>PERMANENT</option>
                                    <option value="COTERMINOUS">COTERMINOUS</option>

                                    {{-- <option value="PERMANENT">PERMANENT</option>
                                    <option value="TEMPORARY">TEMPORARY</option>
                                    <option value="CASUAL">CASUAL</option>
                                    <option value="COTERMINOUS">COTERMINOUS</option>
                                    <option value="PERMANENT">PERMANENT</option> --}}
                                </select>
                                @error('employmentStatus')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                    </div>

                    {{-- <button type="submit" class="btn btn-primary" form="">Submit</button> --}}
                </form>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                    <button type="submit" class="btn btn-primary" form="addAttendance"><i class="bi bi-plus"></i> Add</button>
                    {{-- <button class="btn btn-secondary" wire:click="configureAttendance" data-bs-target="#configureAttendanceModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="bi bi-sliders"></i> Configure</button> --}}
                </div>
            </div>
        </div>
  </div>
