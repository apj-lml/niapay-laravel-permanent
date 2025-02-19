@extends('layouts.app')

@section('content')
    <!-- Navbar & Hero Start -->
    @section('hero')
    {{-- <div class="container-xxl position-relative p-0"> --}}
        <div class="container-xxl bg-primary page-header">
            <div class="container text-center">
                <h1 class="text-white animated zoomIn mt-5">List of Admins</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-white bread-crumb-link" href="/">Home</a></li>
                        {{-- <li class="breadcrumb-item text-white active" aria-current="/register">Login</li> --}}
                    </ol>
                </nav>
            </div>
        </div>
        
    {{-- </div> --}}
    @endsection
    <!-- Navbar & Hero End -->
{{-- <div class="container-xxl py-6"> --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        @livewire('list-of-admin-components')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('modals.employee-profile-modal')
{{-- </div> --}}
@endsection
