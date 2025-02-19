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

        </div>
    </div>
    
{{-- </div> --}}
@endsection


    <div class="container-fluid">
        <div class="card wow fadeInUp shadow-sm" data-wow-delay="0.1s">
            <div class="card-header bg-primary text-white fw-1">{{ __('Casual') }}</div>

            <div class="card-body">
                @livewire('process-payroll-job-order-component')
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchWindow" tabindex="-1" aria-labelledby="searchWindowModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchWindowModalLabel">Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search_box">
                        <input type="text" id="searchInput" size="20">
                        {{-- <button class="btn btn-primary" onclick="findString()">Find</button> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="findString()">Find</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    {{-- @livewire('add-attendance') --}}
    {{-- @livewire('modals.add-individual-attendance-modal') --}}


    @livewire('modals.employee-profile-modal')


    @livewire('modals.print-payroll-jo-modal')

@push('scripts')

<script>
    document.addEventListener("keydown", function (event) {
        if (event.ctrlKey && event.keyCode == 70) {
            event.stopPropagation();
            event.preventDefault();
            
            // document.getElementById('addIndividualAttendance').setAttribute('disabled', 'disabled');

            var searchWindow = new bootstrap.Modal(document.getElementById('searchWindow'), {
                backdrop: false
            });
            searchWindow.show();
        }
    });
    
    var TRange = null;
    
    function findString() {
        str = document.getElementById('searchInput').value;
        if (parseInt(navigator.appVersion) < 4) return;
        var strFound;
        if (window.find) {
            // CODE FOR BROWSERS THAT SUPPORT window.find
            strFound = self.find(str);
            if (strFound && self.getSelection && !self.getSelection().anchorNode) {
                strFound = self.find(str)
            }
            if (!strFound) {
                strFound = self.find(str, 0, 1)
                while (self.find(str, 0, 1)) continue
            }
        } else if (navigator.appName.indexOf("Microsoft") != -1) {
            // EXPLORER-SPECIFIC CODE
            if (TRange != null) {
                TRange.collapse(false)
                strFound = TRange.findText(str)
                if (strFound) TRange.select()
            }
            if (TRange == null || strFound == 0) {
                TRange = self.document.body.createTextRange()
                strFound = TRange.findText(str)
                if (strFound) TRange.select()
            }
        } else if (navigator.appName == "Opera") {
            alert("Opera browsers not supported, sorry...")
            return;
        }
        if (!strFound) alert("String '" + str + "' not found!")
        event.preventDefault(); // Prevent form submission
        return;
    }
    </script>
@endpush
      


    
@endsection
