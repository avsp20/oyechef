@php
    use App\Http\Controllers\Frontend\CommonController as Common;
    $username = Common::getUsername();
    $banner = Common::userBanner();
    $image = Common::getUserimage();
@endphp
<div class="row menu-banner-row">
  <div class="col-12">
      <div class="subheader-col" style="{{ $banner }}">
          @if(request()->segment(1) != "my-account")
          <div class="mobile-user">
              <div class="img">
                  <img src="{{ $image }}">
                  <!-- <img src="img/user-img.png"> -->
              </div>
              <h4 class="username">{{ $username }}</h4>
          </div>
          @endif
          <div class="sh-middle">
            <div class="page-title d-none d-sm-block">
                @if(request()->segment(1) == 'edit-menu') Edit Menu @elseif(request()->segment(1) == 'my-account') My Account @else Menu @endif
            </div>
            @if(request()->segment(1) == 'menu' || request()->segment(1) == 'edit-menu')
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
                    <i class="fas fa-heart"></i>{{ $fav_user_count }} <small>Added to favorites</small>
                </div>
              </div>
            @endif
          </div>
          @if(request()->segment(1) == "my-account")
            <div class="edit-banner text-end">
              <a href="javascript:void(0)">
                <input type="file" name="banner_img" onchange="uploadBanner(this)">
                <i class="fas fa-edit"></i> Change banner
              </a>
            </div>
          @endif
      </div>
  </div>
</div>
{{--<div class="row menu-banner-row">
  <div class="col-12">
      <div class="subheader-col" style="{{ $banner }}">
          <div class="sh-middle">
              <div class="page-title">
                  My Account
              </div>
          </div>
          <div class="edit-banner text-end">

              <a href="javascript:void(0)">
                  <input type="file" name="banner_img" onchange="uploadBanner(this)">
                  <i class="fas fa-edit"></i> Change banner
              </a>

          </div>
      </div>
  </div>
</div>--}}