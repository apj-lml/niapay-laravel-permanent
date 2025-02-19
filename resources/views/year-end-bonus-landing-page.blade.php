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

    <div class="@if(Route::is('payslip')) container-fluid @else container-xxl @endif bg-primary page-header">
        <div class="container text-center">

        </div>
    </div>
    
{{-- </div> --}}
@endsection

    <div class="container-fluid col-sm-5">
        <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
            <div class="card-header bg-primary text-white fw-1">{{ __('Process Year-end Bonus & Cash Gift') }}</div>

            <div class="card-body">

                {{-- @livewire('modals.show-payslip-modal', ['userId' => request()->segment(2)]) --}}
                    
                @livewire('yearend-bonus-landing-page')

            </div>
        </div>
    </div>
    
@endsection
