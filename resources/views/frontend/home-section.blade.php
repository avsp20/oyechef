@php
use App\Http\Controllers\Frontend\CommonController;
use App\Http\Controllers\Frontend\MenuController as Menu;
@endphp
@if(count($products) > 0)
@foreach($products as $product)
<input type="hidden" id="productId" class="product-ids" value="{{ $product->id }}">
@php $user_id = CommonController::encrypt($product->user_id); @endphp
<div class="col-12 col-md-6 col-lg-4 col-xl-4 product-det" data-id="{{ $product->product_id }}" data-cat-id="{{ $product->category_id }}" id="product_{{ $product->id }}">
  <div class="food-widget" onClick="window.open('{{ route('front.menu',[$user_id])}}','_self');{{--@if(Auth::guard('users')->check()) window.open('{{ route('front.menu',[$user_id])}}','_self'); @else window.open('{{ route('front.login') }}','_self'); @endif--}}">
    <div class="food-img">
      <img src="{{ asset('public/frontend/img/user_dishes/'.$product->product_image) }}"/>
    </div>
    <div class="food-content">
      <div class="chef-detail">
        <div class="chef-img">
          @if(!empty($product->user) && !empty($product->user->user_meta))
          @if($product->user->user_meta->user_image != null)
          <img src="{{ asset('public/frontend/img/user_profiles/'.$product->user->user_meta->user_image) }}" />
          @else
          <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
          @endif
          @else
          <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
          @endif
        </div>
        <div class="chef-info">
          <div class="food-name">
            {{ $product->product_name }} - {{ ($product->price_type == 1) ? $product->product_price : 'Free' }}
          </div>
          <div class="chef-name">
            @if(!empty($product->user) && !empty($product->user->user_meta))
            {{ $product->user->name.' '.$product->user->user_meta->lname }}
            @endif
          </div>
        </div>
      </div>
      @if(!empty($product->user) && !empty($product->user->user_meta))
      @php
      $ratings_count = 0;
      $menu_ratings = 0;
      $ratings_count = Menu::totalRatingsCount($product->user->id);
      $menu_ratings = Menu::menuRatings($product->user->id);
      @endphp
      @endif
      <div class="food-rating">
        <div class="food-rating-wrapper">
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
            <i class="fas fa-star empty-star common-star"></i>
            @endfor
            @endif
          </span>
          <span class="total-rating">
            {{ (isset($ratings_count)) ? '('.$ratings_count.')' : '' }}
          </span>

        </div>
        <div class="text-center view-mnu">View menu</div>
      </div>
    </div>
  </div>
</div>
@endforeach
@endif