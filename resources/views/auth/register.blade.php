@extends('layouts.app')

@section('content')
@section('hero')
{{-- <div class="container-xxl position-relative p-0"> --}}

    <div class="container-xxl bg-primary page-header">
        <div class="container text-center">
            <h1 class="text-white animated zoomIn mt-5">Register Employee</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-white" href="/">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current=""><a class="text-white" href="/custom-register">Register</a></li>
                </ol>
            </nav>
        </div>
    </div>
    
{{-- </div> --}}
@endsection

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-body p-5 pt-2">
                    @if(session('status'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" class="needs-validation">
                        @csrf
                        <div class="d-flex justify-content-center py-3">
                            <div class="flex-fill"><hr></div>
                            <div class="d-inline-flex px-2 py-1"><h4 class="text-muted mx-auto">PERSONAL INFORMATION</h4></div>
                            <div class="flex-fill"><hr></div>
                        </div>
                        <div class="row mb-3 mx-auto">
                            <div class="col-md-4 p-1">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" placeholder="493885" minlength="6" maxlength="6" value="{{ old('employee_id') }}" required autofocus>
                                    <label for="employee_id">Employee ID</label>
                                    @error('employee_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" aria-label="role" onchange="roleChange(this);">
                                        <option value="0" selected>N/A</option>
                                        <option value="1">PAYROLL CLERK (CASUAL / PERMANENT)</option>
                                        {{-- <option value="2">PAYROLL CLERK (CASUAL)</option> --}}
                                        {{-- <option value="3">PAYROLL CLERK (PERMANENT / COTERMINOUS)</option> --}}
                                        {{-- <option value="5">TRUST</option> --}}
                                    </select>
                                    <label for="role">Role</label>
                                    @error('role')
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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="DELA CRUZ, JUAN D." value="{{ old('name') }}">
                                    <label for="name">Name</label>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                    <label for="last_name">Last Name</label>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                                    <label for="first_name">First Name</label>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" placeholder="Middle Name" value="{{ old('middle_name') }}" required>
                                    <label for="middle_name">Middle Name</label>
                                    @error('middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name_extn') is-invalid @enderror" id="name_extn" name="name_extn" placeholder="Name Extension" value="{{ old('name_extn') }}">
                                    <label for="name_extn">Name Extension</label>
                                    @error('name_extn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" value="{{ old('password') }}" readonly>
                                    <label for="password">Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" readonly>
                                    <label for="password_confirmation">Confirm Password</label>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" aria-label="section">
                                        <option value="ADMINISTRATIVE & FINANCE" selected>ADMINISTRATIVE & FINANCE</option>
                                        <option value="ENGINEERING">ENGINEERING</option>
                                        <option value="OPERATION & MAINTENANCE">OPERATION & MAINTENANCE</option>
                                        <option value="CARP-IC">CARP-IC</option>
                                        <option value="OFFICE OF THE IRRIGATION MANAGER">OFFICE OF THE IRRIGATION MANAGER</option>
                                    </select>
                                    <label for="floatingSelect">Section</label>
                                    @error('section')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-12 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('agency_unit_id') is-invalid @enderror" id="agency_unit_id" name="agency_unit_id" aria-label="unit">
                                    {{-- @dd($agencyUnits) --}}
                                        @foreach ($agencyUnits as $unit)
                                            <option value="{{ $unit->id }}" @if($loop->first) selected @endif> [{{ Str::upper($unit->agencySection()->get()[0]['section_description'])}}] - {{ $unit->unit_description }}</option>
                                        @endforeach
                                    </select>
                                    <label for="floatingSelect">[Section] - Unit</label>
                                    @error('agency_unit_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-12 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" placeholder="Position" value="{{ old('position') }}" required>
                                    <label for="position">Position</label>
                                    @error('position')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('employment_status') is-invalid @enderror" id="employment_status" name="employment_status" aria-label="employment_status" value="{{ old('employment_status') }}" onchange="dailyOrMonthly(this)">
                                        <option value="CASUAL" selected>CASUAL</option>
                                        {{-- <option value="PERMANENT">PERMANENT</option>
                                        <option value="TEMPORARY">TEMPORARY</option>
                                        <option value="CASUAL">CASUAL</option>
                                        <option value="COTERMINOUS">COTERMINOUS</option> --}}
                                        {{-- <option value="PERMANENT">PERMANENT</option> --}}
                                    </select>
                                    <label for="floatingSelect">Employment Status</label>
                                    @error('employment_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('is_less_fifteen') is-invalid @enderror" id="is_less_fifteen" name="is_less_fifteen" aria-label="is_less_fifteen" value="{{ old('is_less_fifteen') }}">
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                    <label for="floatingSelect">Below Fifteen (15) earned leaves?</label>
                                    @error('is_less_fifteen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('sg_jg') is-invalid @enderror" id="sg_jg" name="sg_jg" aria-label="sg_jg" onchange="myRate();">
                                        <option value="" hidden>-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        {{-- <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option> --}}
                                        {{-- <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option> --}}
                                    </select>
                                    <label for="floatingSelect">Salary Grade / Job Grade</label>
                                    @error('sg_jg')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('step') is-invalid @enderror" id="step" name="step" aria-label="step" onchange="myRate();">
                                        <option value="" hidden selected>-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        {{-- <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option> --}}
                   
                                    </select>
                                    <label for="floatingSelect">Step</label>
                                    @error('step')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('daily_rate') is-invalid @enderror" id="daily_rate" name="daily_rate" placeholder="Daily Rate" value="{{ old('daily_rate') }}" readonly>
                                    <label for="daily_rate">Daily Rate</label>
                                    @error('daily_rate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('monthly_rate') is-invalid @enderror" id="monthly_rate" name="monthly_rate" placeholder="Monthly Rate" value="{{ old('monthly_rate') }}" readonly>
                                    <label for="monthly_rate">Monthly Rate</label>
                                    @error('monthly_rate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mx-auto">
                            <div class="col-md-6 p-1">
                                <div class="form-floating">
                                    <select class="form-select @error('fund_id') is-invalid @enderror" id="fund_id" name="fund_id" aria-label="fund">
                                        @foreach ($listOfFunds as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->fund_description }}</span></option>
                                        @endforeach
                                    </select>
                                    <label for="floatingSelect">Fund</label>
                                    @error('fund_id')
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
                                    <input type="text" class="form-control @error('tin') is-invalid @enderror" id="tin" name="tin" placeholder="TIN" value="{{ old('tin') }}" required>
                                    <label for="tin">TIN</label>
                                    @error('tin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('phic_no') is-invalid @enderror" id="phic_no" name="phic_no" placeholder="Philhealth No." value="{{ old('phic_no') }}" required>
                                    <label for="phic_no">Philhealth No.</label>
                                    @error('phic_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('hdmf') is-invalid @enderror" id="hdmf" name="hdmf" placeholder="PagIBIG No." value="{{ old('hdmf') }}" required>
                                    <label for="hdmf">PagIBIG No.</label>
                                    @error('hdmf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('gsis') is-invalid @enderror" id="gsis" name="gsis" placeholder="GSIS No." value="{{ old('gsis') }}" required>
                                    <label for="gsis">GSIS No.</label>
                                    @error('gsis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-md-6 mb-3 p-1">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('sss') is-invalid @enderror" id="sss" name="sss" placeholder="SSS No." value="{{ old('sss') }}" required>
                                    <label for="sss">SSS No.</label>
                                    @error('sss')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            {{-- <div class="d-flex justify-content-center py-3">
                                <div class="flex-fill"><hr></div>
                                <div class="d-inline-flex px-2 py-1"><h4 class="text-muted mx-auto">DEDUCTIONS</h4></div>
                                <div class="flex-fill"><hr></div>
                            </div> --}}
                            {{-- <div class="row mx-auto">
                                <div class="col-md-6 p-1">
                                    <div class="form-floating">
                                        <input type="hidden" id="ded_phic_id" name="ded_phic_id" value="27">
                                        <input type="text" class="form-control @error('ded_phic') is-invalid @enderror" id="ded_phic" name="ded_phic" placeholder="PHIC" value="{{ old('ded_phic') }}" onkeyup="validateDedInput(this)" onchange="validateDedInput(this)">
                                        <label for="ded_phic">PHIC</label>
                                        @error('ded_phic')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 p-1">
                                    <div class="form-floating">
                                        <input type="hidden" id="ded_pagibig_id" name="ded_pagibig_id" value="23">
                                        <input type="text" class="form-control @error('ded_pagibig') is-invalid @enderror" id="ded_pagibig" name="ded_pagibig" placeholder="pag-IBIG" value="{{ old('ded_pagibig') }}" onkeyup="validateDedInput(this)" onchange="validateDedInput(this)">
                                        <label for="ded_pagibig">Pag-IBIG</label>
                                        @error('ded_pagibig')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 p-1">
                                    <div class="form-floating">
                                        <input type="hidden" id="ded_wht_id" name="ded_wht_id" value="30">
                                        <input type="text" class="form-control @error('ded_wht') is-invalid @enderror" id="ded_wht" name="ded_wht" placeholder="WHT" value="{{ old('ded_wht') }}" onkeyup="validateDedInput(this)" onchange="validateDedInput(this)" readonly>
                                        <label for="ded_wht">WHT</label>
                                        @error('ded_wht')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 p-1">
                                    <div class="form-floating">
                                        <input type="hidden" id="ded_sss_id" name="ded_sss_id" value="31">
                                        <input type="text" class="form-control @error('ded_sss') is-invalid @enderror" id="ded_sss" name="ded_sss" placeholder="SSS" value="{{ old('ded_sss') }}" onkeyup="validateDedInput(this)" onchange="validateDedInput(this)">
                                        <label for="ded_sss">SSS</label>
                                        @error('ded_sss')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 p-1">
                                    <div class="form-floating">
                                        <input type="hidden" id="ded_disallow_id" name="ded_disallow_id" value="29">
                                        <input type="text" class="form-control @error('ded_disallow') is-invalid @enderror" id="ded_disallow" name="ded_disallow" placeholder="DISALLOWANCE" value="{{ old('ded_disallow') }}" onkeyup="validateDedInput(this)" onchange="validateDedInput(this)">
                                        <label for="ded_disallow">DISALLOWANCE</label>
                                        @error('ded_disallow')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> 
                            </div> --}}
                        </div>
                        <div class="row mb-0 mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mx-auto">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')

<script type="text/javascript">

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

function roleChange(el){
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('password_confirmation');
  
    if(el.value == "0"){
        password.readOnly = true;
        confirmPassword.readOnly = true;
        password.value = "";
        confirmPassword.value = "";
    }else{
        password.readOnly = false;
        confirmPassword.readOnly = false;
        password.value = "";
        confirmPassword.value = "";
    }
}
function dailyOrMonthly(el){
    var monthly_rate =  document.getElementById('monthly_rate');
    var daily_rate =  document.getElementById('daily_rate');
    var sg_jg = document.getElementById('sg_jg');
    var step = document.getElementById("step");

    if(el.value != "CASUAL" ){
        monthly_rate.readOnly = true;
        monthly_rate.value = "";
        daily_rate.value = "";

        daily_rate.readOnly = false;
        sg_jg.selectedIndex = 0;
        step.selectedIndex = 0;
        step.readOnly = true;

    }else{
        daily_rate.readOnly = true;
        monthly_rate.value = "";
        daily_rate.value = "";
        monthly_rate.readOnly = false;
        sg_jg.selectedIndex = 0;
        step.selectedIndex = 0;
        step.readOnly = false;
    }

    if(el.value == "CASUAL" ){
        step.disabled = true;
    }else{
        step.disabled = false;

    }
}

function getDeductionRate(daily_rate){
    let ded_phic = document.getElementById('ded_phic');
    let ded_pagibig = document.getElementById('ded_pagibig');
    // let ded_wht = document.getElementById('ded_wht');
    let ded_sss = document.getElementById('ded_sss');
    let ded_disallow = document.getElementById('ded_disallow');

    let sg_jg = document.getElementById('sg_jg');
    // let wht_value =  0.00;

    //HDMF
    if(sg_jg.value <= 6){
        ded_pagibig.value = toFixed_norounding(300.00, 2)
    }else if(sg_jg.value >= 7 && sg_jg.value <= 10){
        ded_pagibig.value = toFixed_norounding(400.00, 2)
    }else if(sg_jg.value >= 11 && sg_jg.value <= 12){
        ded_pagibig.value = toFixed_norounding(500.00, 2)
    }else if(sg_jg.value >= 13 && sg_jg.value <= 16){
        ded_pagibig.value = toFixed_norounding(600.00, 2)
    }else{
        ded_pagibig.value = toFixed_norounding(((daily_rate * 22) * .02), 2);
    }

    //TAX
    // if(sg_jg.value < 13){
    //     ded_wht.readOnly = true;
    //     wht_value = toFixed_norounding(0.00, 2);
    // }else{
    //     ded_wht.readOnly = false;
    //     wht_value = toFixed_norounding(((daily_rate * 22) * .05), 2);
    // }

    ded_phic.value = toFixed_norounding(((daily_rate * 22) * .05), 2);
    // ded_wht.value = wht_value;
    ded_sss.value = toFixed_norounding(570.00, 2);
    ded_disallow.value = toFixed_norounding(0.00, 2);
}

function toFixed_norounding(n,p)
{
    var result = n.toFixed(p);
    result = Math.abs(result) <= Math.abs(n) ? result: (result - Math.sign(n) * Math.pow(0.1,p)).toFixed(p);

    // if you want negative zeros (-0.00), use this instead:
    // return result;

    // fixes negative zeros:
    if(result == 0) return (0).toFixed(p);
    else return result;
}

function validateDedInput(el){
    el.value = el.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
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
        monthly_rate.value = monthly_rate_permanent[sg_jg][step];
        var temp_m_rate = parseFloat(monthly_rate.value.replace(/,/g, ''));
        monthly_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        daily_rate.value = "";
        // daily_rate.disabled = true;

    }else if (emp_status == "JOB ORDER" || emp_status == "CONTRACT OF SERVICE"){
        daily_rate.value = daily_rate_jo[sg_jg];
        var temp_m_rate = parseFloat(daily_rate.value.replace(/,/g, ''));
        daily_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        monthly_rate.value = "";
        // monthly_rate.disabled = true;

    
    }else if (emp_status == "CASUAL"){
        daily_rate.value = daily_rate_casual[sg_jg][step];
        var temp_m_rate = parseFloat(daily_rate.value.replace(/,/g, ''));
        daily_rate.value = temp_m_rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    //   daily_rate.value = daily_rate_casual[job_grade][step];
        monthly_rate.value = "";
        // monthly_rate.disabled = true;
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