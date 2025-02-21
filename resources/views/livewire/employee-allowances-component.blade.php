<div class="animated wow fadeIn" data-wow-delay=".5s">
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="flush-headingTwo">

                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                   @isset($editMode)
                        @if ($editMode)
                            Edit |&nbsp;<a href="#" onclick="checkAccordion(this)"  wire:click="backToAdd(false)"> Back to Add Allowance</a>
                        @else
                            Add Allowance
                        @endif
                   @endisset
                </button>

          </h2>
          <div id="flush-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" wire:ignore.self>
            <div class="accordion-body" >
                <form wire:submit.prevent="addAllowance" class="needs-validation" novalidate >
                    @csrf
                    <div class="row mb-3">
                        {{-- <div class="col-md-8">
                            <div class="form-floating">
                                <select class="form-select @error('allowance') is-invalid @enderror" aria-label="section" wire:model="allowance">
                                    @isset($listOfAllowances)
                                        @foreach ($listOfAllowances as $allowance)
                                            @if ($allowance === reset( $listOfAllowances ))
                                            <option value="{{ $allowance->id }}" selected> <span class="text-muted"> [{{ $allowance->allowance_group }}]</span> - {{ $allowance->description }}</option>
                                            @else
                                            <option value="{{ $allowance->id }}">  <span class="text-muted"> [{{ $allowance->allowance_group }}]</span> - {{ $allowance->description }}</option>  

                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                <label for="floatingSelect">Allowances</label>
                                @error('allowance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          </div> --}}
                          <div class="col-md-8">
                            <div class="form-floating">
                                <select class="form-select @error('allowance') is-invalid @enderror" aria-label="section" wire:model="allowance">
                                    @isset($listOfAllowances)
                                        @foreach ($listOfAllowances as $allowance)
                                            @if ($allowance === reset( $listOfAllowances ))
                                            <option value="{{ $allowance->id }}" selected> <span class="text-muted"> [{{ $allowance->allowance_group }}]</span> - {{ $allowance->description }}</option>
                                            @else
                                            <option value="{{ $allowance->id }}">  <span class="text-muted"> [{{ $allowance->allowance_group }}]</span> - {{ $allowance->description }}</option>  

                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                <label for="floatingSelect">Allowances</label>
                                @error('allowance')
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
                                <select class="form-select @error('active_status') is-invalid @enderror" aria-label="section" wire:model="active_status">
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
                        <div class="col-md-12">
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
                    </div>

                    <div class="row">
                        <div class="container d-flex align-items-center justify-content-center">
                            {{-- @isset($editMode) --}}
                                @if ($editMode == true)

                                <button type="button" class="btn btn-primary" wire:key="update-btn-{{Str::random(10)}}" wire:click="clickUpdateEmployeeAllowance()">
                                    {{ __('Update Allowance') }}
                                </button>
                                @else
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Allowance') }}
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
        <th scope="col">Allowance</th>
        <th scope="col">Amount</th>
        <th scope="col">Status</th>
        <th scope="col">Controls</th>
      </tr>
    </thead>
    <tbody>
        @php
            $totalAllowances = 0.00;
            $totalAllowancesActive = 0.00;
        @endphp

        @if(isset($employee))
            @forelse($employee->employeeAllowances as $allowance)
                <tr class="">
                    <td scope="row" class="text-start">{{ $allowance->allowance_group }}</td>
                    <td scope="row" class="text-start">{{ $allowance->description }}</td>
                    <td scope="row">{{ number_format((float)$allowance->pivot->amount, 2) }}</td>
                    <td scope="row" class="text-center">@if($allowance->pivot->active_status == 1) Active @else Inactive @endif
                    </td>
                    {{-- @if ($allowance->pivot->frequency == 1)
                    <td scope="row">01-15</td>
                    @elseif ($allowance->pivot->frequency == 2)
                    <td scope="row">16-31</td>
                    @else
                    <td scope="row">Both</td>
                    @endif --}}
                    <td scope="row" class="text-center">
                        <button class="btn btn-sm btn-outline-secondary" wire:click="myEditMode(true, {{ $allowance->pivot->id }})" onclick="checkAccordion(this);">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAllowanceConfirmation({{ $allowance->pivot->id }}, {{ $allowance->id }}, {{$allowance->pivot->user_id}})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @php
                if ($allowance->pivot->active_status == 1) {
                    $totalAllowancesActive += bcdiv($allowance->pivot->amount, 1, 2);
                }
                    $totalAllowances += bcdiv($allowance->pivot->amount, 1, 2);
                @endphp
            @empty
            <tr class="">
                <td colspan="5" class="text-center">
                    <h3> No Allowances </h3>
                </td>
            </tr>
            @endforelse
        @endif
    </tbody>
  </table>
  <div class="fw-bold">
    TOTAL ALLOWANCES : <span class="text-danger">{{ number_format((float)$totalAllowances, 2) }}</span>
  </div>
  <div class="fw-bold mb-4">
    TOTAL ACTIVE ALLOWANCES : <span class="text-danger">{{ number_format((float)$totalAllowancesActive, 2) }}</span>
  </div>
</div>
@push('alerts')

<script type="text/javascript">
 
const element = document.querySelector('.js-choice');
const choices = new Choices(element, {
    allowHTML : true
});

function checkAccordion(btn){
    var element = document.getElementById("flush-collapseTwo");
    if(!element.classList.contains('show')){
        var myCollapse = element;
        var bsCollapse = new bootstrap.Collapse(myCollapse, {
            toggle: true
        });
    }
}
window.addEventListener('deleteAllowanceConfirmation', event => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteAllowance', event.detail.pivotId, event.detail.allowanceId, event.detail.userId);
                }
            })
        })

</script>

@endpush
