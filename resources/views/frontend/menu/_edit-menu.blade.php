@extends('frontend.menu-master')
@php
  use App\Http\Controllers\Frontend\CommonController;
@endphp
@section('sidebar')
<div class="sidebar mobile-sidebar mobile-tabs">
  <div class="sidebar-wrapper filter-tab">
    @if(count($menu) > 0)
    <div class="sidebar-title close">
        <h4>MENU FILTERS</h4>
    </div>
    <div class="sidebar-content">
        <ul class="filter-list">
            @foreach($menu as $category)
              <li>
                <div class="form-check">
                  <form method="POST">
                    <input type="checkbox" class="form-check-input menu-category" id="category_{{ $category->id }}" data-name="{{ $category->name }}" name="menu_category" value="{{ $category->id }}" onchange="menuFilter('{{ route("front.filter-category") }}','menu-item')">
                    <label class="form-check-label" for="category_{{ $category->id }}">{{ ucfirst($category->name) }}</label>
                  </form>
                </div>
              </li>
            @endforeach
        </ul>
    </div>
    @endif
  </div>
  {{--<div class="sidebar-wrapper homestore-tab">
    <div class="sidebar-title close">
        <h4>HOME STORE</h4>
    </div>
    <div class="sidebar-content">
      <div class="store-status">
        @if(!empty($user))
          @php
            $user_id = CommonController::encrypt($user->id);
            $delivery_option = json_decode($user->user_meta->store_delivery_option);
          @endphp
          <div class="btn-group">
              <input type="radio" class="btn-check" name="is_store_open_or_close" id="open_mobile" onchange="userStoreUpdateStatus('{{ $user_id }}',this)" value="1" autocomplete="off" {{ ($user->user_meta->is_store_open_or_close == 1) ? 'checked' : '' }}>
              <label class="btn" for="open_mobile">OPEN</label>

              <input type="radio" class="btn-check" name="is_store_open_or_close" id="close_mobile" onchange="userStoreUpdateStatus('{{ $user_id }}',this)" value="2" autocomplete="off" {{ ($user->user_meta->is_store_open_or_close == 2) ? 'checked' : '' }}>
              <label class="btn" for="close_mobile">CLOSED</label>
          </div>
          <p>Open stores will show up on the food map</p>
          <div class="btn-group">
              <input type="checkbox" class="btn-check" name="store_delivery_option" id="pickupcheckbox_mbl" onchange="userStoreUpdateDeliveryStatus('{{ $user }}',this)" value="0" autocomplete="off" @if($delivery_option != null) @if(in_array(0,$delivery_option)) checked="" @endif @endif>
              <label class="btn" for="pickupcheckbox_mbl">PICKUP</label>

              <input type="checkbox" class="btn-check" name="store_delivery_option" id="deliverycheckbox_mbl" onchange="userStoreUpdateDeliveryStatus('{{ $user }}',this)" value="1" autocomplete="off" @if($delivery_option != null) @if(in_array(1,$delivery_option)) checked="" @endif @endif>
              <label class="btn" for="deliverycheckbox_mbl">DELIVERY</label>
          </div>
        @endif
      </div>
    </div>
  </div>--}}
</div>
@endsection
@section('content')
<!-- <div class="spinner-grow text-muted spin-loader" style="display: none;"></div> -->
<div class="addmenu-wrapper">
 <div class="addmenu-head">
    <!-- <button class="btn addmenu-btn">
      <i class="far fa-plus-circle"></i>
    </button> -->
    <a href="javascript:void(0)" class="addmenu-btn" title="Add Menu Item">
      {{--<img src="{{ asset('public/frontend/img/plus.png') }}" width="40">--}}
      <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="25" height="25" viewBox="0 0 64.000000 64.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,64.000000) scale(0.100000,-0.100000)"
