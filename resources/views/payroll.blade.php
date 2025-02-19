@extends('layouts.app')


@push('styles')
    <style type="text/css">
        .breadcrumb-link:hover {
            text-decoration: underline;
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
            background: rgb(0, 0, 0); /* Adjust the background color and opacity as needed */
            z-index: 2040; /* Ensure it's on top of everything in the modal */
        }
    </style>
@endpush

@section('content')
<!-- Navbar & Hero Start -->
@section('hero')
{{-- <div class="container-xxl position-relative p-0"> --}}

    <div class="container-xxl bg-primary page-header p-5">
        <div class="container text-center">
            {{-- <h1 class="text-white animated zoomIn mt-5">List of Employees</h1> --}}

        </div>
    </div>
    
{{-- </div> --}}
@endsection
    <div class="container-fluid">
        <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
            <div class="card-header bg-primary text-white fw-1">{{ __('List of Employees') }}</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                    
                @livewire('list-of-employees')
            </div>
        </div>
    </div>
    {{-- @livewire('add-attendance') --}}
    @livewire('modals.employee-profile-modal')
    {{-- @livewire('modals.print-payroll') --}}
    @livewire('modals.show-index-modal')
    {{-- @livewire('modals.show-payslip-modal') --}}

@endsection

