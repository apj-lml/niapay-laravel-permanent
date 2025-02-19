@extends('layouts.app')


@push('styles')
    <style type="text/css">
        .breadcrumb-link:hover {
            text-decoration: underline;
        }
        .table-hover tbody tr:hover td {
            background-color: rgba(0, 255, 106, 0.162);  /* blue with opacity */
            /* color:white; */
        }
    </style>
@endpush

@section('content')
<!-- Navbar & Hero Start -->
@section('hero')
{{-- <div class="container-xxl position-relative p-0"> --}}

    <div class="container-xxl bg-primary page-header">
        <div class="container text-center">
            <h1 class="text-white animated zoomIn mt-5">System Settings</h1>
            {{-- <h6 class="text-white animated zoomIn">For the Period of ___</h6> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    {{-- <li class="breadcrumb-item text-white">
                        <i class="bi bi-calendar2-plus"></i>
                        <a class="text-white breadcrumb-link" data-bs-toggle="modal" data-bs-target="#addAttendanceModal" href="#">
                            Attendance
                        </a>
                    </li> --}}
                    {{-- <li class="breadcrumb-item text-white" aria-current="/register">
                        <i class="bi bi-printer"></i>
                        <a class="text-white breadcrumb-link" data-bs-toggle="modal" data-bs-target="#printPayrollModal" href="#">
                            Process Payroll
                        </a>
                    </li> --}}
                    {{-- <li class="breadcrumb-item text-white" aria-current="/register">
                        <i class="bi bi-journal-text"></i>
                        <a class="text-white breadcrumb-link" href="/">
                            Index
                        </a>
                    </li> --}}
                </ol>
            </nav>
        </div>
    </div>
    
{{-- </div> --}}
@endsection

        <!-- Service Start -->
        <div class="container-xxl">
            <div class="container">
                {{-- <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Our Services</div>
                    <h2 class="mb-5">We Provide Solutions On Your Business</h2>
                </div> --}}
{{-- 
                <a href="#" data-bs-toggle="modal" data-bs-target="#employeeProfileModal" wire:click="showEmployeeProfile(7)">
                TEST PROFILE
                  </a> --}}

                <div class="row g-4">
                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-item rounded h-100">
                            <div class="d-flex justify-content-between">
                                <div class="service-icon">
                                    <i class="fa fa-building fa-2x"></i>
                                </div>
                                <a class="service-btn" href="{{ route('/manage-units-sections') }}">
                                    <i class="fa fa-link fa-2x"></i>
                                </a>
                            </div>
                            <div class="p-5">
                                <h5 class="mb-3">Manage Units & Sections</h5>
                                <span>Add, Edit, Remove Sections in the system.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item rounded h-100">
                            <div class="d-flex justify-content-between">
                                <div class="service-icon">
                                    <i class="fa fa-chart-pie fa-2x"></i>
                                </div>
                                <a class="service-btn" href="{{ route('/manage-funds') }}">
                                    <i class="fa fa-link fa-2x"></i>
                                </a>
                            </div>
                            <div class="p-5">
                                <h5 class="mb-3">Manage Funds</h5>
                                <span>Add, Edit, Remove Sections in the system.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="service-item rounded h-100">
                            <div class="d-flex justify-content-between">
                                <div class="service-icon">
                                    {{-- <i class="fa fa-chart-line fa-2x"></i> --}}
                                    <i class="fa fa-coins fa-2x"></i>

                                </div>
                                <a class="service-btn" href="{{ route('/manage-allowances-deductions') }}">
                                    <i class="fa fa-link fa-2x"></i>
                                </a>
                            </div>
                            <div class="p-5">
                                <h5 class="mb-3">Manage Allowances & Deductions</h5>
                                <span>Add, Edit, Remove Allowances & Deductions in the system.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="service-item rounded h-100">
                            <div class="d-flex justify-content-between">
                                <div class="service-icon">
                                    {{-- <i class="fa fa-chart-area fa-2x"></i> --}}
                                    {{-- <i class="fa-solid fa-user-pen fa-2x"></i> --}}
                                    <i class="fas fa-user-edit fa-2x"></i>
                                </div>
                                <a class="service-btn" href="{{ route('/manage-signatories') }}">
                                    <i class="fa fa-link fa-2x"></i>
                                </a>
                            </div>
                            <div class="p-5">
                                <h5 class="mb-3">Manage Signatories</h5>
                                <span>Manage Signatories that will be displayed in the payroll.</span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item rounded h-100">
                            <div class="d-flex justify-content-between">
                                <div class="service-icon">
                                    <i class="fa fa-balance-scale fa-2x"></i>
                                </div>
                                <a class="service-btn" href="">
                                    <i class="fa fa-link fa-2x"></i>
                                </a>
                            </div>
                            <div class="p-5">
                                <h5 class="mb-3">legal Advisory</h5>
                                <span>Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</span>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="service-item rounded h-100">
                            <div class="d-flex justify-content-between">
                                <div class="service-icon">
                                    <i class="fa fa-house-damage fa-2x"></i>
                                </div>
                                <a class="service-btn" href="">
                                    <i class="fa fa-link fa-2x"></i>
                                </a>
                            </div>
                            <div class="p-5">
                                <h5 class="mb-3">Tax & Insurance</h5>
                                <span>Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- Service End -->

    {{-- @livewire('system-settings.manage-sections') --}}

    {{-- @livewire('add-attendance') --}}
    @livewire('modals.employee-profile-modal')
    {{-- @livewire('modals.print-payroll') --}}
    {{-- @livewire('modals.add-individual-attendance-modal') --}}
    
@endsection
