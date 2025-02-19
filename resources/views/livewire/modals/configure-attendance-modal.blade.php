<!-- Modal -->
<div class="modal fade" id="configureAttendanceModal" tabindex="-1" aria-labelledby="configureAttendanceModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configureAttendanceModalLabel">Configure Attendance ({{$employmentStatus}})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">


                  <form wire:submit.prevent="addConfiguredAttendance" class="needs-validation " novalidate id="addConfiguredAttendance">
                    <div id="hacker-list">
                        <div class="col-sm-12">
                            <div class="position-relative w-100 mb-3">
                                <input class="form-control border-1 rounded-pill w-100 ps-4 pe-5 search" type="text" placeholder="Search Name..." style="height: 48px;" onkeydown="">
                                <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                        <ol class="list list-group list-group-numbered">

                            @isset($configuredData)
                                @for ($i=0; $i<count($configuredData); $i++)
                                    {{-- <li>
                                        <h3 class="name">Jonny{{ $i }}</h3>
                                        <p class="city">Stockholm</p>
                                    </li> --}}

                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold name">{{$configuredData[$i]['name']}}</div>

                                            <div class="row" wire:key="employee-configuration-{{ $configuredData[$i]['id'] }}">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="hidden" class="form-control" wire:model.defer="configuredData.{{ $i }}.id">
                                        
                                                        <input type="number" class="form-control @error('configuredData.'. $i .'.configuredDaysRendered') is-invalid @enderror" placeholder="No. of days" aria-label="Number of days" aria-describedby="basic-addon2" wire:model.defer="configuredData.{{ $i }}.configuredDaysRendered">
                                                        <span class="input-group-text" id="basic-addon2">full month</span>
                                        
                                                        @error('configuredData.'. $i .'.configuredDaysRendered')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="hidden" class="form-control" wire:model.defer="configuredData.{{ $i }}.id">
                                        
                                                        <input type="number" class="form-control @error('configuredData.'. $i .'.configuredFirstHalf') is-invalid @enderror" placeholder="No. of days" aria-label="Number of days" aria-describedby="basic-addon2" wire:model.defer="configuredData.{{ $i }}.configuredFirstHalf">
                                                        <span class="input-group-text" id="basic-addon2">01-15</span>
                                        
                                                        @error('configuredData.'. $i .'.configuredFirstHalf')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="hidden" class="form-control" wire:model.defer="configuredData.{{ $i }}.id">
                                        
                                                        <input type="number" class="form-control @error('configuredData.'. $i .'.configuredSecondHalf') is-invalid @enderror" placeholder="No. of days" aria-label="Number of days" aria-describedby="basic-addon2" wire:model.defer="configuredData.{{ $i }}.configuredSecondHalf">
                                                        <span class="input-group-text" id="basic-addon2">16-31</span>
                                        
                                                        @error('configuredData.'. $i .'.configuredSecondHalf')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            
                                         </div>
                                        {{-- <span class="badge bg-primary rounded-pill">{{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}</span> --}}
                                    </li>
                                @endfor
                            @endisset
                            
                        </ol>
                      </div>
                      <div style="display:none;">
                        <!-- A template element is needed when list is empty, TODO: needs a better solution -->
                        <li id="hacker-item">
                         <h3 class="name"></h3>
                         <p class="city"></p>
                        </li>
                      </div>
     
       
              </form>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="addConfiguredAttendance"><i class="bi bi-plus"></i> Add</button>
                <button class="btn btn-secondary" data-bs-target="#addAttendanceModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="bi bi-arrow-bar-left"></i> Back</button>
              </div>
          </div>
      </div>
</div>

@push('scripts')
  <script type="text/javascript">

    var myModal = document.getElementById('configureAttendanceModal')

    myModal.addEventListener('shown.bs.modal', function () {
        initSearch();
    })
    function initSearch(){
    
        var options = {
            valueNames: [ 'name' ]
        };

        var hackerList = new List('hacker-list', options);
    }

  </script>
@endpush