@extends('frontend.menu-master')
@php
  use App\Http\Controllers\Frontend\CommonController;
@endphp
@section('css')
<style type="text/css">
    .homestore-btn, .catmenulist-table{
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<div class="addmenu-wrapper">
    <div class="addmenu-head">
        <a href="javascript:void(0)" class="addmenu-btn" title="Add Menu Item">
            <img src="{{ asset('public/frontend/img/icons/button-black.png') }}" width="25">
        </a>
        <a class="homestore-btn" title="store status">
            <img src="{{ asset('public/frontend/img/icons/Food-Options.png') }}" width="25">
        </a>
        @php
            $userid = CommonController::encrypt(Auth::guard('users')->user()->id);
        @endphp
        <a href="{{ route('front.menu',[$userid]) }}" title="Preview Menu">
            <img src="{{ asset('public/frontend/img/icons/menu.png') }}" width="25">
        </a>
        <div class="mobile-tabs" id="homestoreBtn">
            <div class="homestore-tab">
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
        </div>
    </div>
    <div class="addmenu-body" id="menuFoodForm" style="@if($errors->first('category') != null || $errors->first('food_image') != null || $errors->first('food_name') != null || $errors->first('price_type') != null || $errors->first('food_price') != null) {{ 'display: block;' }}@endif">
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
<div class="no-item">
  <div class="text-center no-result-found">Currently, No any items found in your menu.</div>
</div>
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
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.mobile-foodmap').on('click', function() {
                $(".mobile-foodmap").toggleClass("hide-map");
                $(".mobile-loc-map-wrapper").toggleClass("hide-map");
            });
            $('.addmenu-btn').on('click', function() {
                $(".addmenu-btn").toggleClass("open");
            });
            $('.homestore-btn').on('click', function() {
                $(".homestore-btn").toggleClass("open");
            });
            @if(Session::has('level'))
                new Noty({
                    text: "{{ session('content') }}",
                    type: "{{ session('level') }}"
                }).show();
            @endif
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="https://oyechef.com/public/frontend/js/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {});
        $("#free-edit").click(function() {
            $(".food-price").html('');
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#dish_img').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function editFileURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
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
                $('#catmenulist_' + accordion_id + ' .menu-section').each(function() {
                    var menu_id = ($(this).attr('id')).substring(5);
                    selectedData.push(menu_id);
                });
                if (selectedData.length > 1) {
                    updateOrder(selectedData);
                }
            }
        });

        function updateOrder(data) {
            var url = base_url + '/sort-order';
            $.ajax({
                url: url,
                type: 'post',
                data: {
                    "_token": csrf_token,
                    position: data
                },
                success: function(data) {
                    if (data != null) {
                        new Noty({
                            theme: ' alert alert-success alert-styled-left p-0 bg-green',
                            text: data.responseMessage,
                            type: data.success,
                        }).show();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            })
        }
    </script>
@endsection