@php
  use App\Http\Controllers\Frontend\CommonController;
  $fav_menu = CommonController::userFavoriteMenus();
@endphp
<div class="sidebar-wrapper favorite-chef-wrapper">
  <div class="sidebar-title">
    <h4><i class="fas fa-heart active"></i> FAVORITE CHEFS</h4>
  </div>
  @if(Auth::guard('users')->check())
    @if(count($fav_menu) > 0)
      <div class="sidebar-content">
        <ul class="favorite-cheflist mCustomScrollbar ">
          @foreach($fav_menu as $favmenu)
            @php
              $user_id = CommonController::encrypt($favmenu->id);
            @endphp
            <li>
              <div class="chef-detail fav-chefs" onClick="window.open('{{ route('front.menu',[$user_id])}}','_self');">
                @if($favmenu->user_meta->user_image != null)
                  <img src="{{ asset('public/frontend/img/user_profiles/'.$favmenu->user_meta->user_image) }}" />
                @else
                  <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
                @endif
                <span class="chef-name">{{ $favmenu->name }} {{ ($favmenu->user_meta != null) ? $favmenu->user_meta->lname : '' }}</span>
              </div>
              <div class="icon-delete">
                <a href="javascript:void(0)" onclick="deleteFavoriteUser('{{ $user_id }}')">
                  <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g><path class="st0" d="M9,0C4,0,0,4,0,9c0,5,4,9,9,9s9-4,9-9C18,4,14,0,9,0z M14.1,9.9c0,0.3-0.2,0.5-0.5,0.5H4.4   c-0.3,0-0.5-0.2-0.5-0.5V8.1c0-0.3,0.2-0.5,0.5-0.5h9.2c0.3,0,0.5,0.2,0.5,0.5V9.9z" fill="#606060"/></g>
                  </svg>
                </a>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    @else
      <div class="">No chefs added to favorites.</div>
    @endif
  @else
    <div class="">No chefs added to favorites.</div>
  @endif
</div>