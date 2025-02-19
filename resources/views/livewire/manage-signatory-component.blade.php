<div class="p-2">
  <div class="row">
    <div class="col-md-6">
      {{-- <button class="btn btn-primary rounded-pill text-white py-2 px-4 mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSignatoryModal"><i class="bi bi-plus-lg"></i> Add Signatory</button> --}}
    </div>
    <div class="col-md-6">
      <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control" placeholder="Search..." aria-label="Username" aria-describedby="basic-addon1" wire:model="searchVal">
      </div>
    </div>
  </div>

      <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered" id="signatoryTable" wire:ignore.self>
              <thead>
                <tr>
                  <th scope="col">Signatory</th>
                  <th scope="col">Position</th>
                  <th scope="col">Box</th>
                  @if($signatoryDocu == 'wages')
                  <th scope="col">Section</th>
                  <th scope="col">Office</th>
                  @endif
                  <th scope="col">Controls</th>
                </tr>
              </thead>
              <tbody>
                {{-- @isset($listOfSignatories) --}}
                  @forelse ($listOfSignatories as $signatory)
                  <tr>
                    <td>
                      {{ $signatory->name }}
                    </td>
                    <td>
                      {{ $signatory->position }}
                    </td>
                    <td>
                      {{ $signatory->type }}
                    </td>
                  @if($signatoryDocu == 'wages')
                    <td>
                      {{ $signatory->agencySection->section_description }}
                    </td>
                    <td>
                      {{ $signatory->office }}
                    </td>
                  @endif
                    <td>
                      <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addSignatoryModal" wire:click="showEditSignatoryModal({{ $signatory->id }})">
                          <i class="bi bi-pencil"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" wire:click="deleteSignatoryConfirmation({{ $signatory->id }})">
                          <i class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6"><h3 class="text-muted">Nothing to show here...</h3></td>
                  </tr>
                  @endforelse
                {{-- @endisset --}}
              </tbody>
            </table>
      </div>
        {{ $listOfSignatories->links() }}
  </div>
  
  @push('scripts')
  <script type="text/javascript">
    // signatoryTable.destroy(false);
    // let signatoryTable = new DataTable('#signatoryTable');

    document.addEventListener('livewire:load', function () {

        // var signatoryTable = $('#signatoryTable').DataTable();
        // Livewire.hook('element.updated', (el, component) => {

        //     // Check if the updated element is the DataTable container
        //     if (el.id === 'signatoryTable') {
        //       // setTimeout(
        //       //   function() {
        //       if ($.fn.DataTable.isDataTable('#signatoryTable')) {
        //           $('#signatoryTable').DataTable().destroy();
        //         }

        //       $('#signatoryTable').DataTable();

        //       var element = document.querySelector('[data-dt-idx="0"]');
        //       var element2 = document.querySelector('[data-dt-idx="next"]');

        //       console.log(element2);
        //       console.log(element);

        //       setTimeout(
        //         function() {
        //           element2.click();
        //           element.click();

        //       }, 100);

        //       setTimeout(
        //         function() {
        //           element.click();
        //           element.click();
        //       }, 200);
        //     }
        // });


    });

      window.addEventListener('deleteSignatoryConfirmation', event => {
      Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteSignatory', event.detail.signatoryId);
            }
        })
      });
  
  </script>
    
  @endpush