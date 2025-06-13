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

    <div class="container-fluid">
        <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
            <div class="card-header bg-primary text-white fw-1">{{ __('Edit/Adjust Data to be Uploaded') }}</div>
            <div class="card-body">
                <p style="color: red; font-weight: bolde;"><i>NOTE! <br>
                    1. Employees who are inactive or not included in payroll will not show here and will not be updated.<br>
                    2. Data highlighted in red means that the value is not equal in the current deduction saved in the database.<br>
                    3. Only all checked data will be updated.<br>
                
                </i></p>
                @if ($selectedDeductionType == 'GSIS')
                    @livewire('edit-uploaded-deduction-component', [
                        "file" => $file,
                        "selectedDeductionType" => $selectedDeductionType
                    ])
                @elseif ($selectedDeductionType == 'HDMF')
                    @livewire('hdmf-edit-uploaded-deduction-component', [
                        "file" => $file,
                        "selectedDeductionType" => $selectedDeductionType
                    ])
                @endif


            </div>
        </div>
    </div>
    
@endsection
