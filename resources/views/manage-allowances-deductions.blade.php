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
        .form-control-focus {
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            .was-validated :valid + .form-control-focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
            }

            .was-validated :invalid + .form-control-focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
            }
    </style>
@endpush

@push('scripts')
<script type="module">
    import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";
    // Tags.init('#validationTagsClear');
</script>
@endpush

@section('content')
<!-- Navbar & Hero Start -->
@section('hero')

    <div class="container-xxl bg-primary page-header">
        <div class="container text-center">
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

<div class="container-fluid pt-0">
    <div class="row">
        <div class="col-md-12 p-0 m-0">
            @livewire('system-settings.manage-allowances-component')
        </div>
        <div class="col-md-12 p-0 m-0">
            @livewire('system-settings.manage-deductions-component')
        </div>
    </div>
</div>

@livewire('modals.add-allowance-modal')
@livewire('modals.add-deduction-modal')

@endsection
