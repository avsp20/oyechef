@php
    use App\Http\Controllers\Frontend\CommonController as Common;
@endphp
@if(request()->segment(1) == "profile")
    @php
        $username = Common::getUsername();
    @endphp
@endif
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="{{ url('/') }}">
        <meta name="description" content="Home Cooks - Local food by local chefs">
        <meta name="generator" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@nytimesbits" />
        <meta name="twitter:creator" content="@nickbilton" />
        <meta property="og:url" content="{{ url()->current() }}" />
        @if(request()->segment(1) == "profile" )
            <meta property="og:title" content="OyeChef - {{ $username }}" />
        @endif
        <meta property="og:image" content="{{ asset('public/frontend/img/oyechef-share.jpg') }}" />
        <title>{{ config('app.name', 'Oyechef Food') }}</title>
        <link rel="shortcut icon" type="image/jpg" href="{{ asset('public/frontend/img/favicon.png') }}" width="16"/>
        @include('frontend.layouts.head')
        @yield('css')
        @yield('style')
    </head>
    @php $cls = ''; @endphp
    @if(request()->route()->getName() == "news-feed.index")
        @php
            $cls = "show-logo news-feed-page";
        @endphp
    @elseif(request()->route()->getName() == "front.my-account")
        @php
            $cls = "account-page";
        @endphp
    @endif
    <body class="{{ $cls }}">
        @include('frontend.layouts.header')
        <!-- Section login -->
        <section class="section-content @if(request()->route()->getName() == "news-feed.index"){{"newsfeed-content"}}@endif" id="sec-content">
            <div class="container-fluid">
                <div class="row">
                    @php $class = ''; @endphp
                    @if(request()->route()->getName() != "front.my-account")
                        @include('frontend.profile.layouts.sidebar')
                    @endif
                    @if(request()->route()->getName() == "front.profile")
                        @php
                            $class = "profilepage-content";
                        @endphp
                    @elseif(request()->route()->getName() == "front.my-account")
                        @php
                            $class = "my-acc-wrapper";
                        @endphp
                    @endif
                    <!-- Main Content -->
                    <div class="main-content newpage-content {{$class}}">
                        @yield('content')
                    </div>
                    <!-- End Main Content -->
                    <!-- Footer -->
                    @include('frontend.layouts.footer')
                    <!-- end footer -->
                </div>
            </div>
        </section>
        <div class="overlap-ratings"></div>
        <div class="overlap-favorites"></div>
        <div class="overlap-sharing"></div>
        @include('frontend.profile.layouts.script')
        <script src="{{ asset('public/frontend/js/profile.js') }}"></script>
        <script type="text/javascript">
          var csrf_token = '{{ csrf_token() }}';
          var base_url = '{{ url("/") }}';
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                @if(Session::has('level'))
                    new Noty({
                        text: "{{ session('content') }}",
                        type: "{{ session('level') }}"
                    }).show();
                @endif
            });
            var twitterHandle = 'sarbbottam';
            function tweetCurrentPage() {
                window.open('https://twitter.com/share?url='+escape(window.location.href)+'&text='+document.title + ' via @' + twitterHandle, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                return false; 
            }
        </script>
        @yield('script')
    </body>
</html>