@extends('layouts.app')

@push('mystyles')

@endpush

@section('content')

@section('hero')
    <div class="container-xxl bg-white p-0">
        <div class="container-xxl bg-primary hero-header">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="text-white mb-4 animated zoomIn">Pangasinan IMO's <br> Payroll System</h1>
                        <p class="text-white pb-3 animated zoomIn">
                            A comprehensive and efficient platform designed to streamline and manage the payroll processes for employees, ensuring accuracy and compliance with organizational and regulatory requirements.
                        </p>
                        {{-- <a href="" class="btn btn-outline-light rounded-pill border-2 py-3 px-5 animated slideInRight">Learn More</a> --}}
                    </div>
                    <div class="col-lg-6 text-center text-lg-start">
                        <img class="img-fluid animated zoomIn" src="img/hero.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
        <!-- Navbar & Hero End -->

    @livewire('landing-page')
        
@endsection
