<div class="p-2">
    <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
        <div class="card-header bg-primary text-white fw-1"><i class="bi bi-house-gear-fill"></i> Manage Sections</div>

        <div class="card-body">
        <button class="btn btn-primary rounded-pill text-white py-2 px-4 mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSectionsModal"><i class="bi bi-plus-lg"></i> Add Section</button>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Section Code</th>
                        <th scope="col">Section Description</th>
                        <th scope="col">Controls</th>
                        {{-- <th scope="col">Role</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @isset($listOfSections)
                        @foreach ($listOfSections as $section)
                        <tr>
                          <td>
                            {{ $section->section_code }}
                          </td>
                          <td>
                            {{ $section->section_description }}
                          </td>
                          <td>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addSectionsModal" wire:click="showEditSectionModal({{ $section->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="deleteSectionConfirmation({{ $section->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                          </td>
                        </tr>
                        @endforeach
                      @endisset
                    </tbody>
                  </table>
            </div>
        
              {{ $listOfSections->links() }}

        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">

window.addEventListener('deleteSectionConfirmation', event => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
              Livewire.emit('deleteSection', event.detail.sectionId);
          }
      })
    });

    window.addEventListener('invalidDeletionSection', event => {
      console.log(event.detail.constraints);
      Swal.fire({
      title: '<strong>Deletion <u>Failed</u></strong>',
      icon: 'warning',
      html:
        `You can not <b>delete</b> this section because <br> ${event.detail.constraints.name} is currently in this section`,
      showCloseButton: true,
      showCancelButton: false,
      focusConfirm: false,
      confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Okay!',
      confirmButtonAriaLabel: 'Thumbs up, great!',
    })
    });

</script>
  
@endpush