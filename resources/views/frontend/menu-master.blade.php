@php
  use App\Http\Controllers\Frontend\CommonController as Common;
  $menu = Common::MenuCategory();
  $encrypt = Common::decrypt(request()->route('id'));
  $user_details = Common::userDetails($encrypt);
  $fav_user = Common::getLoginUserFavoriteMenu(request()->route('id'));
  $usrId = Common::encrypt($user_id);
  $user_image = Common::getUserimage();
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
  @php
    $className = "";
  @endphp
  @if(request()->route()->getName() == 'front.edit-menu')
    @php
      $className = "editmenu-page";
    @endphp
  @endif
  <body class="{{$className}}">
    @if(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
      @php
        $user_details = Auth::guard('users')->user();
      @endphp
    @endif
    @include('frontend.layouts.header')
    @php
      $class_name = "";
    @endphp
    @if(request()->route()->getName() == 'front.edit-menu')
        @php
            $class_name = "edit-menu-page";
        @endphp
    @endif
    <section class="section-content section-page-content menupage-content {{ $class_name }}">
      <div class="container-fluid">
        <div class="row">
          <!-- Sidebar -->
            <div class="sidebar mobilesidebar" id="mobilesidebar">
              <div class="sidebar-wrapper">
                  <div class="sidebar-title">
                      <h4><i class="fas fa-sliders-h"></i> Filter</h4>
                  </div>
                  <div class="sidebar-content">
                      <ul class="filter-list">
                        @php
                          $uid = Common::encrypt($user_details->id);
                        @endphp
                          <li>
                              <div class="form-check">
                                @if(request()->route()->getName() == 'front.menu')
                                  <input type="checkbox" class="form-check-input item-list" id="free" data-id="6" name="menu_category" value="6" onchange="menuFilter('{{ route("front.filter-menu-item",[$usrId]) }}','item-section')">
                                @elseif(request()->route()->getName() == 'front.edit-menu')
                                  <input type="checkbox" class="form-check-input item-list" id="free" data-id="6" name="menu_category" value="6" onchange="menuFilter('{{ route("front.filter-category") }}','menu-item')">
                                @endif
                                <label class="form-check-label" for="free">Free</label>
                              </div>
                          </li>
                          <li>
                              <div class="form-check">
                                @if(request()->route()->getName() == 'front.menu')
                                  <input type="checkbox" class="form-check-input item-list" id="pickup" data-id="7" name="menu_category" value="7" onchange="menuFilter('{{ route("front.filter-menu-item",[$usrId]) }}','item-section')">
                                @elseif(request()->route()->getName() == 'front.edit-menu')
                                  <input type="checkbox" class="form-check-input item-list" id="pickup" data-id="7" name="menu_category" value="7" onchange="menuFilter('{{ route("front.filter-category") }}','menu-item')">
                                @endif
                                <label class="form-check-label" for="pickup">Pickup</label>
                              </div>
                          </li>
                          <li>
                              <div class="form-check">
                                @if(request()->route()->getName() == 'front.menu')
                                  <input type="checkbox" class="form-check-input item-list" id="delivery" data-id="8" name="menu_category" value="8" onchange="menuFilter('{{ route("front.filter-menu-item",[$usrId]) }}','item-section')">
                                @elseif(request()->route()->getName() == 'front.edit-menu')
                                  <input type="checkbox" class="form-check-input item-list" id="delivery" data-id="8" name="menu_category" value="8" onchange="menuFilter('{{ route("front.filter-category") }}','menu-item')">
                                @endif
                                <label class="form-check-label" for="delivery">Delivery</label>
                              </div>
                          </li>
                          @if(count($menu) > 0)
                            @foreach($menu as $category)
                              <li>
                                <div class="form-check">
                                  @if(request()->route()->getName() == 'front.menu')
                                    <input type="checkbox" class="form-check-input menu-item" id="category_{{ $category->id }}" name="menu_category" data-name="{{ $category->name }}" value="{{ $category->id }}" onchange="menuFilter('{{ route("front.filter-menu-item",[$usrId]) }}','item-section')">
                                  @elseif(request()->route()->getName() == 'front.edit-menu')
                                    <input type="checkbox" class="form-check-input menu-category" id="category_{{ $category->id }}" data-name="{{ $category->name }}" name="menu_category" value="{{ $category->id }}" onchange="menuFilter('{{ route("front.filter-category") }}','menu-item')">
                                  @endif
                                  <label class="form-check-label" for="category_{{ $category->id }}">{{ ucfirst($category->name) }}</label>
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
          @php
            $class = "";
          @endphp
          @if(request()->route()->getName() == 'front.edit-menu')
              @php
                  $class = "editmenu-content";
              @endphp
          @endif
          <div class="main-content newpage-content {{ $class }}">
            <div class="menu-page-top">
              <div class="row">
                  <div class="col-12">
                      <div class="subheader-col menu-user-col">
                          <div class="user">
                              <div class="u-img">
                                <img src="{{ $user_image }}" />
                              </div>
                            @include('frontend.user-details')
                          </div>
                      </div>
                  </div>
              </div>
              @include('frontend.banner')
            </div>
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
    <!-- End section login -->
    @include('frontend.layouts.script')
    <script src="{{ asset('public/frontend/js/menu.js') }}"></script>
    <script type="text/javascript">
      var csrf_token = '{{ csrf_token() }}';
      var base_url = '{{ url("/") }}';
    </script>
    <script>
      $(document).ready(function() {
          // $('.mobile-foodmap').on('click', function() {
          //     $(".mobile-foodmap").toggleClass("hide-map");
          //     $(".mobile-loc-map-wrapper").toggleClass("hide-map");
          // });
          setInterval(function() {
              var add_section = $(".add-post-toggle").css("display");
              var follow_section = $(".follow-btn-toggle").css("display");
              var follower_section = $(".followers-toggle").css("display");
              if (add_section == 'none') {
                  if (follow_section == 'none') {
                      if (follower_section == 'none') {
                          $("body").removeClass("no-scroll");
                      }
                  }
              }
          }, 500);
          $(".add-post-wrapper").click(function() {
              $(".main-content-body.add-post-toggle").slideToggle();
              $(".main-content-body.follow-btn-toggle").css("display", "none");
              $(".main-content-body.followers-toggle").css("display", "none");
              $(".add-post-wrapper").toggleClass("open");
              $(".follow-wrapper").removeClass("open");
              $(".followers-mobile").removeClass("open");
              var add_section = $(".add-post-toggle").css("display");

              var follow_section = $(".follow-btn-toggle").css("display");
              var follower_section = $(".followers-toggle").css("display");
              if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
                  $("body").addClass("no-scroll");
              }
              // $("body").toggleClass("no-scroll");
          });
          $(".follow-wrapper").click(function() {
              $(".main-content-body.follow-btn-toggle").slideToggle();
              $(".main-content-body.add-post-toggle").css("display", "none");
              $(".main-content-body.followers-toggle").css("display", "none");
              $(".follow-wrapper").toggleClass("open");
              $(".add-post-wrapper").removeClass("open");
              $(".followers-mobile").removeClass("open");
              var add_section = $(".add-post-toggle").css("display");
              var follow_section = $(".follow-btn-toggle").css("display");
              var follower_section = $(".followers-toggle").css("display");
              if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
                  $("body").addClass("no-scroll");
              }
          });
          $(".followers-mobile").click(function() {
              $(".main-content-body.followers-toggle").slideToggle();
              $(".main-content-body.add-post-toggle").css("display", "none");
              $(".main-content-body.follow-btn-toggle").css("display", "none");
              $(".followers-mobile").toggleClass("open");
              $(".add-post-wrapper").removeClass("open");
              $(".follow-wrapper").removeClass("open");
              var add_section = $(".add-post-toggle").css("display");
              var follow_section = $(".follow-btn-toggle").css("display");
              var follower_section = $(".followers-toggle").css("display");
              if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
                  $("body").addClass("no-scroll");
              }
          });
      });
    </script>
    @yield('script')
  </body>
</html>