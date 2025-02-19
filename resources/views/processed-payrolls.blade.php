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
        .spinner-overlay {
            position: relative;
        }

        .spinner-overlay::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8); /* Adjust the background color and opacity as needed */
            z-index: 999; /* Ensure it's on top of everything in the modal */
        }
    </style>
@endpush

@section('content')
<!-- Navbar & Hero Start -->
@section('hero')
{{-- <div class="container-xxl position-relative p-0"> --}}

    <div class="@if(Route::is('process-payroll-jo') ) container-fluid @else container-xxl @endif bg-primary page-header">
        <div class="container text-center">
            {{-- <h1 class="text-white animated zoomIn pt-5">General Payroll</h1>
            <h6 class="text-white animated zoomIn">For the Period of ___</h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item text-white">
                        <i class="bi bi-calendar2-plus"></i>
                        <a class="text-white breadcrumb-link" data-bs-toggle="modal" data-bs-target="#addAttendanceModal" href="#">
                            Attendance
                        </a>
                    </li>
                    <li class="breadcrumb-item text-white" aria-current="/register">
                        <i class="bi bi-recycle"></i>
                        <a class="text-white breadcrumb-link" data-bs-toggle="modal" data-bs-target="#printPayrollModal" href="#">
                            Process Payroll
                        </a>
                    </li>
                    <li class="breadcrumb-item text-white" aria-current="/register">
                        <i class="bi bi-printer"></i>
                        <a class="text-white breadcrumb-link" href="#" data-bs-toggle="modal" data-bs-target="#printPayrollJoModal" >
                            Print
                        </a>
                    </li>
                </ol>
            </nav> --}}
        </div>
    </div>
    
{{-- </div> --}}
@endsection


    <div class="container-fluid">
        <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
            <div class="card-header bg-primary text-white fw-1">{{ __('Processed Payrolls') }}</div>

            <div class="card-body">
                    
                @livewire('list-of-processed-payrolls-component')

            </div>
        </div>
    </div>
    
@endsection
