
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
    <div class="modal-dialog modal-xl">
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

                @if(isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'PERMANENT' || isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'COTERMINOUS')
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="allowances-tab" data-bs-toggle="tab" data-bs-target="#allowances" type="button" role="tab" aria-controls="allowances" aria-selected="false" wire:click="clickEmployeeAllowancesTab(@isset($employeeProfile) {{ $employeeProfile['id'] }} @endisset)" > Allowances</button>
                </li>
                @endif

                @if(isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'PERMANENT' || isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'COTERMINOUS')

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
                                                class="form-select @error('activeStatus') is-invalid @enderror"
                                                id="is_active" aria-label="is_active"
                                                wire:model="activeStatus" wire:change="changeActiveStatus()">
                                                <option value="1">ACTIVE</option>
                                                <option value="0">INACTIVE</option>
                                            </select>
                                            <label for="floatingSelect">Employment Status</label>
                                            @error('activeStatus')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select
                                                class="form-select @error('isLessFifteen') is-invalid @enderror"
                                                id="is_less_fifteen" aria-label="is_less_fifteen"
                                                wire:model="isLessFifteen" wire:change="changeIsLessFifteen()">
                                                <option value="1">YES</option>
                                                <option value="0">NO</option>
                                            </select>
                                            <label for="floatingSelect">Is less than 15 leave credit?</label>
                                            @error('isLessFifteen')
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
                                 
                                                <option value="PERMANENT" selected>PERMANENT</option>
                                                <option value="COTERMINOUS">COTERMINOUS</option>
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
                                @if(isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'COTERMINOUS' || isset($employeeProfile->employment_status) && $employeeProfile->employment_status == 'PERMANENT')
        
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
                                            <select class="form-select @error('employeeProfile.step') is-invalid @enderror" id="step" aria-label="step" onchange="myRate();" {{ $isStepDisabled }} wire:model="employeeProfile.step">
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
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('employeeProfile.monthly_rate') is-invalid @enderror" id="monthly_rate" placeholder="Monthly Rate" wire:model="employeeProfile.monthly_rate" readonly>
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
                                    <div class="col-md-12 mb-3 p-1">
                                        <div class="form-floating">
                                            <input type="number" class="form-control @error('employeeProfile.atm_no') is-invalid @enderror" id="atm" name="atm" placeholder="ATM No." value="{{ old('employeeProfile.atm_no') }}" wire:model="employeeProfile.atm_no">
                                            <label for="atm_no">LBP ATM No.</label>
                                            @error('employeeProfile.atm_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
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

window.addEventListener('format-monthly-rate', event => {
        myRate();
    });

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

    //   var monthly_rate_permanent = {
    //     2:{1:13000.00,	2:13111,	3:13223,	4:13334,	5:13446,	6:13557,	7:13669,	8:13780},
    //     3:{1:13819.00,	2:13927,	3:14036,	4:14144,	5:14253,	6:14361,	7:14470,	8:14578},
    //     4:{1:14678.00,	2:14793,	3:14909,	4:15024,	5:15140,	6:15255,	7:15371,	8:15486},
    //     5:{1:15586.00,	2:16166,	3:16745,	4:17325,	5:17905,	6:18485,	7:19064,	8:19644},
    //     6:{1:19744.00,	2:19928,	3:20111,	4:20295,	5:20478,	6:20662,	7:20845,	8:21029},
    //     7:{1:21129.00,	2:21620,	3:22111,	4:22602,	5:23094,	6:23585,	7:24076,	8:24567},
    //     8:{1:27000.00,	2:27604,	3:28209,	4:28813,	5:29417,	6:30021,	7:30626,	8:31230},
    //     9:{1:31320.00,	2:32037,	3:32755,	4:33472,	5:34189,	6:34906,	7:35624,	8:36341},
    //     10:{1:36619.00,	2:38010,	3:39401,	4:40792,	5:42182,	6:43573,	7:44964,	8:46355},
    //     11:{1:46725.00,	2:51386,	3:56046,	4:60707,	5:65367,	6:70028,	7:74688,	8:79349},
    //     12:{1:80003.00,	2:82987,	3:85970,	4:88954,	5:91937,	6:94921,	7:97904,	8:100888},
    //     13:{1:102690.00, 2:106586,	3:110486,	4:114379,	5:118275,	6:122171,	7:126068,	8:129964},
    //     14:{1:131124.00,	2:133372,	3:135620,	4:137868,	5:140115,	6:142363,	7:144611,	8:146859},
    //     15:{1:148171.00,	2:150711,	3:153251,	4:155791,	5:158331,	6:160871,	7:163411,	8:165951},
    //     16:{1:167432.00,	2:170302,	3:173173,	4:176043,	5:178914,	6:181784,	7:184655,	8:187525},
    //     17:{1:189199.00,	2:192442,	3:195686,	4:198929,	5:202172,	6:205415,	7:208659,	8:211902},
    //     18:{1:278434.00,	2:284201,	3:289969,	4:295736,	5:301504,	6:307271,	7:313039,	8:318806},
    //     19:{1:331954.00,	2:339067,	3:346181,	4:353294,	5:360408,	6:367521,	7:374635,	8:381748},
    //     20:{1:419144.00,	2:422737,	3:426329,	4:429922,	5:433514,	6:437107,	7:440699,	8:444292}
    //   }

      var monthly_rate_permanent = {
        1:{1:'13000.00',2:'13111.00',3:'13223.00',4:'13334.00',5:'13446.00',6:'13557.00',7:'13669.00',8:'13780.00'},
        2:{1:'13819.00',2:'13927.00',3:'14036.00',4:'14144.00',5:'14253.00',6:'14361.00',7:'14470.00',8:'14578.00'},
        3:{1:'14678.00',2:'14793.00',3:'14909.00',4:'15024.00',5:'15140.00',6:'15255.00',7:'15371.00',8:'15486.00'},
        4:{1:'15586.00',2:'15976.00',3:'16375.00',4:'16784.00',5:'17204.00',6:'17634.00',7:'18075.00',8:'18527.00'},
        5:{1:'16166.00',2:'16489.00',3:'16819.00',4:'17155.00',5:'17499.00',6:'17849.00',7:'18206.00',8:'18570.00'},
        6:{1:'17325.00',2:'17585.00',3:'17849.00',4:'18116.00',5:'18388.00',6:'18664.00',7:'18944.00',8:'19228.00'},
        7:{1:'17905.00',2:'18084.00',3:'18265.00',4:'18448.00',5:'18632.00',6:'18818.00',7:'19007.00',8:'19644.00'},
        8:{1:'19744.00',2:'19928.00',3:'20111.00',4:'20295.00',5:'20478.00',6:'20662.00',7:'20845.00',8:'21029.00'},
        9:{1:'21129.00',2:'21446.00',3:'21768.00',4:'22094.00',5:'22426.00',6:'22762.00',7:'23103.00',8:'23450.00'},
        10:{1:'23094.00',2:'23325.00',3:'23558.00',4:'23794.00',5:'24032.00',6:'24272.00',7:'24515.00',8:'24567.00'},
        11:{1:'27000.00',2:'27405.00',3:'27816.00',4:'28233.00',5:'28657.00',6:'29087.00',7:'29523.00',8:'29966.00'},
        12:{1:'29417.00',2:'29682.00',3:'29949.00',4:'30218.00',5:'30490.00',6:'30765.00',7:'31042.00',8:'31230.00'},
        13:{1:'31320.00',2:'31790.00',3:'32267.00',4:'32751.00',5:'33242.00',6:'33741.00',7:'34247.00',8:'34760.00'},
        14:{1:'34189.00',2:'34531.00',3:'34876.00',4:'35225.00',5:'35577.00',6:'35933.00',7:'36292.00',8:'36341.00'},
        15:{1:'36619.00',2:'37351.00',3:'38098.00',4:'38860.00',5:'39638.00',6:'40430.00',7:'41239.00',8:'42064.00'},
        16:{1:'39401.00',2:'39992.00',3:'40592.00',4:'41201.00',5:'41819.00',6:'42446.00',7:'43083.00',8:'43729.00'},
        17:{1:'42182.00',2:'42604.00',3:'43030.00',4:'43460.00',5:'43895.00',6:'44334.00',7:'44777.00',8:'46355.00'},
        18:{1:'46725.00',2:'51386.00',3:'52671.00',4:'53987.00',5:'55337.00',6:'56721.00',7:'58139.00',8:'59592.00'},
        19:{1:'51386.00',2:'52825.00',3:'54304.00',4:'55824.00',5:'57387.00',6:'58994.00',7:'60646.00',8:'62344.00'},
        20:{1:'60707.00',2:'61921.00',3:'63160.00',4:'64423.00',5:'65711.00',6:'67025.00',7:'68366.00',8:'69733.00'},
        21:{1:'65367.00',2:'67001.00',3:'68676.00',4:'70393.00',5:'72153.00',6:'73957.00',7:'75806.00',8:'79349.00'},
        22:{1:'80003.00',2:'81603.00',3:'83235.00',4:'84900.00',5:'86598.00',6:'88330.00',7:'90096.00',8:'91898.00'},
        23:{1:'82987.00',2:'84627.00',3:'86340.00',4:'88066.00',5:'89828.00',6:'91624.00',7:'93457.00',8:'95326.00'},
        24:{1:'91937.00',2:'93132.00',3:'94343.00',4:'95569.00',5:'96812.00',6:'98070.00',7:'99345.00',8:'100888.00'},
      }

      var monthly_rate_permanent_pg = {
        1:{1:'15208.00',2:'15304.00',3:'15423.00',4:'15542.00',5:'15663.00',6:'15784.00',7:'15906.00',8:'16030.00'},
        2:{1:'16118.00',2:'16233.00',3:'16349.00',4:'16466.00',5:'16582.00',6:'16700.00',7:'16820.00',8:'16939.00'},
        3:{1:'17120.00',2:'17244.00',3:'17366.00',4:'17490.00',5:'17616.00',6:'17740.00',7:'17868.00',8:'17994.00'},
        4:{1:'18180.00',2:'18309.00',3:'18440.00',4:'18571.00',5:'18704.00',6:'18836.00',7:'18971.00',8:'19106.00'},
        5:{1:'19296.00',2:'19434.00',3:'19573.00',4:'19712.00',5:'19852.00',6:'19994.00',7:'20137.00',8:'20280.00'},
        6:{1:'20474.00',2:'20620.00',3:'20767.00',4:'20916.00',5:'21065.00',6:'21215.00',7:'21367.00',8:'21520.00'},
        7:{1:'21872.00',2:'22034.00',3:'22196.00',4:'22362.00',5:'22526.00',6:'22693.00',7:'22860.00',8:'23030.00'},
        8:{1:'23399.00',2:'23603.00',3:'23808.00',4:'24014.00',5:'24221.00',6:'24432.00',7:'24644.00',8:'24859.00'},
        9:{1:'25433.00',2:'25627.00',3:'25823.00',4:'26021.00',5:'26220.00',6:'26421.00',7:'26624.00',8:'26828.00'},
        10:{1:'28247.00',2:'28462.00',3:'28678.00',4:'28896.00',5:'29116.00',6:'29337.00',7:'29561.00',8:'29787.00'},
        11:{1:'33387.00',2:'33501.00',3:'33790.00',4:'34082.00',5:'34378.00',6:'34679.00',7:'34983.00',8:'35292.00'},
        12:{1:'35650.00',2:'35771.00',3:'36059.00',4:'36350.00',5:'36645.00',6:'36944.00',7:'37246.00',8:'37552.00'},
        13:{1:'37828.00',2:'37987.00',3:'38303.00',4:'38623.00',5:'38948.00',6:'39276.00',7:'39608.00',8:'39945.00'},
        14:{1:'40505.00',2:'40882.00',3:'41263.00',4:'41650.00',5:'42040.00',6:'42436.00',7:'42837.00',8:'43243.00'},
        15:{1:'44148.00',2:'44564.00',3:'44985.00',4:'45412.00',5:'45844.00',6:'46281.00',7:'46723.00',8:'47172.00'},
        16:{1:'47829.00',2:'48286.00',3:'48750.00',4:'49219.00',5:'49694.00',6:'50175.00',7:'50662.00',8:'51154.00'},
        17:{1:'51877.00',2:'52381.00',3:'52891.00',4:'53407.00',5:'53929.00',6:'54459.00',7:'54993.00',8:'55536.00'},
        18:{1:'56332.00',2:'56885.00',3:'57447.00',4:'58013.00',5:'58589.00',6:'59171.00',7:'59760.00',8:'60356.00'},
        19:{1:'61916.00',2:'62729.00',3:'63556.00',4:'64395.00',5:'65249.00',6:'66116.00',7:'66999.00',8:'67895.00'},
        20:{1:'76594.00',2:'77628.00',3:'78679.00',4:'79747.00',5:'80833.00',6:'81936.00',7:'82982.00',8:'84121.00'},
        21:{1:'95296.00',2:'96612.00',3:'97952.00',4:'99320.00',5:'100814.00',6:'102331.00',7:'103873.00',8:'105308.00'},
        22:{1:'107022.00',2:'108627.00',3:'110260.00',4:'111918.00',5:'113603.00',6:'115317.00',7:'116952.00',8:'118719.00'},
        23:{1:'121559.00',2:'123385.00',3:'125242.00',4:'127128.00',5:'129047.00',6:'130995.00',7:'132977.00',8:'134989.00'},
        24:{1:'155217.00',2:'157550.00',3:'159921.00',4:'162184.00',5:'164630.00',6:'166512.00',7:'169030.00',8:'171587.00'},
        25:{1:'173788.00',2:'176411.00',3:'179077.00',4:'181457.00',5:'184205.00',6:'186999.00',7:'189319.00',8:'192196.00'},
        26:{1:'194570.00',2:'197521.00',3:'200519.00',4:'203567.00',5:'206663.00',6:'209044.00',7:'212230.00',8:'215469.00'},
        27:{1:'218237.00',2:'221556.00',3:'224726.00',4:'227943.00',5:'231209.00',6:'234743.00',7:'238113.00',8:'241758.00'},
        28:{1:'308730.00',2:'314460.00',3:'320302.00',4:'325952.00',5:'331707.00',6:'337758.00',7:'343862.00',8:'350080.00'},
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

function formatMonthlyRate(input) {
    // Remove any non-digit characters except the decimal point
    let value = input.value.replace(/[^0-9.]/g, '');

    // Format the number as currency with 2 decimal places
    let formattedValue = parseFloat(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Update the input value with the formatted value
    input.value = formattedValue;
}

function dailyOrMonthly(el){
    var monthly_rate =  document.getElementById('monthly_rate');
    var daily_rate =  document.getElementById('daily_rate');
    var sg_jg = document.getElementById('sg_jg');
    var step = document.getElementById("step");

    if(el.value == "CASUAL" || el.value == "JOB ORDER" || el.value == "CONTRACT OF SERVICE"){
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

    if(el.value == "COTERMINOUS" || el.value == "PERMANENT"){
        step.disabled = true;
    }else{
        step.disabled = false;

    }
}


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
        monthly_rate.value = monthly_rate_permanent_pg[sg_jg][step];
        var temp_m_rate = parseFloat(monthly_rate.value.replace(/,/g, ''));
        monthly_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        @this.set('employeeProfile.monthly_rate', monthly_rate.value);

        daily_rate.value = "";
        // daily_rate.disabled = true;

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