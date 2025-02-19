@extends('layouts.app')

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

<div class="container-fluid col-sm-8">
    <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
        <div class="card-header bg-primary text-white fw-1">{{ __('Manage Funds') }}</div>

        <div class="card-body">
                
            @livewire('manage-funds-component')
            @livewire('modals.add-fund-modal')

        </div>
    </div>
</div>
    
@endsection
