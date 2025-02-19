<div class="p-2">
    <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
        <div class="card-header bg-primary text-white fw-1"><i class="bi bi-house-gear-fill"></i> Manage Deductions</div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <button class="btn btn-primary rounded-pill text-white py-2 px-4 mb-3 shadow-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#addDeductionModal"
                  wire:click="showAddDeductionModal()">
                  <i class="bi bi-plus-lg"></i> Add Deductions
              </button>
            </div>
            <div class="col-md-6">
              <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon1" wire:model="searchVal">
              </div>
            </div>
          </div>
          

          
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="deductionTable">
                    <thead>
                      <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Account Title</th>
                        <th scope="col">UACS Code (LFPs)</th>
                        <th scope="col">UACS Code (COB)</th>
                        {{-- <th scope="col">Deduction For</th> --}}
                        <th scope="col">Status 
                          {{-- @livewire('deduction-status-component', ['status'=> 'ACTIVE', 'deduction_id'=>''], key('allstatus')) --}}
                          {{-- @livewire('deduction-status-component', ['status' => 'ACTIVE', 'deduction_id' => null], key('all-status-toggle')) --}}
                        </th>
                        <th scope="col">Controls</th>
                        {{-- <th scope="col">Role</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @isset($listOfDeductions)
                        @foreach ($listOfDeductions as $deduction)
                        <tr>
                          <td>
                            {{ $deduction->deduction_group }}
                          </td>
                          <td>
                            {{ $deduction->description }}
                          </td>
                          <td>
                            {{ $deduction->account_title }}
                          </td>
                          <td>
                            {{ $deduction->uacs_code_lfps }}
                          </td>
                          <td>
                            {{ $deduction->uacs_code_cob }}
                          </td>
                          {{-- <td>
                            {{ $deduction->deduction_for }}
                          </td> --}}
                          <td class="text-center align-middle">
                             @livewire('deduction-status-component', ['status'=> $deduction->status, 'deduction_id'=>$deduction->id], key('deduction-' . $deduction->id))
                          </td>

                          <td>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addDeductionModal" wire:click="showEditDeductionModal({{ $deduction->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="deleteDeductionConfirmation({{ $deduction->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                          </td>
                        </tr>
                        @endforeach
                      @endisset
                    </tbody>
                  </table>
            </div>
        
              {{ $listOfDeductions->links() }}

        </div>
    </div>
</div>

@push('scripts')


<script type="text/javascript">

// let deductionTable = new DataTable('#deductionTable')

    window.addEventListener('deleteDeductionConfirmation', event => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
              Livewire.emit('deleteDeduction', event.detail.deductionId);
          }
      })
    });

    window.addEventListener('invalidDeletion', event => {
      console.log(event.detail.constraints);
      Swal.fire({
      title: '<strong>Deletion <u>Failed</u></strong>',
      icon: 'warning',
      html:
        `Deduction can not be <b>deleted</b> as it is currently assigned to ${event.detail.constraints.user.name}.`,
      showCloseButton: true,
      showCancelButton: false,
      focusConfirm: false,
      confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Great!',
      confirmButtonAriaLabel: 'Okay!',
    })
    });

</script>
  
@endpush