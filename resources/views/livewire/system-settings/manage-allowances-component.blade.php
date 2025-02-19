<div class="p-2">
    <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
        <div class="card-header bg-primary text-white fw-1"><i class="bi bi-house-gear-fill"></i> Manage Allowances</div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <button class="btn btn-primary rounded-pill text-white py-2 px-4 mb-3 shadow-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#addAllowanceModal" 
                    wire:click="showAddAllowanceModal()">
                    <i class="bi bi-plus-lg"></i> Add Allowances
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
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Account Title</th>
                        <th scope="col">UACS Code (LFPs)</th>
                        <th scope="col">UACS Code (COB)</th>
                        {{-- <th scope="col">Deduction For</th> --}}
                        <th scope="col">Status</th>
                        <th scope="col">Controls</th>
                        {{-- <th scope="col">Role</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @isset($listOfAllowances)
                        @foreach ($listOfAllowances as $allowance)
                        <tr>
                          <td>
                            {{ $allowance->allowance_group }}
                          </td>
                          <td>
                            {{ $allowance->description }}
                          </td>
                          <td>
                            {{ $allowance->account_title }}
                          </td>
                          <td>
                            {{ $allowance->uacs_code_lfps }}
                          </td>
                          <td>
                            {{ $allowance->uacs_code_cob }}
                          </td>
                          {{-- <td>
                            {{ $allowance->allowance_for }}
                          </td> --}}
                          <td>
                            {{ $allowance->status }}
                          </td>
                          <td>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addAllowanceModal" wire:click="showEditAllowanceModal({{ $allowance->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="deleteAllowanceConfirmation({{ $allowance->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                          </td>
                        </tr>
                        @endforeach
                      @endisset
                    </tbody>
                  </table>
            </div>
        
              {{ $listOfAllowances->links() }}

        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">

    window.addEventListener('deleteAllowanceConfirmation', event => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
              Livewire.emit('deleteAllowance', event.detail.allowanceId);
          }
      })
    });

    window.addEventListener('invalidDeletion', event => {
      console.log(event.detail.constraints);
      Swal.fire({
      title: '<strong>Delete <u>Failed</u></strong>',
      icon: 'warning',
      html:
        `Allowance can not be <b>deleted</b> as it is currently assigned to ${event.detail.constraints.user.name}.`,
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