fill="#000000" stroke="none">
<path d="M245 631 c-92 -24 -173 -90 -215 -176 -19 -41 -24 -66 -24 -135 -1
-78 2 -90 33 -148 38 -70 70 -100 145 -140 43 -22 64 -26 136 -26 72 0 93 4
136 26 75 40 107 70 145 140 31 58 34 70 33 148 0 72 -4 93 -26 136 -39 75
-70 107 -137 143 -65 34 -164 49 -226 32z m165 -47 c39 -12 66 -29 104 -67
206 -207 3 -544 -279 -463 -79 23 -156 100 -179 176 -34 113 -11 206 68 286
80 79 173 102 286 68z"/>
<path d="M307 474 c-4 -4 -7 -36 -7 -70 l0 -63 -67 -3 c-53 -2 -68 -6 -68 -18
0 -12 15 -16 67 -18 l67 -3 3 -67 c2 -52 6 -67 18 -67 12 0 16 15 18 67 l3 67
67 3 c52 2 67 6 67 18 0 12 -15 16 -67 18 l-67 3 -3 66 c-3 61 -14 84 -31 67z"/>
</g>
</svg>
    </a>
     <a class="homestore-btn d-md-none d-inline-flex" title="Add Menu Item" >
     <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="25" height="25" viewBox="0 0 64.000000 64.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,64.000000) scale(0.100000,-0.100000)"
fill="#000000" stroke="none">
<path d="M112 608 c-17 -17 -15 -58 4 -79 14 -15 13 -24 -5 -97 -17 -65 -21
-112 -21 -246 l0 -166 230 0 230 0 0 166 c0 133 -4 181 -20 245 -13 52 -18 92
-14 119 11 69 7 70 -205 70 -132 0 -191 -4 -199 -12z m348 -38 l0 -30 -165 0
c-138 0 -167 3 -171 15 -4 8 -4 22 0 30 4 12 33 15 171 15 l165 0 0 -30z m40
0 c0 -11 -4 -20 -10 -20 -5 0 -10 9 -10 20 0 11 5 20 10 20 6 0 10 -9 10 -20z
m-30 -57 c0 -5 -9 -42 -20 -83 -15 -59 -20 -108 -20 -232 l0 -158 -160 0 -160
0 0 155 c0 125 4 172 21 240 l21 85 159 0 c88 0 159 -3 159 -7z m43 -95 c24
-100 25 -367 2 -348 -12 10 -15 39 -15 141 0 79 -4 129 -10 129 -6 0 -10 -50
-10 -129 0 -102 -3 -131 -15 -141 -23 -19 -22 248 2 348 9 39 20 72 23 72 3 0
14 -33 23 -72z m-8 -368 c3 -5 -3 -10 -15 -10 -12 0 -18 5 -15 10 3 6 10 10
15 10 5 0 12 -4 15 -10z"/>
<path d="M175 323 c4 -21 8 -80 9 -132 1 -77 4 -96 19 -105 12 -8 22 -8 35 0
14 9 17 28 18 105 1 52 5 111 9 132 5 25 4 37 -4 37 -6 0 -11 -9 -11 -20 0
-11 -4 -20 -10 -20 -5 0 -10 9 -10 20 0 11 -4 20 -10 20 -5 0 -10 -9 -10 -20
0 -11 -4 -20 -10 -20 -5 0 -10 9 -10 20 0 11 -5 20 -11 20 -8 0 -9 -12 -4 -37z
m65 -33 c0 -5 -9 -10 -20 -10 -11 0 -20 5 -20 10 0 6 9 10 20 10 11 0 20 -4
20 -10z m-6 -91 c9 -93 8 -99 -14 -99 -22 0 -23 6 -14 99 4 33 10 61 14 61 4
0 10 -28 14 -61z"/>
<path d="M311 335 c-25 -33 -37 -85 -21 -95 7 -5 10 -30 8 -75 -2 -55 0 -69
15 -79 24 -16 52 4 51 38 0 14 -1 73 -2 131 -2 115 -11 129 -51 80z m26 -91
c-7 -7 -37 7 -37 17 0 5 8 26 17 47 l17 37 4 -49 c2 -26 1 -50 -1 -52z m3 -46
c0 -13 3 -40 6 -60 6 -35 5 -38 -16 -38 -21 0 -22 3 -16 38 3 20 6 47 6 60 0
12 5 22 10 22 6 0 10 -10 10 -22z"/>
</g>
</svg>
      </a>

    @php
      $userid = CommonController::encrypt(Auth::guard('users')->user()->id);
    @endphp
    <a href="{{ route('front.menu',[$userid]) }}" title="Preview Menu">
      {{--<img src="{{ asset('public/frontend/img/menu.png') }}" width="40">--}}
      <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="25" height="25" viewBox="0 0 64.000000 64.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,64.000000) scale(0.100000,-0.100000)"
