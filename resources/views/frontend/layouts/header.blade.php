@php
  use App\Http\Controllers\Frontend\CommonController;
  $menu = CommonController::editUserMenu();
@endphp
<!-- Header  -->
  @php
    $class_name = "";
  @endphp
  @if(request()->route()->getName() == "front.menu" || request()->route()->getName() == "front.my-account" )
    @php
      $class_name = "d-none d-sm-block";
    @endphp
  @elseif(request()->route()->getName() == "front.edit-menu" || request()->route()->getName() == "profile.index")
    @php
      $class_name = "d-none d-md-block";
    @endphp
  @endif
 <!-- d-none d-sm-block -->
  <header class="header main-header home-header new-header">
      <nav class="navbar navbar-expand">
          <a class="navbar-brand {{$class_name}}" href="{{ url('/') }}">
              <img src="{{ asset('public/frontend/img/logo.png') }}" class="">
          </a>

          <ul class="nav navbar-nav ms-auto acc-ul">
              <li class="nav-item @if(request()->route()->getName() == "news-feed.index"){{ "active" }}@endif">
                <a class="nav-link" href="{{ route('news-feed.index') }}">
                  <img src="{{ asset('public/frontend/img/icons/newspaper-folded.png') }}" width="35">
                </a>
              </li>
              <li class="nav-item @if(request()->route()->getName() == "front.home"){{ "active" }}@endif">
                @if(request()->route()->getName() == "front.home")
                  <a class="nav-link" href="#">
                      <img class="home-img" src="{{ asset('public/frontend/img/icons/cup.png') }}" width="35">
                  </a>
                @else
                  @if(request()->route()->getName() == "front.menu" || request()->route()->getName() == "front.edit-menu")
                    @php
                      $img = asset('public/frontend/img/icons/cup-yellow.png');
                      $class = "icon-yellow";
                    @endphp
                  @else
                    @php
                      $img = asset('public/frontend/img/icons/cup.png');
                      $class = "home-img";
                    @endphp
                  @endif
                  <a class="nav-link" href="{{ route('front.home') }}">
                    <img class="{{ $class }}" src="{{ $img }}" width="35">
                  </a>
                @endif
              </li>
              @if(Auth::guard('users')->check())
              <li class="nav-item menu-profile-img">
                @if(!empty(Auth::guard('users')->user()->user_meta))
                  @php
                    $incomplete = 0;
                  @endphp
                  @if(Auth::guard('users')->user()->name == null || Auth::guard('users')->user()->email == null || Auth::guard('users')->user()->user_meta->lname == null || Auth::guard('users')->user()->user_meta->phone == null || Auth::guard('users')->user()->user_meta->address == null)
                    @php $incomplete = 1; @endphp
                  @endif
                  @if(Auth::guard('users')->user()->user_meta->user_image != null)
                    <a class="nav-link" href="{{ route('profile.index',[CommonController::encrypt(Auth::guard('users')->user()->id)]) }}"><img src="{{ asset('public/frontend/img/user_profiles/'.Auth::guard('users')->user()->user_meta->user_image) }}" class="head-p-img" width="35" /></a>
                    @if($incomplete == 1)<span class="incomplete-profile"><i class="fas fa-exclamation-circle"></i></span>@endif
                  @else
                    <a class="nav-link" href="{{ route('profile.index',[CommonController::encrypt(Auth::guard('users')->user()->id)]) }}"><img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" width="35" /></a>
                    @if($incomplete == 1)<span class="incomplete-profile"><i class="fas fa-exclamation-circle"></i></span>@endif
                  @endif
                @else
                  <a class="nav-link" href="{{ route('profile.index') }}"><img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" width="35" /></a>
                  @if($incomplete == 1)<span class="incomplete-profile"><i class="fas fa-exclamation-circle"></i></span>@endif
                @endif
                  {{--<a class="nav-link" href="Profile.html"><img src="{{ asset('public/frontend/img/p-img.png') }}" width="35" /></a>--}}
              </li>
              @else
                <li class="nav-item menu-profile-img">
                  <a class="nav-link" href="{{ route('front.login') }}"><img src="{{ asset('public/frontend/img/usr-avtar.png') }}" width="35" /></a>
                </li>
              @endif
          </ul>
          <!-- <div class="mobile-foodmap hide-map">
              <h4><a href="#">Food Map</a></h4>
          </div> -->
          <div class="mobile-loc-map-wrapper hide-map">
              <img src="{{ asset('public/frontend/img/location-map.png') }}" class="mw-100" />
          </div>
      </nav>
  </header>
  @if(request()->route()->getName() == "front.menu")
    <div class="btn-filter-wrapper menupage-filterbtn">
      <button class="mobilemenu-icon btn btn-filter" type="button" data-bs-toggle="collapse" data-bs-target="#mobilesidebar" aria-controls="mobilesidebar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  @endif
