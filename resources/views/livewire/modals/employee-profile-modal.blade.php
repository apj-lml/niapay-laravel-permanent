
@push('styles')
<style type="text/css">

    .modal-loading-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 1); /* Adjust the opacity as needed */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 2040;
    }

</style>
@endpush
<div class="modal fade" id="employeeProfileModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="employeeProfileModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content position-relative">
            <div class="modal-loading-overlay {{ $profileIsLoaded }}">
                <div class="position-absolute top-50 start-50 translate-middle">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                {{-- <div class="spinner-grow text-secondary position-absolute top-50 start-50 translate-middle" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-success position-absolute top-50 start-50 translate-middle" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-danger position-absolute top-50 start-50 translate-middle" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-warning position-absolute top-50 start-50 translate-middle" role="status">
                    <span class="visually-hidden">Loading...</span>
              </div> --}}
          </div>

        <div class="modal-header">
          <h5 class="modal-title" id="employeeProfileModalLabel">
            @isset($employeeProfile)
            {{ $employeeProfile['last_name'] }}, {{ $employeeProfile['first_name'] }} {{ $employeeProfile['name_extn'] }} {{ $employeeProfile['middle_name'] }} ({{ $employeeProfile['employment_status'] }})
            @endisset
        </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal()"></button>
        </div>
        <div class="modal-body position-relative">
       {{-- /* -------------------------------------------------------------------------- */
            /*                                   spinner                                  */
            /* -------------------------------------------------------------------------- */ --}}
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="selected">Profile</button>
                </li>

                @if(isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'CASUAL' || isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'PERMANENT')
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="allowances-tab" data-bs-toggle="tab" data-bs-target="#allowances" type="button" role="tab" aria-controls="allowances" aria-selected="false" wire:click="clickEmployeeAllowancesTab(@isset($employeeProfile) {{ $employeeProfile['id'] }} @endisset)" > Allowances</button>
                </li>
                @endif

                @if(isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'CASUAL')

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="deduction-tab" data-bs-toggle="tab" data-bs-target="#deduction" type="button" role="tab" aria-controls="deduction" aria-selected="false" wire:click="clickEmployeeDeductionsTab(@isset($employeeProfile) {{ $employeeProfile['id'] }} @endisset)">Deductions</button>
                </li>

                @endif

            </ul>
            <div class="tab-content" id="myTabContent" >
                <div class="tab-pane show active" id="profile" role="tabpanel" aria-labelledby="profile-tab" wire:ignore.self>
                    <div class="row justify-content-center animated wow fadeIn">
                        <div class="mt-3">
                            <form class="needs-validation" novalidate>
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.employee_id') is-invalid @enderror" placeholder="Employee ID" wire:model.debounce.500="employeeProfile.employee_id">
                                            <label for="employee_id">Employee ID</label>
                                            @error('employeeProfile.employee_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select
                                                class="form-select @error('employeeProfile.is_active') is-invalid @enderror"
                                                id="is_active" aria-label="is_active"
                                                wire:model="employeeProfile.is_active" wire:change="changeActiveStatus()">
                                                <option value="1">ACTIVE</option>
                                                <option value="0">INACTIVE</option>
                                            </select>
                                            <label for="floatingSelect">Employment Status</label>
                                            @error('employeeProfile.is_active')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select
                                                class="form-select @error('employeeProfile.is_less_fifteen') is-invalid @enderror"
                                                id="is_less_fifteen" aria-label="is_less_fifteen"
                                                wire:model="employeeProfile.is_less_fifteen" wire:change="changeIsLessFifteen()">
                                                <option value="1">YES</option>
                                                <option value="0">NO</option>
                                            </select>
                                            <label for="floatingSelect">Is less than 15 leave credit?</label>
                                            @error('employeeProfile.is_less_fifteen')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                {{-- <div class="row mb-3 d-none">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.name') is-invalid @enderror" placeholder="Name" wire:model.debounce.500="employeeProfile.name">
                                            <label for="name">Name</label>
                                            @error('employeeProfile.name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row mb-3">
                                    <div class="col-md-3 pe-0">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.last_name') is-invalid @enderror" placeholder="Last Name" wire:model.debounce.500="employeeProfile.last_name" required>
                                            <label for="last_name">Last Name</label>
                                            @error('employeeProfile.last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 pe-0">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.first_name') is-invalid @enderror" placeholder="First Name" wire:model.debounce.500="employeeProfile.first_name" required>
                                            <label for="first_name">First Name</label>
                                            @error('employeeProfile.first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 pe-0">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.middle_name') is-invalid @enderror" placeholder="Middle Name" wire:model.debounce.500="employeeProfile.middle_name">
                                            <label for="middle_name">Middle Name</label>
                                            @error('employeeProfile.middle_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.name_extn') is-invalid @enderror" placeholder="Name Extension" wire:model.debounce.500="employeeProfile.name_extn">
                                            <label for="name_extn">Name Extension</label>
                                            @error('employeeProfile.name_extn')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.last_name') is-invalid @enderror" placeholder="last_name" wire:model.debounce.500="employeeProfile.last_name">
                                            <label for="last_name">Last Name</label>
                                            @error('employeeProfile.last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div> --}}
        
                                <div class="row mb-3">
                                    {{-- <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select @error('employeeProfile.section') is-invalid @enderror" aria-label="section" wire:model="employeeProfile.section">
                                                <option value="ADMINISTRATIVE & FINANCE" selected>ADMINISTRATIVE & FINANCE</option>
                                                <option value="ENGINEERING">ENGINEERING</option>
                                                <option value="OPERATION & MAINTENANCE">OPERATION & MAINTENANCE</option>
                                                <option value="CARP-IC">CARP-IC</option>
                                                <option value="OFFICE OF THE IRRIGATION MANAGER">OFFICE OF THE IRRIGATION MANAGER</option>
                                            </select>
                                            <label for="floatingSelect">Section</label>
                                            @error('employeeProfile.section')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <select class="form-select @error('employeeProfile.agency_unit_id') is-invalid @enderror"  aria-label="unit" wire:model="employeeProfile.agency_unit_id">
                                                @foreach ($listOfUnits as $unit)
                                                    <option value="{{ $unit->id }}"> [{{ Str::upper($unit->agencySection()->get()[0]['section_description'])}}] - {{ $unit->unit_description }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Unit</label>
                                            @error('employeeProfile.agency_unit_id')
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
                                            <input type="text" class="form-control @error('employeeProfile.position') is-invalid @enderror" placeholder="Position" wire:model.debounce.500="employeeProfile.position">
                                            <label for="position">Position</label>
                                            @error('employeeProfile.position')
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
                                            <select class="form-select @error('employeeProfile.employment_status') is-invalid @enderror" id="employment_status" aria-label="employment_status" wire:model="employeeProfile.employment_status" wire:change="dailyOrMonthly($event.target.value)">
                                                <option value="CASUAL" selected>CASUAL</option>
                                                {{-- <option value="PERMANENT">PERMANENT</option>
                                                <option value="TEMPORARY">TEMPORARY</option>
                                                <option value="CASUAL">CASUAL</option>
                                                <option value="COTERMINOUS">COTERMINOUS</option> --}}
                                                <option value="PERMANENT">PERMANENT</option>
                                            </select>
                                            <label for="floatingSelect">Employment Status</label>
                                            @error('employeeProfile.employment_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @if(isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'CASUAL' || isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'PERMANENT')
        
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select @error('employeeProfile.sg_jg') is-invalid @enderror" id="sg_jg" aria-label="sg_jg" onchange="myRate();" wire:model="employeeProfile.sg_jg">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8" selected>8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                            </select>
                                            <label for="floatingSelect">Salary Grade / Job Grade</label>
                                            @error('employeeProfile.sg_jg')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select @error('employeeProfile.step') is-invalid @enderror" id="step" aria-label="step" onchange="myRate();" {{ $isStepDisabled }}>
                                                <option value="1" selected>1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                            
                                            </select>
                                            <label for="floatingSelect">Step</label>
                                            @error('employeeProfile.step')
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
                                            <input type="text" class="form-control @error('employeeProfile.daily_rate') is-invalid @enderror" id="daily_rate" placeholder="Daily Rate" wire:model.lazy="employeeProfile.daily_rate" readonly>
                                            <label for="daily_rate">Daily Rate</label>
                                            @error('employeeProfile.daily_rate')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.daily_rate') is-invalid @enderror" id="daily_rate" placeholder="Daily Rate" wire:model.lazy="userDailyRate" readonly>
                                            <label for="daily_rate">Daily Rate</label>
                                            @error('userDailyRate')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6 d-none">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.monthly_rate') is-invalid @enderror" id="monthly_rate" placeholder="Monthly Rate" wire:model="employeeProfile.monthly_rate" wire:change="employeeProfile.monthly_rate" readonly>
                                            <label for="monthly_rate">Monthly Rate</label>
                                            @error('employeeProfile.monthly_rate')
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
                                            <select class="form-select @error('employeeProfile.fund_id') is-invalid @enderror" aria-label="fund" wire:model="employeeProfile.fund_id">
                                                @foreach ($listOfFunds as $fund)
                                                    <option value="{{ $fund->id }}">{{ $fund->fund_description }}</span></option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Fund</label>
                                            @error('employeeProfile.fund_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center py-3">
                                    <div class="flex-fill"><hr></div>
                                    <div class="d-inline-flex px-2 py-1"><h4 class="text-muted mx-auto">ADDITIONAL INFO</h4></div>
                                    <div class="flex-fill"><hr></div>
                                </div>
                                <div class="row mb-3 mx-auto">
                                    <div class="col-md-6 mb-3 p-1">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.tin') is-invalid @enderror" id="tin" name="tin" placeholder="TIN" value="{{ old('employeeProfile.tin') }}" wire:model="employeeProfile.tin">
                                            <label for="tin">TIN</label>
                                            @error('employeeProfile.tin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3 p-1">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.phic_no') is-invalid @enderror" id="phic_no" name="phic_no" placeholder="Philhealth No." value="{{ old('employeeProfile.phic_no') }}" wire:model="employeeProfile.phic_no">
                                            <label for="phic_no">Philhealth No.</label>
                                            @error('employeeProfile.phic_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3 p-1">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.hdmf') is-invalid @enderror" id="hdmf" name="hdmf" placeholder="PagIBIG No." value="{{ old('hdmf') }}" wire:model="employeeProfile.hdmf">
                                            <label for="hdmf">PagIBIG No.</label>
                                            @error('employeeProfile.hdmf')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3 p-1">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.gsis') is-invalid @enderror" id="gsis" name="gsis" placeholder="GSIS No." value="{{ old('gsis') }}" wire:model="employeeProfile.gsis">
                                            <label for="gsis">GSIS No.</label>
                                            @error('employeeProfile.gsis')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                @endif
                                <div class="row">
                                    <div class="container d-flex align-items-center justify-content-center">
                                        <button type="button" wire:click="saveProfile()" class="btn btn-primary">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="allowances" role="tabpanel" aria-labelledby="allowances-tab" wire:ignore.self>
                    @livewire('employee-allowances-component')
                    <a href="/manage-allowances-deductions" target="_blank" rel="noopener noreferrer" class="me-auto"><i class="bi bi-arrow-left-short"></i> Manage Allowances</a>
                </div>
                
                <div class="tab-pane fade" id="deduction" role="tabpanel" aria-labelledby="deduction-tab" wire:ignore.self>
                    @livewire('employee-deductions-component')
                    <a href="/manage-allowances-deductions" target="_blank" rel="noopener noreferrer" class="me-auto"><i class="bi bi-arrow-left-short"></i> Manage Deductions</a>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal()">Close</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>


            </div>
      </div>
    </div>
  </div>


@push('scripts')

<script type="text/javascript">

window.addEventListener('closeDeductionsTab', event => {
      
    // Find the button element by its ID
    var button = document.getElementById("profile-tab");

            // Trigger a click event on the button
            button.click();
        })

var daily_rate_jo = {
        1:503.09,
        2:534.59,
        3:566.63,
        4:600.63,
        5:636.68,
        6:674.86,
        7:715.36,
        8:761.72,
        9:817.04,
        10:874.22,
        11:943.36,
        12:1042.63,
        13:1146.90,
        14:1261.59,
        15:1387.77,
        16:1526.54
      }

      var monthly_rate_permanent = {
        2:{1:13000.00,	2:13111,	3:13223,	4:13334,	5:13446,	6:13557,	7:13669,	8:13780},
        3:{1:13819.00,	2:13927,	3:14036,	4:14144,	5:14253,	6:14361,	7:14470,	8:14578},
        4:{1:14678.00,	2:14793,	3:14909,	4:15024,	5:15140,	6:15255,	7:15371,	8:15486},
        5:{1:15586.00,	2:16166,	3:16745,	4:17325,	5:17905,	6:18485,	7:19064,	8:19644},
        6:{1:19744.00,	2:19928,	3:20111,	4:20295,	5:20478,	6:20662,	7:20845,	8:21029},
        7:{1:21129.00,	2:21620,	3:22111,	4:22602,	5:23094,	6:23585,	7:24076,	8:24567},
        8:{1:27000.00,	2:27604,	3:28209,	4:28813,	5:29417,	6:30021,	7:30626,	8:31230},
        9:{1:31320.00,	2:32037,	3:32755,	4:33472,	5:34189,	6:34906,	7:35624,	8:36341},
        10:{1:36619.00,	2:38010,	3:39401,	4:40792,	5:42182,	6:43573,	7:44964,	8:46355},
        11:{1:46725.00,	2:51386,	3:56046,	4:60707,	5:65367,	6:70028,	7:74688,	8:79349},
        12:{1:80003.00,	2:82987,	3:85970,	4:88954,	5:91937,	6:94921,	7:97904,	8:100888},
        13:{1:102690.00,    2:106586,	3:110486,	4:114379,	5:118275,	6:122171,	7:126068,	8:129964},
        14:{1:131124.00,	2:133372,	3:135620,	4:137868,	5:140115,	6:142363,	7:144611,	8:146859},
        15:{1:148171.00,	2:150711,	3:153251,	4:155791,	5:158331,	6:160871,	7:163411,	8:165951},
        16:{1:167432.00,	2:170302,	3:173173,	4:176043,	5:178914,	6:181784,	7:184655,	8:187525},
        17:{1:189199.00,	2:192442,	3:195686,	4:198929,	5:202172,	6:205415,	7:208659,	8:211902},
        18:{1:278434.00,	2:284201,	3:289969,	4:295736,	5:301504,	6:307271,	7:313039,	8:318806},
        19:{1:331954.00,	2:339067,	3:346181,	4:353294,	5:360408,	6:367521,	7:374635,	8:381748},
        20:{1:419144.00,	2:422737,	3:426329,	4:429922,	5:433514,	6:437107,	7:440699,	8:444292}
      }

      var daily_rate_casual = {
        2:{1:"590.90", 2:595.95},
        3:{1:628.13, 2:633.04},
        4:{1:667.18, 2:"672.40"},
        5:{1:708.45, 2:734.81},
        6:{1:897.45, 2:905.81},
        7:{1:"960.40", 2:982.72},
        8:{1:1227.27, 2:1254.72},
        9:{1:1423.63, 2:1456.22},
        10:{1:"1664.50", 2:1727.72},
        11:{1:2123.86, 2:2335.72},
        12:{1:"3636.50", 2:3772.13},
        13:{1:4667.72, 2:4844.81}
      }

function dailyOrMonthly(el){
    var monthly_rate =  document.getElementById('monthly_rate');
    var daily_rate =  document.getElementById('daily_rate');
    var sg_jg = document.getElementById('sg_jg');
    var step = document.getElementById("step");

    if(el.value == "CASUAL" || el.value == "PERMANENT" || el.value == "CASUAL"){
        monthly_rate.readOnly = true;
        monthly_rate.value = "";
        daily_rate.value = "";

        daily_rate.readOnly = false;
        sg_jg.selectedIndex = 0;
        step.selectedIndex = 0;

    }else{
        daily_rate.readOnly = true;
        monthly_rate.value = "";
        daily_rate.value = "";
        monthly_rate.readOnly = false;
        sg_jg.selectedIndex = 0;
        step.selectedIndex = 0;
    }

    if(el.value == "CASUAL" || el.value == "PERMANENT"){
        step.disabled = true;
    }else{
        step.disabled = false;

    }
}
// function myRate(){
//     var sg_jg = document.getElementById('sg_jg').value;
//     var step = document.getElementById("step").value;
//     var monthly_rate =  document.getElementById('monthly_rate');
//     var daily_rate =  document.getElementById('daily_rate');
//     var emp_status = document.getElementById('employment_status').value;
//     // monthly_rate.disabled = false;
//     // daily_rate.disabled = false;

//     // step = 1;

//     if (emp_status == "PERMANENT" || emp_status == "COTERMINOUS" || emp_status == "TEMPORARY"){
//         monthly_rate.value = monthly_rate_permanent[sg_jg][step];
//         var temp_m_rate = parseFloat(monthly_rate.value.replace(/,/g, ''));
//         monthly_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

//         @this.set('employeeProfile.monthly_rate', monthly_rate.value);

//         daily_rate.value = "";
//         // daily_rate.disabled = true;

//     }else if (emp_status == "CASUAL" || emp_status == "PERMANENT"){
//         daily_rate.value = daily_rate_jo[sg_jg];
//         var temp_m_rate = parseFloat(daily_rate.value.replace(/,/g, ''));
//         daily_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
//         @this.set('employeeProfile.daily_rate', daily_rate.value);

//         monthly_rate.value = "";
//         // monthly_rate.disabled = true;

    
//     }else if (emp_status == "CASUAL"){
//         daily_rate.value = daily_rate_casual[sg_jg][step];
//         var temp_m_rate = parseFloat(daily_rate.value.replace(/,/g, ''));
//         daily_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
//         @this.set('employeeProfile.daily_rate', daily_rate.value);
//     //   daily_rate.value = daily_rate_casual[job_grade][step];
//         monthly_rate.value = "";
//         // monthly_rate.disabled = true;
//     }
// }


function myRate(){
    var sg_jg = document.getElementById('sg_jg').value;
    var step = document.getElementById("step").value;
    var monthly_rate =  document.getElementById('monthly_rate');
    var daily_rate =  document.getElementById('daily_rate');
    var emp_status = document.getElementById('employment_status').value;
    // monthly_rate.disabled = false;
    // daily_rate.disabled = false;

    // step = 1;

    if (emp_status == "PERMANENT" || emp_status == "COTERMINOUS" || emp_status == "TEMPORARY"){
        monthly_rate.value = monthly_rate_permanent[sg_jg][step];
        var temp_m_rate = parseFloat(monthly_rate.value.replace(/,/g, ''));
        monthly_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        @this.set('employeeProfile.monthly_rate', monthly_rate.value);

        daily_rate.value = "";
        // daily_rate.disabled = true;

    }else if (emp_status == "JOB ORDER" || emp_status == "CONTRACT OF SERVICE"){
        daily_rate.value = daily_rate_jo[sg_jg];
        var temp_m_rate = parseFloat(daily_rate.value.replace(/,/g, ''));
        daily_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        @this.set('employeeProfile.daily_rate', daily_rate.value);
        monthly_rate.value = "";
        // monthly_rate.disabled = true;

    
    }else if (emp_status == "CASUAL"){
        daily_rate.value = daily_rate_casual[sg_jg][step];
        var temp_m_rate = parseFloat(daily_rate.value.replace(/,/g, ''));
        daily_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        @this.set('employeeProfile.daily_rate', daily_rate.value);
        monthly_rate.value = "";
    }

    // getDeductionRate(daily_rate_jo[sg_jg])
}

function disableCasualSgJgStep(isDisabled){
        var op = document.getElementById("step").getElementsByTagName("option");
        for (var i = 3; i < op.length; i++) {
            op[i].disabled = isDisabled;
          }

        var jg = document.getElementById("job_grade").getElementsByTagName("option");
          for (var j = 13; j < jg.length; j++) { 
              jg[j].disabled = isDisabled;
            }
      }
</script>

@endpush