fill="#000000" stroke="none">
<path d="M280 586 l-175 -53 -3 -266 -2 -267 220 0 220 0 0 265 0 265 -30 0
c-30 0 -30 1 -30 55 0 38 -4 55 -12 54 -7 0 -92 -24 -188 -53z m180 -16 l0
-40 -132 0 c-72 0 -129 3 -126 6 8 7 218 71 241 73 13 1 17 -7 17 -39z m60
-305 l0 -245 -200 0 -200 0 0 245 0 245 200 0 200 0 0 -245z"/>
<path d="M170 421 c0 -28 5 -53 10 -56 6 -3 10 5 10 18 0 20 4 24 19 20 11 -3
22 -14 24 -25 9 -34 27 -9 27 37 0 51 -13 64 -33 34 l-15 -21 -11 21 c-19 35
-31 24 -31 -28z"/>
<path d="M270 415 c0 -48 3 -55 19 -55 11 0 23 5 26 10 3 6 -1 10 -9 10 -22 0
-20 30 2 30 16 1 16 1 0 14 -22 16 -23 26 -3 26 8 0 15 5 15 10 0 6 -11 10
-25 10 -24 0 -25 -3 -25 -55z"/>
<path d="M330 421 c0 -28 5 -53 10 -56 6 -3 10 4 10 17 l0 23 20 -25 20 -25
-1 60 c0 53 -1 58 -13 42 -12 -15 -14 -16 -20 -2 -12 32 -26 13 -26 -34z"/>
<path d="M410 422 c0 -53 19 -74 46 -51 16 13 19 99 4 99 -5 0 -10 -18 -10
-40 0 -22 -4 -40 -10 -40 -5 0 -10 15 -10 34 0 19 -4 38 -10 41 -6 4 -10 -13
-10 -43z"/>
<path d="M145 260 c-4 -7 57 -10 175 -10 118 0 179 3 175 10 -8 13 -342 13
-350 0z"/>
<path d="M140 190 c0 -6 67 -10 180 -10 113 0 180 4 180 10 0 6 -67 10 -180
10 -113 0 -180 -4 -180 -10z"/>
<path d="M140 130 c0 -6 67 -10 180 -10 113 0 180 4 180 10 0 6 -67 10 -180
10 -113 0 -180 -4 -180 -10z"/>
</g>
</svg>
    </a>
    <div class="mobile-tabs" id="homestoreBtn">
          <div class="sidebar-wrapper homestore-tab">

            <div class="sidebar-content">
              <div class="store-status">
                @if(!empty($user))
                  @php
                    $user_id = CommonController::encrypt($user->id);
                    $delivery_option = json_decode($user->user_meta->store_delivery_option);
                  @endphp
                  <div class="btn-group">
                      <input type="radio" class="btn-check" name="is_store_open_or_close" id="open_mobile" onchange="userStoreUpdateStatus('{{ $user_id }}',this)" value="1" autocomplete="off" {{ ($user->user_meta->is_store_open_or_close == 1) ? 'checked' : '' }}>
                      <label class="btn" for="open_mobile">OPEN</label>

                      <input type="radio" class="btn-check" name="is_store_open_or_close" id="close_mobile" onchange="userStoreUpdateStatus('{{ $user_id }}',this)" value="2" autocomplete="off" {{ ($user->user_meta->is_store_open_or_close == 2) ? 'checked' : '' }}>
                      <label class="btn" for="close_mobile">CLOSED</label>
                  </div>
                  <p>Open stores will show up on the food map</p>
                  <div class="btn-group">
                      <input type="checkbox" class="btn-check" name="store_delivery_option" id="pickupcheckbox_mbl" onchange="userStoreUpdateDeliveryStatus('{{ $user }}',this)" value="0" autocomplete="off" @if($delivery_option != null) @if(in_array(0,$delivery_option)) checked="" @endif @endif>
                      <label class="btn" for="pickupcheckbox_mbl">PICKUP</label>

                      <input type="checkbox" class="btn-check" name="store_delivery_option" id="deliverycheckbox_mbl" onchange="userStoreUpdateDeliveryStatus('{{ $user }}',this)" value="1" autocomplete="off" @if($delivery_option != null) @if(in_array(1,$delivery_option)) checked="" @endif @endif>
                      <label class="btn" for="deliverycheckbox_mbl">DELIVERY</label>
                  </div>
                @endif
              </div>
              <!-- <div class="store-status">
                <div class="btn-group">
                  <input type="radio" class="btn-check" name="is_store_open_or_close" id="open_mobile"
                  onchange="userStoreUpdateStatus('+oy6HTe0KXCjenNQabYWpg==',this)" value="1" autocomplete="off"
                  checked>
                  <label class="btn" for="open_mobile">OPEN</label>

                  <input type="radio" class="btn-check" name="is_store_open_or_close" id="close_mobile"
                  onchange="userStoreUpdateStatus('+oy6HTe0KXCjenNQabYWpg==',this)" value="2"
                  autocomplete="off">
                  <label class="btn" for="close_mobile">CLOSED</label>
                </div>
                <p>Open stores will show up on the food map</p>
                <div class="btn-group">
                  <input type="checkbox" class="btn-check" name="store_delivery_option" id="pickupcheckbox_mbl"
                  onchange="userStoreUpdateDeliveryStatus('{&quot;id&quot;:67,&quot;role_id&quot;:null,&quot;name&quot;:&quot;av&quot;,&quot;lname&quot;:null,&quot;username&quot;:null,&quot;email&quot;:&quot;avp123456@yopmail.com&quot;,&quot;phone&quot;:null,&quot;country&quot;:null,&quot;state&quot;:null,&quot;city&quot;:null,&quot;address&quot;:null,&quot;zipcode&quot;:null,&quot;email_verified_at&quot;:null,&quot;user_image&quot;:null,&quot;latitude&quot;:null,&quot;longitude&quot;:null,&quot;facebook_key&quot;:null,&quot;google_key&quot;:null,&quot;status&quot;:1,&quot;created_at&quot;:&quot;2022-02-15T09:30:37.000000Z&quot;,&quot;updated_at&quot;:&quot;2022-02-15T09:30:37.000000Z&quot;,&quot;deleted_at&quot;:null,&quot;user_meta&quot;:{&quot;id&quot;:98,&quot;role_id&quot;:2,&quot;user_id&quot;:67,&quot;user_ratings_id&quot;:null,&quot;user_favorites_id&quot;:null,&quot;lname&quot;:&quot;av&quot;,&quot;username&quot;:null,&quot;phone&quot;:null,&quot;country&quot;:null,&quot;state&quot;:null,&quot;city&quot;:null,&quot;address&quot;:null,&quot;zipcode&quot;:null,&quot;user_image&quot;:null,&quot;latitude&quot;:null,&quot;longitude&quot;:null,&quot;facebook_key&quot;:null,&quot;google_key&quot;:null,&quot;status&quot;:1,&quot;is_store_open_or_close&quot;:1,&quot;store_delivery_option&quot;:null,&quot;banner_image&quot;:null,&quot;created_at&quot;:&quot;2022-02-15T09:30:37.000000Z&quot;,&quot;updated_at&quot;:&quot;2022-02-15T10:11:14.000000Z&quot;,&quot;deleted_at&quot;:null}}',this)"
                  value="0" autocomplete="off">
                  <label class="btn" for="pickupcheckbox_mbl">PICKUP</label>

                  <input type="checkbox" class="btn-check" name="store_delivery_option" id="deliverycheckbox_mbl"
                  onchange="userStoreUpdateDeliveryStatus('{&quot;id&quot;:67,&quot;role_id&quot;:null,&quot;name&quot;:&quot;av&quot;,&quot;lname&quot;:null,&quot;username&quot;:null,&quot;email&quot;:&quot;avp123456@yopmail.com&quot;,&quot;phone&quot;:null,&quot;country&quot;:null,&quot;state&quot;:null,&quot;city&quot;:null,&quot;address&quot;:null,&quot;zipcode&quot;:null,&quot;email_verified_at&quot;:null,&quot;user_image&quot;:null,&quot;latitude&quot;:null,&quot;longitude&quot;:null,&quot;facebook_key&quot;:null,&quot;google_key&quot;:null,&quot;status&quot;:1,&quot;created_at&quot;:&quot;2022-02-15T09:30:37.000000Z&quot;,&quot;updated_at&quot;:&quot;2022-02-15T09:30:37.000000Z&quot;,&quot;deleted_at&quot;:null,&quot;user_meta&quot;:{&quot;id&quot;:98,&quot;role_id&quot;:2,&quot;user_id&quot;:67,&quot;user_ratings_id&quot;:null,&quot;user_favorites_id&quot;:null,&quot;lname&quot;:&quot;av&quot;,&quot;username&quot;:null,&quot;phone&quot;:null,&quot;country&quot;:null,&quot;state&quot;:null,&quot;city&quot;:null,&quot;address&quot;:null,&quot;zipcode&quot;:null,&quot;user_image&quot;:null,&quot;latitude&quot;:null,&quot;longitude&quot;:null,&quot;facebook_key&quot;:null,&quot;google_key&quot;:null,&quot;status&quot;:1,&quot;is_store_open_or_close&quot;:1,&quot;store_delivery_option&quot;:null,&quot;banner_image&quot;:null,&quot;created_at&quot;:&quot;2022-02-15T09:30:37.000000Z&quot;,&quot;updated_at&quot;:&quot;2022-02-15T10:11:14.000000Z&quot;,&quot;deleted_at&quot;:null}}',this)"
                  value="1" autocomplete="off">
                  <label class="btn" for="deliverycheckbox_mbl">DELIVERY</label>
                </div>
              </div> -->
            </div>
          </div>
        </div>

 </div>
 <div class="addmenu-body" id="menuFoodForm" style="@if($errors->first('category') != null || $errors->first('food_image') != null || $errors->first('food_name') != null || $errors->first('price_type') != null || $errors->first('food_price') != null) {{ 'display: block;' }}@endif {{--@if(count($errors) > 0){{ 'display: block;' }}@endif--}}">
   <div class="form-wrapper">
     <form action="{{ route('front.save-product') }}" class="food-form create-dish" method="POST" enctype="multipart/form-data">
      @csrf
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="foodcategory" class="form-label">Food Category <span>*</span></label>
                  <select class="form-select" name="category">
                    <option value="0">Select food category</option>
                    @if(count($menu) > 0)
                      @foreach($menu as $category)
                        <option value="{{ $category->id }}" {{ (old('category') == $category->id) ? "selected" : "" }}>{{ $category->name }}</option>
                      @endforeach
                    @endif
                  </select>
                  <span class="text-danger">{{ $errors->first('category') }}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12 col-xl-2">
                <div class="form-group">
                  <label for="food_image" class="form-label">Food Image <span>*</span></label>
                  <br>
                  <div class="fileupload-wrapper">
                    <div class="browse-btn"><img src="{{ asset('public/frontend/img/browse.png') }}" id="dish_img"></div>
                    <input type="file" name="food_image" onchange="readURL(this);"/>
                    <span class="text-danger">{{ $errors->first('food_image') }}</span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12 col-lg-5 col-xl-5">
                <div class="form-group">
                <label for="foodname" class="form-label">Food Name <span>*</span></label>
                 <input type="text" class="form-control" placeholder="Food Name" name="food_name" value="{{ old('food_name') }}">
                 <span class="text-danger">{{ $errors->first('food_name') }}</span>
                 </div>
               </div>
               <div class="col-12 col-md-12 col-lg-7 col-xl-5">
                   <div class="food-price-radio d-flex justify-content-between form-group">
                     <div class="form-check form-check-inline">
                       <input class="form-check-input" type="radio" name="price_type" id="free-edit" value="0">
                       <label class="form-check-label" for="free-edit">Free <span>*</span></label>
                     </div>
                     <div class="form-check form-check-inline">
                       <input class="form-check-input" type="radio" name="price_type" id="price-edit" value="1" checked>
                       <label class="form-check-label" for="price-edit">Price <span>*</span></label>
                       <input type="text" class="form-control" placeholder="Enter Price" name="food_price" value="{{ old('food_price') }}">
                       <span class="text-danger food-price">{{ $errors->first('food_price') }}</span>
                     </div>
                   </div>
               </div>

             </div>
             <div class="row">
               <div class="col">
                 <div class="form-group">
                  <button class="editmenu-btn btn-solid" type="submit">
                    SAVE
                  </button>
                  <button class="editmenu-btn btn-solid-alt">
                    RESET
                  </button>
                 </div>

               </div>
             </div>
            <a href="#" class="food-item-delete">
              <i class="fas fa-times-circle"></i>
            </a>
     </form>
   </div>
 </div>
