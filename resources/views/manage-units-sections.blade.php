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

    <div class="container-xxl bg-primary page-header">
        <div class="container text-center">
            {{-- <h1 class="text-white animated zoomIn mt-5">System Settings</h1> --}}
            {{-- <h6 class="text-white animated zoomIn">For the Period of ___</h6> --}}
            <nav aria-label="breadcrumb">
                {{-- <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item text-white">
                        <i class="bi bi-calendar2-plus"></i>
                        <a class="text-white breadcrumb-link" data-bs-toggle="modal" data-bs-target="#addAttendanceModal" href="#">
                            Attendance
                        </a>
                    </li>
                    <li class="breadcrumb-item text-white" aria-current="/register">
                        <i class="bi bi-printer"></i>
                        <a class="text-white breadcrumb-link" data-bs-toggle="modal" data-bs-target="#printPayrollModal" href="#">
                            Process Payroll
                        </a>
                    </li>
                    <li class="breadcrumb-item text-white" aria-current="/register">
                        <i class="bi bi-journal-text"></i>
                        <a class="text-white breadcrumb-link" href="/">
                            Index
                        </a>
                    </li>
                </ol> --}}
            </nav>
        </div>
    </div>

@endsection

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 p-0 m-0">
            @livewire('system-settings.manage-sections-component')
        </div>
        <div class="col-md-6 p-0 m-0">
            @livewire('system-settings.manage-units-component')
        </div>
    </div>
</div>

@livewire('modals.add-units-modal')
@livewire('modals.add-sections-modal')

    {{-- @livewire('add-attendance') --}}
    {{-- @livewire('modals.employee-profile-modal') --}}
    {{-- @livewire('modals.print-payroll') --}}
    {{-- @livewire('modals.add-individual-attendance-modal') --}}
    
@endsection
