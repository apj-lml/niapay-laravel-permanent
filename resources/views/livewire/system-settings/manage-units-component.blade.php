<div class="p-2">
    <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
        <div class="card-header bg-primary text-white fw-1"><i class="bi bi-house-gear-fill"></i> Manage Units</div>

        <div class="card-body">
        <button class="btn btn-primary rounded-pill text-white py-2 px-4 mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUnitsModal"><i class="bi bi-plus-lg"></i> Add Units</button>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Unit Code</th>
                        <th scope="col">Unit Description</th>
                        <th scope="col">Controls</th>
                        {{-- <th scope="col">Role</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @isset($listOfUnits)
                        @foreach ($listOfUnits as $unit)
                        <tr>
                          <td>
                            {{ $unit->unit_code }}
                          </td>
                          <td>
                            {{ $unit->unit_description }}
                          </td>
                          <td>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addUnitsModal" wire:click="showEditUnitModal({{ $unit->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="deleteUnitConfirmation({{ $unit->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                          </td>
                        </tr>
                        @endforeach
                      @endisset
                    </tbody>
                  </table>
            </div>
        
              {{ $listOfUnits->links() }}

        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">

    window.addEventListener('deleteUnitConfirmation', event => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
              Livewire.emit('deleteUnit', event.detail.unitId);
          }
      })
    });

    window.addEventListener('invalidDeletion', event => {
      console.log(event.detail.constraints);
      Swal.fire({
      title: '<strong>Deletion <u>Failed</u></strong>',
      icon: 'warning',
      html:
        `You can not <b>delete</b> this unit because <br> ${event.detail.constraints.name} is currently in this unit`,
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Great!',
      confirmButtonAriaLabel: 'Thumbs up, great!',
      cancelButtonText:
        '<i class="fa fa-thumbs-down"></i>',
      cancelButtonAriaLabel: 'Thumbs down'
    })
    });

</script>
  
@endpush