<!-- Header -->
<!-- Header  -->
	{{--<header class="header main-header home-header">
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('public/frontend/img/logo.png') }}" class="">
      </a>
      <button class="mobilemenu-icon btn btn-filter" type="button" data-bs-toggle="collapse" data-bs-target="#mobilesidebar" aria-controls="mobilesidebar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-sliders-h"></i>
      </button>
      <button class="navbar-toggler mobilemenu-icon" type="button" data-bs-toggle="collapse" data-bs-target="#mobilemenu" aria-controls="mobilemenu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <!-- <div class="header-search d-flex search-form"> -->
      <form class="header-search d-flex" method="POST" id="search_form">
        @csrf
        <input class="form-control" type="search" name="search" id="search_data" placeholder="Search" aria-label="Search">
        <button class="btn btn-search" type="submit" id="search_btn">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.8026 18.645L14.5483 13.3907C15.8752 11.7595 16.5256 9.68135 16.3655 7.58473C16.2054 5.4881 15.2469 3.53284 13.6877 2.12206C12.1285 0.711281 10.0874 -0.0474494 7.98525 0.00230061C5.88311 0.0520507 3.88019 0.906489 2.38945 2.38945C0.906489 3.88019 0.0520507 5.88311 0.00230061 7.98525C-0.0474494 10.0874 0.711281 12.1285 2.12206 13.6877C3.53284 15.2469 5.4881 16.2054 7.58473 16.3655C9.68135 16.5256 11.7595 15.8752 13.3907 14.5483L18.645 19.8026C18.8021 19.9371 19.0041 20.0074 19.2107 19.9994C19.4173 19.9914 19.6133 19.9058 19.7595 19.7595C19.9058 19.6133 19.9914 19.4173 19.9994 19.2107C20.0074 19.0041 19.9371 18.8021 19.8026 18.645ZM3.54704 12.8406C2.62807 11.9222 2.00211 10.752 1.74834 9.47781C1.49457 8.20364 1.62438 6.88283 2.12136 5.68246C2.61835 4.48208 3.46017 3.45606 4.54035 2.73418C5.62053 2.0123 6.89053 1.62699 8.18972 1.62699C9.48891 1.62699 10.7589 2.0123 11.8391 2.73418C12.9193 3.45606 13.7611 4.48208 14.2581 5.68246C14.7551 6.88283 14.8849 8.20364 14.6311 9.47781C14.3773 10.752 13.7514 11.9222 12.8324 12.8406C12.2254 13.455 11.5024 13.9427 10.7055 14.2756C9.9085 14.6085 9.05341 14.78 8.18972 14.78C7.32604 14.78 6.47094 14.6085 5.674 14.2756C4.87705 13.9427 4.1541 13.455 3.54704 12.8406Z" fill="#030303"/>
          </svg>
          </button>
        <!-- </div> -->
      </form>
      <div class="collapse navbar-collapse" id="mobilemenu">
      <ul class="nav navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link nav-btn" href="{{ route('front.edit-menu') }}">
            @if(!Auth::guard('users')->check()) Create Your Menu
            @else
              @if($menu > 0) Edit @else Create @endif Your Menu
            @endif
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-btn" href="#">Local Food</a>
        </li>
      </ul>
    </div>
    @if(Auth::guard('users')->check())
      <ul class="nav navbar-nav ms-auto acc-ul">
        <li class="nav-item dropdown acc-dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
            @if(!empty(Auth::guard('users')->user()->user_meta))
              @php
                $incomplete = 0;
              @endphp
              @if(Auth::guard('users')->user()->name == null || Auth::guard('users')->user()->email == null || Auth::guard('users')->user()->user_meta->lname == null || Auth::guard('users')->user()->user_meta->phone == null || Auth::guard('users')->user()->user_meta->address == null)
                @php $incomplete = 1; @endphp
              @endif
              @if(Auth::guard('users')->user()->user_meta->user_image != null)
                <img src="{{ asset('public/frontend/img/user_profiles/'.Auth::guard('users')->user()->user_meta->user_image) }}" class="head-p-img" />
                @if($incomplete == 1)<span class="incomplete-profile"><i class="fas fa-exclamation-circle"></i></span>@endif
              @else
                <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" class="head-p-img" />
                @if($incomplete == 1)<span class="incomplete-profile"><i class="fas fa-exclamation-circle"></i></span>@endif
              @endif
            @else
              <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" class="head-p-img" />
              @if($incomplete == 1)<span class="incomplete-profile"><i class="fas fa-exclamation-circle"></i></span>@endif
            @endif
          </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="{{ route('front.my-account') }}" ><i class="fas fa-user-circle"></i> My Account</a>
              </li>
              <li>
                <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
                <form id="logout-form" action="{{ route('front.logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
        </li>
      </ul>
    @else
     <ul class="nav navbar-nav ms-auto acc-ul">
        <li class="nav-item dropdown acc-dropdown">
          <a href="{{ route('front.login') }}" class="nav-link"><img src="{{ asset('public/frontend/img/usr-avtar.png') }}" class="sign-in-img head-p-img"></a>
        </li>
      </ul>
    @endif
      <div class="mobile-foodmap hide-map">
        <h4><a href="#">Food Map</a></h4>
      </div>
      <div class="mobile-loc-map-wrapper hide-map" >
        <div id="food_mobile_map"></div>
     </div>
    </nav>
  </header>--}}
<!-- Header -->
