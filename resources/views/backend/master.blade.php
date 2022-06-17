<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Food') }}</title>
    @include('backend.layouts.head')
    @yield('css')
    @yield('style')
</head>
<body>
    @include('backend.layouts.header')
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            @include('backend.layouts.sidebar')
            <div class="content-wrapper">
                <!-- Page header -->
                @yield('breadcrumb')
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">
                    @yield('content')
                    @include('backend.layouts.footer')
                </div>
                <!-- /content area -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
    @include('backend.layouts.script')
    @yield('script')
</body>
</html>