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
@php
    $className = "";
@endphp
@if(request()->route()->getName() == 'front.home')
    @php
        $className = "home-page";
    @endphp
@endif
<body class="{{ $className }}">
    @include('frontend.layouts.header')
    <!-- Section login -->
    <section class="section-content" id="sec-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                    @include('frontend.layouts.sidebar')
                <!-- End Sidebar -->
                <!-- Main Content -->
                <!-- <div class="main-content"> -->
                @php
                    $class = "";
                    $cls = "";
                @endphp
                @if(request()->route()->getName() == 'front.home')
                    @php
                        $class = "home-content";
                        $cls = "localpage-content";
                    @endphp
                @endif
                <div class="main-content newpage-content {{ $class }} {{$cls}}">
                    @yield('content')
                </div>
                <!-- End Main Content -->
                <!-- Footer -->
                    @include('frontend.layouts.footer')
                <!-- end footer -->
            </div>
        </div>
    </section>
    <!-- End section login -->
    @include('frontend.layouts.script')
    @yield('script')
</body>
</html>