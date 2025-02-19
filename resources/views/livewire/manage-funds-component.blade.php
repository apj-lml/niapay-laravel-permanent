<div class="p-2">
  <button class="btn btn-primary rounded-pill text-white py-2 px-4 mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFundModal" wire:click="showAddFundModal()"><i class="bi bi-plus-lg"></i> Add Fund</button>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                {{-- <th scope="col">ID</th> --}}
                <th scope="col">Fund Description</th>
                <th scope="col">Fund Obligation</th>
                <th scope="col">Fund UACS Code</th>
                <th scope="col">Controls</th>
                {{-- <th scope="col">Role</th> --}}
              </tr>
            </thead>
            <tbody>
              @isset($listOfFunds)
                @foreach ($listOfFunds as $fund)
                <tr>
                  {{-- <td>
                    {{ $fund->id }}
                  </td> --}}
                  <td>
                    {{ $fund->fund_description }}
                  </td>
                  <td>
                    {{ $fund->fund_obligation_description }}
                  </td>
                  <td>
                    {{ $fund->fund_uacs_code }}
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addFundModal" wire:click="showEditFundModal({{ $fund->id }})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="deleteFundConfirmation({{ $fund->id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              @endisset
            </tbody>
          </table>
    </div>

      {{ $listOfFunds->links() }}


</div>

@push('scripts')
<script type="text/javascript">

    window.addEventListener('deleteFundConfirmation', event => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
              Livewire.emit('deleteFund', event.detail.fundId);
          }
      })
    });

    window.addEventListener('invalidDeletion', event => {
      Swal.fire({
      title: '<strong>Delete <u>Failed</u></strong>',
      icon: 'warning',
      html:
        `You can not <b>delete</b> this fund because <br> ${event.detail.constraints.name} is currently in this fund.`,
      showCloseButton: true,
      showCancelButton: false,
      focusConfirm: false,
      confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Okay!',
    })
    });

</script>
  
@endpush