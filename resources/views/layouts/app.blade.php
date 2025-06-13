<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <title>NIAPay</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet"> 


    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/DataTables/datatables.css') }}" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

    <!-- Customized Bootstrap Stylesheet -->
    {{-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    <!-- Choices.js Stylesheet -->
    <link href="{{ asset('css/choices.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/base.min.css') }}" rel="stylesheet"> --}}

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> --}}

    {{-- <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/date-1.5.1/fh-3.4.0/kt-2.11.0/r-2.5.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/datatables.min.css" rel="stylesheet"> --}}
 
    <style>
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #e7f9ff;
        }
    </style>

    @stack('styles')

    @livewireStyles

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>

<body>
    <div class="@if(Route::is('process-payroll-jo') || Route::is('year-end-bonus')) container-fluid @else container-xxl @endif bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                {{-- <span class="sr-only">Loading...</span> --}}
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="@if(Route::is('process-payroll-jo') || Route::is('year-end-bonus')) container-fluid @else container-xxl @endif position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0" style="z-index: 1000;">
                <a href="/" class="navbar-brand p-0">
                    <h1 class="m-0">NIAPay</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="/" class="nav-item nav-link {{ (request()->routeIs('/')) ? 'active' : '' }}">Home</a>
                        {{-- <a href="about.html" class="nav-item nav-link">About</a> --}}
                        {{-- <a href="service.html" class="nav-item nav-link">Service</a> --}}
                        {{-- <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0">
                                <a href="feature.html" class="dropdown-item">Features</a>
                                <a href="quote.html" class="dropdown-item">Free Quote</a>
                                <a href="team.html" class="dropdown-item">Our Team</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="404.html" class="dropdown-item">404 Page</a>
                            </div>
                        </div> --}}
                        {{-- <a href="contact.html" class="nav-item nav-link">Contact</a> --}}
                        @auth
                        @if (Route::has('login'))
                            {{-- @auth
                                <a href="{{ url('/home') }}" class="nav-item nav-link">Home</a>
                            @else --}}
                                @if (Route::has('register'))
                                    {{-- <a href="{{ route('register') }}" class="nav-item nav-link {{ (request()->routeIs('register')) ? 'active' : '' }}">Register</a> --}}
                                    <a href="{{ route('register') }}" class="nav-item nav-link {{ (request()->routeIs('register')) ? 'active' : '' }}">Register</a>
                                @endif
                            {{-- @endauth --}}
                            
                        <a href="{{ route('payroll') }}" class="nav-item nav-link {{ (request()->routeIs('/payroll')) ? 'active' : '' }}">List of Employees</a>
                        {{-- <a href="{{ route('payroll') }}" class="nav-item nav-link {{ (request()->routeIs('/payroll')) ? 'active' : '' }}">Process Payroll</a> --}}
                        {{-- <a href="{{ route('payroll-finder') }}" class="nav-item nav-link {{ (request()->routeIs('payroll-finder')) ? 'active' : '' }}">Payroll</a> --}}

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Process Payrolls</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('process-payroll') }}" class="dropdown-item">Wages</a>
                                <a href="{{ route('mid-year-bonus-landing-page') }}" class="dropdown-item">Mid-year Bonus (MYB)</a>
                                <a href="{{ route('year-end-bonus-landing-page') }}" class="dropdown-item">Year-end Bonus & Cash Gift</a>
                                <a href="{{ route('cna-landing-page') }}" class="dropdown-item">Collective Negotiation Agreement (CNA) Incentive</a>
                                <a href="{{ route('pei-landing-page') }}" class="dropdown-item">Productive Enhancement Incentive (PEI)</a>
                                <a href="{{ route('ua-landing-page') }}" class="dropdown-item">Uniform Allowance</a>
                                <a href="{{ route('payroll-finder') }}" class="dropdown-item">Payroll Finder</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Deductions</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('upload-deductions-landing-page') }}" class="dropdown-item">Upload Deductions</a>
                                <a href="{{ route('/deduction-summary') }}" class="dropdown-item">Deduction Summary</a>
                            </div>
                        </div>
                        {{-- <a href="{{ route('/deduction-summary') }}" class="nav-item nav-link {{ (request()->routeIs('/deduction-summary')) ? 'active' : '' }}">Deduction Summary</a> --}}
                        <a href="{{ route('process-payslip') }}" class="nav-item nav-link {{ (request()->routeIs('/process-payslip')) ? 'active' : '' }}">Payslip</a>
                        {{-- <a href="{{ route('processed-payrolls') }}" class="nav-item nav-link {{ (request()->routeIs('/processed-payrolls')) ? 'active' : '' }}">Processed Payrolls</a> --}}
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->first_name }}</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('/change-password') }}" class="dropdown-item">Change Password</a>
                                <a href="{{ route('/list-of-admin') }}" class="dropdown-item">List of Admins</a>
                                <a href="{{ route('/system-settings') }}" class="dropdown-item">System Settings</a>
                                {{-- <a href="quote.html" class="dropdown-item">Free Quote</a>
                                <a href="team.html" class="dropdown-item">Our Team</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="404.html" class="dropdown-item">404 Page</a> --}}
                            </div>
                        </div>
                        @endauth
                    
                        @endif
                    </div>
                    @auth
                        <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                        class="btn btn-light text-primary py-2 px-4 ms-lg-5">
                        {{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-light text-primary py-2 px-4 ms-lg-5 {{ (request()->routeIs('login')) ? 'active' : '' }}">Login</a>
                        @endif
                    @endauth

                </div>
            </nav>
            @yield('hero')
        </div>

            <main class="">
                @yield('content')
            </main>
    

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light footer pt-5 wow fadeIn" data-wow-delay="0.1s" style="margin-top: 6rem;">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-white mb-4">Get In Touch</h5>
                        <p><i class="fa fa-map-marker-alt me-3"></i>Bayaoas, Urdaneta City, Pangasinan</p>
                        <p><i class="fa fa-phone-alt me-3"></i>(075) 632-5149</p>
                        <p><i class="fa fa-envelope me-3"></i>pimo.adm02@gmail.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 col-lg-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <p>Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulpu</p>
                        <div class="position-relative w-100 mt-3">
                            <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Your Email" style="height: 48px;">
                            <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-6 col-lg-4">
                        <h5 class="text-white mb-4">Quick Link</h5>
                        <a class="btn btn-link" href="">About Us</a>
                        <a class="btn btn-link" href="">Contact Us</a>
                        <a class="btn btn-link" href="">Privacy Policy</a>
                        <a class="btn btn-link" href="">Terms & Condition</a>
                        <a class="btn btn-link" href="">Career</a>
                    </div> --}}
                    {{-- <div class="col-md-6 col-lg-3">
                        <h5 class="text-white mb-4">Popular Link</h5>
                        <a class="btn btn-link" href="">About Us</a>
                        <a class="btn btn-link" href="">Contact Us</a>
                        <a class="btn btn-link" href="">Privacy Policy</a>
                        <a class="btn btn-link" href="">Terms & Condition</a>
                        <a class="btn btn-link" href="">Career</a>
                    </div> --}}
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">NIAPay</a>, All Right Reserved. 
							
							<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
							Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                            <br>Distributed By: <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="/">Home</a>
                                <a href="">Cookies</a>
                                {{-- <a href="">Help</a>
                                <a href="">FQAs</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="{{ asset('lib/jquery/jquery-3.7.1.min.js') }}"></script> 
    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('lib/bootstrap5/dist/js/bootstrap.bundle.min.js') }}"></script> 


