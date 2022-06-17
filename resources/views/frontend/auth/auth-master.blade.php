<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Oyechef Food') }}</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('public/frontend/img/favicon.png') }}" width="16"/>
    @include('frontend.layouts.head')
    @yield('css')
    @yield('style')
</head>
<body>
    <!-- Header  -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
          <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
              <img src="{{ asset('public/frontend/img/logo.png') }}" class="">
            </a>
          </div>
        </nav>
    </header>
    <!-- Header -->
    <!-- Section login -->
    <section class="form-section @if((request()->segment(1) == 'password') && (request()->segment(2) == 'reset')){{ 'forgot-password-section' }}@endif">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="form-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End section login -->
    @include('frontend.layouts.script')
    @yield('script')
</body>
</html>