@php
  use App\Http\Controllers\Frontend\CommonController as Common;
  $menu = Common::editUserMenu();
  $encrypt = Common::decrypt(request()->route('id'));
  $user_details = Common::userDetails($encrypt);
  $fav_user = Common::getLoginUserFavoriteMenu(request()->route('id'));
@endphp
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
    <meta property="og:title" content="OyeChef - @if(request()->segment(1) == 'menu') {{ ucfirst($user_details->name) }} @if($user_details->user_meta != null) {{ ucfirst($user_details->user_meta->lname) }} @endif @endif" />
    <meta property="og:image" content="{{ asset('public/frontend/img/oyechef-share.jpg') }}" />
    <title>{{ config('app.name', 'Oyechef Food') }}</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('public/frontend/img/favicon.png') }}" width="16"/>
    @include('frontend.layouts.head')
    @yield('css')
    @yield('style')
</head>
<body>
    @if(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
      @php
        $user_details = Auth::guard('users')->user();
      @endphp
    @endif
    <!-- Header  -->
    <header class="header main-header @if(request()->segment(1) == 'my-account'){{'myacc-header'}}@else{{'menupage-header'}}@endif">
      <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="{{ route('front.home') }}">
            <img src="{{ asset('public/frontend/img/logo.png') }}" class="">
        </a>
        <button class="navbar-toggler mobilemenu-icon" type="button" data-bs-toggle="collapse" data-bs-target="#mobilemenu" aria-controls="mobilemenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="mobilemenu">
            <ul class="nav navbar-nav ms-auto">
                <li class="nav-item">
                @if(Auth::guard('users')->check())
                  @if($menu > 0)
                    <a class="nav-link nav-btn" href="{{ route('front.edit-menu') }}">Edit Your Menu</a>
                  @else
                    <a class="nav-link nav-btn" href="{{ route('front.edit-menu') }}">Create Your Menu</a>
                  @endif
                @else
                  <a class="nav-link nav-btn" href="{{ route('front.edit-menu') }}">Create Your Menu</a>
                @endif
                </li>
                <li class="nav-item">
                <a class="nav-link nav-btn" href="{{ route('front.home') }}">Local Food</a>
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
                    @if(Auth::guard('users')->user()->name == null || Auth::guard('users')->user()->email == null || Auth::guard('users')->user()->user_meta->lname == null || Auth::guard('users')->user()->user_meta->phone == null || Auth::guard('users')->user()->user_meta->address == null || Auth::guard('users')->user()->user_meta->user_image == null)
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
            {{--<a class="nav-link nav-btn" href="{{ route('front.login') }}">Sign In</a>--}}
          </li>
        </ul>
        @endif
      </nav>
    @if(request()->segment(1) == 'my-account' || request()->segment(1) == 'edit-menu')
      @if(Auth::guard('users')->check())
        @if(!empty(Auth::guard('users')->user()->user_meta))
          @if(Auth::guard('users')->user()->user_meta->banner_image != null)
            @php
              $banner_image = asset('public/frontend/img/user_banners/'.Auth::guard('users')->user()->user_meta->banner_image);
              $banner = "background-image: url($banner_image) !important";
            @endphp
          @else
            @php
              $banner_image = asset('public/frontend/img/oyechef-banner.jpg');
              $banner = "background-image: url($banner_image) !important";
            @endphp
          @endif
        @else
          @php
            $banner_image = asset('public/frontend/img/oyechef-banner.jpg');
            $banner = "background-image: url($banner_image) !important";
          @endphp
        @endif
      @else
        @php
          $banner_image = asset('public/frontend/img/oyechef-banner.jpg');
          $banner = "background-image: url($banner_image) !important";
        @endphp
      @endif
    @else
      @if($user_details->user_meta->banner_image != null)
        @php
          $banner_image = asset('public/frontend/img/user_banners/'.$user_details->user_meta->banner_image);
          $banner = "background-image: url($banner_image) !important";
        @endphp
      @else
        @php
          $banner_image = asset('public/frontend/img/oyechef-banner.jpg');
          $banner = "background-image: url($banner_image) !important";
        @endphp
      @endif
    @endif
    <div class="sub-header @if(request()->segment(1) == 'my-account'){{'my-profile-header'}}@else{{'menupage-header'}}@endif" style="{{ $banner }}">
        <div class="container-fluid">
            <div class="row align-items-center align-items-lg-end">
                <div class="col-4 col-md-12 col-lg-4">
                    <div class="subheader-col">
                      <div class="user">
                        <div class="u-img">
                          @if(request()->segment(1) == 'my-account' || request()->segment(1) == 'edit-menu')
                            @if(Auth::guard('users')->check())
                              @if(!empty(Auth::guard('users')->user()->user_meta))
                                @if(Auth::guard('users')->user()->user_meta->user_image != null)
                                  <img src="{{ asset('public/frontend/img/user_profiles/'.Auth::guard('users')->user()->user_meta->user_image) }}" class="head-p-img" />
                                @else
                                  <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" class="head-p-img" />
                                @endif
                              @else
                                <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" class="head-p-img" />
                              @endif
                            @else
                              <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
                            @endif
                          @else
                            @if($user_details->user_meta->user_image != null)
                              <img src="{{ asset('public/frontend/img/user_profiles/'.$user_details->user_meta->user_image) }}" />
                            @else
                              <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
                            @endif
                          @endif
                        </div>
                        <div class="u-info">
                          @if(request()->segment(1) == 'menu')
                          <h4 class="u-name">
                            @php
                              $word_count = strlen($user_details->name.' '.$user_details->user_meta->lname);
                            @endphp
                            {{ $user_details->name }} @if($user_details->user_meta != null) @if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) @if($word_count >= 12) {{ ucfirst($user_details->user_meta->lname[0]) }} @else {{ ucfirst($user_details->user_meta->lname) }} @endif @else {{ ucfirst($user_details->user_meta->lname) }} @endif @endif
                          </h4>
                          @elseif(request()->segment(1) == 'my-account' || request()->segment(1) == 'edit-menu')
                            @if(Auth::guard('users')->check())
                            @php
                              $word_count = strlen(Auth::guard('users')->user()->name.' '.Auth::guard('users')->user()->user_meta->lname);
                            @endphp
                            <h4 class="u-name">
                              {{ Auth::guard('users')->user()->name }} @if(Auth::guard('users')->user()->user_meta != null) @if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) @if($word_count >= 12) {{ ucfirst(Auth::guard('users')->user()->user_meta->lname[0]) }} @else {{ ucfirst(Auth::guard('users')->user()->user_meta->lname) }} @endif @else {{ ucfirst(Auth::guard('users')->user()->user_meta->lname) }} @endif @endif
                            </h4>
                            @endif
                          @endif
                          <div class="u-contact">
                            <ul>
                              <li>
                                <a class="active" href="tel:@if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif"><i class="fas fa-phone-alt"></i>
                                  <div class="u-contact-hover">
                                    <i class="fas fa-mobile-alt"></i> @if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif
                                  </div>
                                </a>
                              </li>
                              <li><a @if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) href="sms://@if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif" @else href="tel:@if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif" @endif><!-- <a href="mailto:amp@narola.email"> -->
                                <i class="fas fa-comment-alt"></i>
                                <div class="u-contact-hover">
                                  <i class="fas fa-mobile-alt"></i> @if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif
                                </div>
                              </a>
                              </li>
                                <li class="rating-li"><a href="#"><i class="fas fa-star" @if($user_ratings > 0) style="color: #F6B22C;" @endif></i>
                                  <div class="u-contact-hover">
                                    <span class="star menu-rating">
                                      @if(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
                                        @if(Auth::guard('users')->check())
                                          <span>Cannot rate your own account</span>
                                        @endif
                                      @endif
                                      @if(request()->segment(1) == 'menu')
                                        @if(Auth::guard('users')->check())
                                          @if(request()->route('id') == Common::encrypt(Auth::guard('users')->user()->id))
                                            <span>Cannot rate your own account</span>
                                          @else
                                            @if($user_ratings > 0)
                                              @for($i = 0; $i < 5; $i++)
                                                @if (floor($user_ratings) - $i >= 1)
                                                  <i class="fas fa-star common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                                @elseif ($user_ratings - $i > 0)
                                                  <i class="fas fa-star-half-alt common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                                @else
                                                  <i class="fas fa-star common-star empty-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                                @endif
                                              @endfor
                                            @else
                                              @for($i = 0; $i < 5; $i++)
                                                <i class="fas fa-star empty-star common-star" id="star_{{ $i+1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                              @endfor
                                            @endif
                                          @endif
                                        @else
                                          @if($user_ratings > 0)
                                            @for($i = 0; $i < 5; $i++)
                                              @if (floor($user_ratings) - $i >= 1)
                                                <i class="fas fa-star common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                              @elseif ($user_ratings - $i > 0)
                                                <i class="fas fa-star-half-alt common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                              @else
                                                <i class="fas fa-star common-star empty-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                              @endif
                                            @endfor
                                          @else
                                            @for($i = 0; $i < 5; $i++)
                                              <i class="fas fa-star empty-star common-star" id="star_{{ $i+1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                            @endfor
                                          @endif
                                        @endif
                                    @endif
                                    </span>
                                  </div>
                                </a>
                                </li>
                                @php
                                  $user_id = request()->route('id');
                                @endphp
                                @if(request()->segment(1) == 'menu')
                                  @if(Auth::guard('users')->check())
                                    @php
                                      $userId = Common::encrypt(Auth::guard('users')->user()->id);
                                    @endphp
                                    @if($user_id == $userId)
                                      <!-- <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cannot add your own account to favorites" class="favorite-li"> -->
                                      <li class="favorite-li">
                                        <a href="#">
                                          <i class="fas fa-heart"></i>
                                          <div class="u-favorite-hover">
                                            <span>Cannot add your own account to favorites</span>
                                          </div>
                                        </a>
                                      </li>
                                    @else
                                      <li>
                                        <a href="{{ route('front.add-to-favorites',[$user_id]) }}">
                                          <i class="fas fa-heart" @if($fav_user == 1) style="color: #F6B22C;" @endif></i>
                                        </a>
                                      </li>
                                    @endif
                                  @else
                                    <li>
                                      <a href="{{ route('front.add-to-favorites',[$user_id]) }}">
                                        <i class="fas fa-heart" @if($fav_user == 1) style="color: #F6B22C;" @endif></i>
                                      </a>
                                    </li>
                                  @endif
                                @elseif(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
                                  <!-- <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cannot add your own account to favorites" class="favorite-li"> -->
                                  <li class="favorite-li">
                                    <a href="#">
                                      <i class="fas fa-heart"></i>
                                      <div class="u-favorite-hover">
                                        @if(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
                                          @if(Auth::guard('users')->check())
                                            <span>Cannot add your own account to favorites</span>
                                          @endif
                                        @endif
                                      </div>
                                    </a>
                                  </li>
                                @else
                                  <li style="pointer-events: none;">
                                    <a href="#">
                                      <i class="fas fa-heart"></i>
                                    </a>
                                  </li>
                                @endif
                                <li class="share-li">
                                @if(request()->segment(1) == 'my-account' || request()->segment(1) == 'edit-menu')
                                  @if(Auth::guard('users')->check())
                                    @php
                                      $share_uid = Common::encrypt(Auth::guard('users')->user()->id);
                                    @endphp
                                  @endif
                                @else
                                  @php
                                    $share_uid = request()->route('id');
                                  @endphp
                                @endif
                                  <a href="javascript:void(0)" class="share-a"><i class="fas fa-share-alt"></i>
                                  <div class="u-share-hover">
                                      <a href="https://api.whatsapp.com/send?text={{urlencode(route('front.menu',[$share_uid])) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                      <a href="https://twitter.com/share?url={{ urlencode(route('front.menu',[$share_uid])) }}" class="ml-8"><i class="fab fa-twitter" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Twitter"></i></a>
                                      <a href="https://www.facebook.com/sharer.php?u={{urlencode(route('front.menu',[$share_uid])) }}" class="ml-8" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Facebook"><i class="fab fa-facebook"></i></a>
                                  </div>
                                </a>
                                </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                @if(request()->segment(1) == 'my-account')
                <div class="col-md-6 col-lg-4">
                  <div class="subheader-col">
                    <div class="sh-middle">
                      <div class="page-title my-acc-title">
                        MY ACCOUNT
                      </div>
                    </div>
                  </div>
                </div>
                @else
                <div class="col-8 col-md-6 col-lg-4">
                    <div class="subheader-col">
                      <div class="sh-middle">
                        <div class="page-title">
                          @if(request()->segment(1) == 'edit-menu') Edit Menu @else Menu @endif
                        </div>
                        <div class="store-review">
                            <div class="food-rating">
                              <span class="avg-rating">
                                {{ (isset($menu_ratings) && $menu_ratings > 0) ? $menu_ratings : '' }}
                              </span>
                              <span class="star">
                                @if($menu_ratings > 0)
                                  @for($i = 0; $i < 5; $i++)
                                    @if (floor($menu_ratings) - $i >= 1)
                                      <i class="fas fa-star"> </i>
                                    @elseif ($menu_ratings - $i > 0)
                                      <i class="fas fa-star-half-alt"> </i>
                                    @else
                                      <i class="fas fa-star empty-star"> </i>
                                    @endif
                                  @endfor
                                @else
                                  @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star empty-star"></i>
                                  @endfor
                                @endif
                              </span>
                              <span class="total-rating">
                                ({{ isset($ratings_count) ? $ratings_count : 0 }})
                              </span>
                            </div>
                            <div class="favorites">
                               <i class="fas fa-heart"></i> {{ $fav_user_count }} <small>Added to favorites</small>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                @endif
                @if(request()->segment(1) == 'my-account')
                <div class="col-md-6 col-lg-4">
                  <div class="subheader-col">
                      <div class="edit-banner text-end">
                        <form method="POST" enctype="multipart/formdata">
                          <a href="javascript:void(0)">
                            <input type="file" name="banner_img" onchange="uploadBanner(this)">
                            <i class="fas fa-edit"></i> Change banner
                          </a>
                        </form>
                      </div>
                  </div>
                </div>
                @else
                <div class="col-md-6 col-lg-4">
                    <div class="subheader-col">
                        <div class="store-status">
                          <h4>Home Store</h4>
                          @if(request()->segment(1) == 'edit-menu')
                            @php
                              $user = Auth::guard('users')->user()->id;
                            @endphp
                          @else
                            @php
                              $user = Common::encrypt($user_details->id);
                            @endphp
                          @endif
                          <div id="open_close_shop" class="btn-group" @if(request()->segment(1) == 'menu') style="pointer-events: none;" @endif>
                              <input type="radio" class="btn-check" name="is_store_open_or_close" id="open" onchange="userStoreUpdateStatus('{{ $user }}',this)" value="1" autocomplete="off" {{ ($user_details->user_meta->is_store_open_or_close == 1) ? "checked" : '' }}>
                              <label class="btn {{ ($user_details->user_meta->is_store_open_or_close == 1) ? "btn-checked" : '' }}" for="open">OPEN</label>

                              <input type="radio" class="btn-check" name="is_store_open_or_close" id="close" onchange="userStoreUpdateStatus('{{ $user }}',this)" value="2" autocomplete="off" {{ ($user_details->user_meta->is_store_open_or_close == 2) ? "checked" : '' }}>
                              <label class="btn {{ ($user_details->user_meta->is_store_open_or_close == 2) ? "btn-checked" : '' }}" for="close">CLOSED</label>
                          </div>
                          <p>Open stores will show up on the food map</p>
                          <form method="POST">
                            @csrf
                            @php
                              $delivery_option = json_decode($user_details->user_meta->store_delivery_option);
                            @endphp
                            <div id="pick_del_check" class="btn-group" @if(request()->segment(1) == 'menu') style="pointer-events: none;" @endif>
                                <input type="checkbox" class="btn-check" name="store_delivery_option" id="pickupcheckbox" onchange="userStoreUpdateDeliveryStatus('{{ $user }}',this)" value="0" autocomplete="off" @if($delivery_option != null) @if(in_array(0,$delivery_option)) checked="" @endif @endif>
                                <label class="btn" for="pickupcheckbox">PICKUP</label>

                                <input type="checkbox" class="btn-check" name="store_delivery_option" id="deliverycheckbox" onchange="userStoreUpdateDeliveryStatus('{{ $user }}',this)" value="1" autocomplete="off" @if($delivery_option != null) @if(in_array(1,$delivery_option)) checked="" @endif @endif>
                                <label class="btn" for="deliverycheckbox">DELIVERY</label>
                            </div>
                          </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>

    </header>
         <div class="btn-filter-wrapper">
            <button class="mobilemenu-icon btn btn-filter" type="button" data-bs-toggle="collapse" data-bs-target="#mobilesidebar" aria-controls="mobilesidebar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-sliders-h"></i>
      </button>
         </div>
      @php
  use App\Http\Controllers\Frontend\CommonController;
  $menu = CommonController::MenuCategory();
  $fav_menu = CommonController::userFavoriteMenus();
@endphp
@if(request()->segment(1) == 'menu')
<!-- Sidebar -->
  <div class="sidebar mobilesidebar" id="mobilesidebar">
      <div class="sidebar-wrapper">
          <div class="sidebar-title">
              <h4 class="filter-title">Filter</h4>
          </div>
          <div class="sidebar-content">
              <ul class="filter-list">
                @php
                  $uid = Common::encrypt($user_details->id);
                @endphp
                <li>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input item-list" id="free" data-id="6" name="homemob_menu" value="6" onchange="mobMenuFilterOption('{{ route("front.mobile-filter-option",[$uid]) }}','item-section')">
                    <label class="form-check-label" for="free">Free</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input item-list" id="pickup" data-id="7" name="homemob_menu" value="7" onchange="mobMenuFilterOption('{{ route("front.mobile-filter-option",[$uid]) }}','item-section')">
                    <label class="form-check-label" for="pickup">Pickup</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input item-list" id="delivery" data-id="8" name="homemob_menu" value="8" onchange="mobMenuFilterOption('{{ route("front.mobile-filter-option",[$uid]) }}','item-section')">
                    <label class="form-check-label" for="delivery">Delivery</label>
                  </div>
                </li>
                @if(count($menu) > 0)
                  @foreach($menu as $category)
                    <li>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input item-list" id="item_{{ $category->id }}" data-id="{{ $category->id }}" name="homemob_menu" value="{{ $category->id }}" onchange="mobMenuFilterOption('{{ route("front.mobile-filter-option",[$uid]) }}','item-section')">
                        <label class="form-check-label" for="item_{{ $category->id }}">{{ $category->name }}</label>
                      </div>
                    </li>
                  @endforeach
                @endif
              </ul>
          </div>
        </div>
    @include('frontend.fav-menu')
  </div>
<!-- End Sidebar -->
@endif
    <!-- Header -->
    <!-- Section login -->
    <section class="section-content @if(request()->segment(1) == 'my-account'){{'section-myprofile'}}@else{{'section-page-content'}}@endif @if(request()->segment(1) == 'edit-menu'){{'edit-menu-page'}}@endif">
        <div class="container-fluid">
            <div class="row">
              <!-- Sidebar -->
                @yield('sidebar')
              <!-- End Sidebar -->
              <!-- Main Content -->
              @if(request()->segment(1) == 'my-account')
              <div class="my-profile-wrapper">
                @yield('content')
              </div>
              @else
              <div class="main-content">
                @yield('content')
              </div>
              @endif
              <!-- End Main Content -->
              <!-- Footer -->
              <footer>
                <div class="row">
                  <div class="col-12">
                    <p class="footer-text">
                      © 2021 OyeChef • All Rights Reserved
                    </p>
                  </div>
                </div>
              </footer>
              <!-- end footer -->
            </div>
        </div>
    </section>
    <div class="overlap-ratings"></div>
    <div class="overlap-favorites"></div>
    <div class="overlap-sharing"></div>
    <!-- End section login -->
    @include('frontend.layouts.script')
    <script src="{{ asset('public/frontend/js/menu.js') }}"></script>
    <script type="text/javascript">
      var csrf_token = '{{ csrf_token() }}';
      var base_url = '{{ url("/") }}';
    </script>
    @yield('script')
</body>
</html>