</div>
@if($menu_count == 0)
  <div class="text-center no-result-found">Currently, No any items found in your menu.</div>
@endif
@if(count($products) > 0)
  <div class="menu-item">
    <!-- <div class="spinner-wrapper">
      <div class="spinner-grow text-muted spin-loader" style="display: none;"></div>
    </div> -->
    <div class="catmenu-list">
      @foreach($products as $product)
        @if(count($product->products) > 0)
          <div class="accordion" id="catmenulist_{{ $product->id }}">
            <div class="accordion-item">
              <h2 class="accordion-header catmenu-title" id="catOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{ $product->id }}" aria-expanded="true" aria-controls="collapseOne_{{ $product->id }}">
                  {{ ucfirst($product->name) }}
                </button>
              </h2>
              <div id="collapseOne_{{ $product->id }}" class="accordion-collapse collapse show" aria-labelledby="catOne" data-bs-parent="#catmenulist_{{ $product->id }}">
                <div class="accordion-body">
                    <div class="catmenulist-table catmenu" id="catmenu_{{ $product->id }}">
                      <!-- menuitem 1 -->
                      @foreach($product->products as $key => $prdct)
                        @if($prdct->category_id == $product->id)
                          <div class="catmenulist-tr menu-section" id="menu_{{ $prdct->id }}">
                            <div class="catmenulist-td sort-icon">
                              <i class="fas fa-bars"></i>
                            </div>
                            <div class="catmenulist-td">
                              <div class="item-info">
                                <div class="item-img">
                                  <img src="{{ asset('public/frontend/img/user_dishes/'.$prdct->product_image) }}">
                                </div>
                                <h4 class="item-name">
                                    {{ ucfirst($prdct->product_name) }}
                                </h4>
                              </div>
                              <div class="price">
                                  @if($prdct->price_type == 1) {{ number_format($prdct->product_price,2) }} @else {{ "Free" }} @endif
                              </div>
                            </div>
                            <div class="catmenulist-td action-btn">
                              <a href="javascript:void(0)" class="icon-edit" data-id="{{ $prdct->id }}" id="product_edit_{{ $prdct->id }}"><i class="fas fa-edit"></i></a>
                              <a href="javascript:void(0)" onclick="deleteUserMenuItem('{{ $prdct->id }}')" class="icon-delete" data-id="{{ $prdct->id }}" id="product_delete_{{ $prdct->id }}"><i class="fas fa-times-circle"></i></a>
                            </div>
                          </div>
                          <!-- edit menu item -->
                          <div class="catmenulist-tr tr-edit" id="product_section_{{ $prdct->id }}" style="display: none;">
                            <div class="catmenulist-td-edit">
                              <div class="form-wrapper">
                                <form action="{{ route('front.update-product',[$prdct->id]) }}" class="food-form edit-dish" method="POST" enctype="multipart/form-data">
                                  @csrf
                                  <div class="row">
                                     <div class="col">
                                       <div class="form-group">
                                         <label for="foodcategory" class="form-label">Food Category <span>*</span></label>
                                         <select class="form-select" name="item_category">
                                           <option value="0">Select food category</option>
                                            @if(count($menu) > 0)
                                              @foreach($menu as $category)
                                                <option value="{{ $category->id }}" {{ ($prdct->category_id == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                              @endforeach
                                            @endif
                                         </select>
                                         <span class="text-danger">{{ $errors->first('item_category') }}</span>
                                       </div>
                                     </div>
                                  </div>

                                  <div class="row">
                                     <div class="col-12 col-md-12 col-lg-12 col-xl-2">
                                       <div class="form-group">
                                         <label for="foodimage" class="form-label">Food Image <span>*</span></label>
                                         <br>
                                         <div class="fileupload-wrapper">
                                           <div class="browse-btn">
                                            @if($prdct->product_image != null)
                                              <img src="{{ asset('public/frontend/img/user_dishes/'.$prdct->product_image) }}" id="edit_dish_img">
                                            @else
                                             <img src="{{ asset('public/frontend/img/browse.png') }}">
                                            @endif
                                            </div>
                                           <input type="file" name="item_image" onchange="editFileURL(this);"/>
                                           <a class="delete-img">
                                            <!-- <i class="fas fa-times-circle"></i> -->
                                            <i class="fas fa-edit"></i>
                                          </a>
                                          <span class="text-danger">{{ $errors->first('item_image') }}</span>
                                         </div>

                                       </div>
                                     </div>
                                     <div class="col-12 col-md-12 col-lg-5 col-xl-5">
                                      <div class="form-group">
                                      <label for="foodname" class="form-label">Food Name <span>*</span></label>
                                       <input type="text" class="form-control" placeholder="Food Name" name="item_name" value="{{ $prdct->product_name }}">
                                       <span class="text-danger food-price">{{ $errors->first('item_name') }}</span>
                                       </div>
                                     </div>
                                     <div class="col-12 col-md-12 col-lg-7 col-xl-5">
                                         <div class="food-price-radio d-flex justify-content-between form-group">
                                           <div class="form-check form-check-inline">
                                             <input class="form-check-input" type="radio" name="item_type" id="free_{{ $prdct->id }}" value="0" @if($prdct->price_type == 0) checked="" @endif>
                                             <label class="form-check-label" for="free_{{ $prdct->id }}">Free <span>*</span></label>
                                           </div>

                                           <div class="form-check form-check-inline">
                                             <input class="form-check-input" type="radio" name="item_type" id="price_{{ $prdct->id }}" value="1" @if($prdct->price_type == 1) checked="" @endif>
                                             <label class="form-check-label" for="price_{{ $prdct->id }}">Price <span>*</span></label>
                                              <input type="text" class="form-control" placeholder="Enter Price" name="item_price" value="{{ $prdct->product_price }}">
                                              <span class="text-danger food-price">{{ $errors->first('item_price') }}</span>
                                           </div>
                                         </div>
                                     </div>

                                  </div>
                                  <div class="row">
                                     <div class="col">
                                       <div class="form-group">
                                        <button class="editmenu-btn btn-solid" type="submit">
                                          SAVE
                                        </button>
                                        <button class="editmenu-btn btn-solid-alt">
                                          RESET
                                        </button>
                                       </div>

                                     </div>
                                  </div>
                                  <a href="javascript:void(0)" class="food-item-delete close-edit" id="close_edit_menu_{{ $prdct->id }}" data-id="{{ $prdct->id }}">
                                     <i class="fas fa-times-circle"></i>
                                  </a>
                                </form>
                              </div>
                            </div>
                          </div>
                          <!-- end edit menu item -->
                        @endif
                      @endforeach
                      <!-- end menu item 1 -->
                    </div>
                </div>
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </div>
@endif
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="{{ asset('/public/frontend/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function(){
      @if(Session::has('level'))
          new Noty({
              text: "{{ session('content') }}",
              type: "{{ session('level') }}"
          }).show();
      @endif
    });
  $("#free-edit").click(function(){
    $(".food-price").html('');
  });
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#dish_img').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  function editFileURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#edit_dish_img').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  var accordion_id;
  $(".catmenu").sortable({
      delay: 150,
      stop: function() {
          var id = $(this).attr('id');
          var selectedData = new Array();
          $('.accordion').each(function() {
            accordion_id = id.substring(8);
          });
          $('#catmenulist_'+accordion_id+' .menu-section').each(function(){
            var menu_id = ($(this).attr('id')).substring(5);
            selectedData.push(menu_id);
          });
        if(selectedData.length > 1){
          updateOrder(selectedData);
        }
      }
  });
  function updateOrder(data) {
    var url = base_url + '/sort-order';
      $.ajax({
          url:url,
          type:'post',
          data:{"_token":csrf_token,position:data},
          success:function(data){
            if(data != null){
              new Noty({
                theme: ' alert alert-success alert-styled-left p-0 bg-green',
                text: data.responseMessage,
                type: data.success,
              }).show();
              setTimeout(function(){
                location.reload();
              }, 2000);
            }
          }
      })
  }
</script>
@endsection
@endsection
