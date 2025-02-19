<div class="animated wow fadeIn" data-wow-delay=".5s">
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="flush-headingTwo">

                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                   @isset($editMode)
                        @if ($editMode)
                            Edit |&nbsp;<a href="#" onclick="checkAccordion(this)"  wire:click="backToAdd(false)"> Back to Add Deduction</a>
                        @else
                            Add Deduction
                        @endif
                   @endisset
                </button>

          </h2>
          <div id="flush-collapseTwo" class="accordion-collapse collapse  show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" wire:ignore.self>
            <div class="accordion-body" >
                <form wire:submit.prevent="addDeduction" class="needs-validation" novalidate >
                    @csrf
                    <div class="row mb-3">
                        {{-- <div class="col-md-8">
                            <div class="form-floating">
                                <select class="form-select @error('deduction') is-invalid @enderror" aria-label="section" wire:model="deduction">
                                    @isset($listOfDeductions)
                                        @foreach ($listOfDeductions as $deduction)
                                            @if ($deduction === reset( $listOfDeductions ))
                                            <option value="{{ $deduction->id }}" selected> <span class="text-muted"> [{{ $deduction->deduction_group }}]</span> - {{ $deduction->description }}</option>
                                            @else
                                            <option value="{{ $deduction->id }}">  <span class="text-muted"> [{{ $deduction->deduction_group }}]</span> - {{ $deduction->description }}</option>  

                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                <label for="floatingSelect">Deductions</label>
                                @error('deduction')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          </div> --}}
                          <div class="col-md-8">
                            <div class="form-floating">
                                <select class="form-select js-choice @error('deduction') is-invalid @enderror" aria-label="section" wire:model="deduction">
                                    @isset($listOfDeductions)
                                        @foreach ($listOfDeductions as $deduction)
                                            @if ($deduction === reset( $listOfDeductions ))
                                            <option value="{{ $deduction->id }}" selected> <span class="text-muted"> [{{ $deduction->deduction_group }}]</span> - {{ $deduction->description }}</option>
                                            @else
                                            <option value="{{ $deduction->id }}">  <span class="text-muted"> [{{ $deduction->deduction_group }}]</span> - {{ $deduction->description }}</option>  

                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                <label for="floatingSelect">Deductions</label>
                                @error('deduction')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          </div>
                        <div class="col-md-4 d-none">
                            <div class="form-floating">
                                <select class="form-select @error('frequency') is-invalid @enderror" aria-label="section" wire:model="frequency">
                                    <option value="1">01-15</option>
                                    <option value="2">16-31</option>
                                    <option value="3" selected>Both</option>
                                </select>
                                <label for="floatingSelect">Frequency</label>
                                @error('frequency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select @error('active_status') is-invalid @enderror" aria-label="section" wire:model="active_status" wire:change="changeStatus()">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <label for="floatingSelect">Status</label>
                                @error('active_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" min="0" step=".01" class="form-control @error('amount') is-invalid @enderror" placeholder="Monthly Rate" wire:model.debounce.500="amount">
                                <label for="monthly_rate">Amount</label>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('remarks') is-invalid @enderror" aria-label="remarks" wire:model="remarks" @if($this->deduction != 27 || ($this->deduction == 27 && $this->active_status == 1)) disabled @endif>
                                    <option value="N/A" selected>N/A</option>
                                    <option value="Indigent family identified by DSWD">Indigent family identified by DSWD</option>
                                    <option value="Beneficiary of 4Ps">Beneficiary of 4Ps</option>
                                    <option value="Senior Citizen">Senior Citizen</option>
                                    <option value="Person with disability">Person with disability</option>
                                    <option value="Others">Others</option>
                                </select>
                                <label for="floatingSelect">Remarks</label>
                                @error('remarks')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="container d-flex align-items-center justify-content-center">
                            {{-- @isset($editMode) --}}
                                @if ($editMode == true)

                                <button type="button" class="btn btn-primary" wire:key="update-btn-{{Str::random(10)}}" wire:click="clickUpdateEmployeeDeduction()">
                                    {{ __('Update Deduction') }}
                                </button>
                                @else
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Deduction') }}
                                </button>
                                @endif
                            {{-- @endisset --}}
                        </div>
                    </div>

                </form>
            </div>
          </div>
        </div>
      </div>
   <table class="table table-striped table-hover table-bordered mt-3 zoomIn" data-wow-delay="0.1s">
    <thead>
      <tr>
        <th scope="col">Type</th>
        <th scope="col">Deduction</th>
        <th scope="col">Amount</th>
        <th scope="col">Status</th>
        <th scope="col">Controls</th>
      </tr>
    </thead>
    <tbody>
        @php
            $totalDeductions = 0.00;
            $totalDeductionsActive = 0.00;
        @endphp

        @if(isset($employee))
            @forelse($employee->employeeDeductions as $deduction)
                <tr class="">
                    <td scope="row" class="text-start">{{ $deduction->deduction_group }}</td>
                    <td scope="row" class="text-start">{{ $deduction->description }}</td>
                    <td scope="row">{{ number_format((float)$deduction->pivot->amount, 2) }}</td>
                    <td scope="row" class="text-center">@if($deduction->pivot->active_status == 1) Active @else Inactive @endif
                        @if($deduction->pivot->active_status == 0 && $deduction->pivot->deduction_id == 27 && ($deduction->pivot->remarks != 'N/A' && $deduction->pivot->remarks != null))
                            <span data-bs-container="body" title="{{ $deduction->pivot->remarks }}" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Right popover">
                                <i class="bi bi-info-circle"></i>
                            </span>
                        @endif
                    </td>
                    {{-- @if ($deduction->pivot->frequency == 1)
                    <td scope="row">01-15</td>
                    @elseif ($deduction->pivot->frequency == 2)
                    <td scope="row">16-31</td>
                    @else
                    <td scope="row">Both</td>
                    @endif --}}
                    <td scope="row" class="text-center">
                        <button class="btn btn-sm btn-outline-secondary" wire:click="myEditMode(true, {{ $deduction->pivot->id }})" onclick="checkAccordion(this);">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteDeductionConfirmation({{ $deduction->pivot->id }}, {{ $deduction->id }}, {{$deduction->pivot->user_id}})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @php
                if ($deduction->pivot->active_status == 1) {
                    $totalDeductionsActive += bcdiv($deduction->pivot->amount, 1, 2);
                }
                    $totalDeductions += bcdiv($deduction->pivot->amount, 1, 2);
                @endphp
            @empty
            <tr class="">
                <td colspan="5" class="text-center">
                    <h3> No Deductions </h3>
                </td>
            </tr>
            @endforelse
        @endif
    </tbody>
  </table>
  <div class="fw-bold">
    TOTAL DEDUCTIONS : <span class="text-danger">{{ number_format((float)$totalDeductions, 2) }}</span>
  </div>
  <div class="fw-bold mb-4">
    TOTAL ACTIVE DEDUCTIONS : <span class="text-danger">{{ number_format((float)$totalDeductionsActive, 2) }}</span>
  </div>
</div>
@push('alerts')

<script type="text/javascript">
 

function checkAccordion(btn){
    var element = document.getElementById("flush-collapseTwo");
    if(!element.classList.contains('show')){
        var myCollapse = element;
        var bsCollapse = new bootstrap.Collapse(myCollapse, {
            toggle: true
        });
    }
}

window.addEventListener('deleteDeductionConfirmation', event => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteDeduction', event.detail.pivotId, event.detail.deductionId, event.detail.userId);
                    // Swal.fire(
                    // 'Deleted!',
                    // 'Deduction has been deleted--.',
                    // 'success'
                    // )
                }
            })
        })

</script>

@endpush