{{-- /* ------------------------------ Font Awesome ------------------------------ */ --}}
    <script src="https://kit.fontawesome.com/dce3348e0b.js" crossorigin="anonymous"></script>
    
    <!-- Alpine v3 -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <!-- Focus plugin -->
    {{-- <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script> --}}



    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('lib/listjs/list.min.js') }}"></script>


    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Include Choices JavaScript -->
    <script src="{{ asset('js/choices.min.js') }}"></script>

    {{-- SWAL --}}
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('lib/swal2/dist/sweetalert2.all.min.js') }}"></script>


    <script src="{{ asset('lib/DataTables/datatables.js') }}"></script>
 
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/date-1.5.1/fh-3.4.0/kt-2.11.0/r-2.5.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/datatables.min.js"></script>
     --}}
    @livewireScripts

    @stack('scripts')
    
    @stack('alerts')


    <script type="text/javascript">

        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    // toast.addEventListener('mouseenter', Swal.stopTimer)
                    // toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

        window.addEventListener('fireToast', event => {
            Toast.fire({
                icon: event.detail.icon,
                title: event.detail.title
                })
        })

        window.addEventListener('closeModal', event => {
            var myModalEl = document.getElementById(event.detail.id);
            var modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        })
      
      
    </script>

</body>

</html>

