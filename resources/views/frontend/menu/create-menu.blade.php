@extends('frontend.menu-master')
@section('sidebar')
<div class="sidebar mobile-sidebar mobile-tabs">
  <div class="sidebar-wrapper filter-tab">
    <div class="sidebar-title close">
        <h4>MENU FILTERS</h4>
    </div>
    <div class="sidebar-content">
        <ul class="filter-list">
          <li>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="meat">
              <label class="form-check-label" for="meat">Meats</label>
            </div>
          </li>
          <li>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="vegetarian">
              <label class="form-check-label" for="vegetarian">Vegetarian</label>
            </div>
          </li>
          <li>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="vegan">
              <label class="form-check-label" for="vegan">Vegan</label>
            </div>
          </li>
          <li>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="desserts">
              <label class="form-check-label" for="desserts">Desserts</label>
            </div>
          </li>
          <li>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="groceries">
              <label class="form-check-label" for="groceries">Groceries</label>
            </div>
          </li>

        </ul>
    </div>
  </div>
  <div class="sidebar-wrapper homestore-tab">
    <div class="sidebar-title close">
        <h4>HOME STORE</h4>
    </div>
    <div class="sidebar-content">
      <div class="store-status">
        <div class="btn-group" style="pointer-events: none;">
            <input type="radio" class="btn-check" name="btnradio" id="openradio" autocomplete="off" checked="">
            <label class="btn" for="openradio">OPEN</label>

            <input type="radio" class="btn-check" name="btnradio" id="closedradio" autocomplete="off">
            <label class="btn" for="closedradio">CLOSED</label>
        </div>
        <p>Open stores will show up on the food map</p>
        <div class="btn-group" style="pointer-events: none;">
            <input type="checkbox" class="btn-check" id="pickupcheckbox" autocomplete="off" checked="">
            <label class="btn" for="pickupcheckbox">PICKUP</label>

            <input type="checkbox" class="btn-check" id="deliverycheckbox" autocomplete="off">
            <label class="btn" for="deliverycheckbox">DELIVERY</label>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('content')
<div class="addmenu-wrapper">
 <div class="addmenu-head">
    <!-- <button class="btn addmenu-btn">
      <i class="far fa-plus-circle"></i>
    </button> -->
    <a href="#" class="addmenu-btn" title="Add Menu Item"><img src="{{ asset('public/frontend/img/plus.png') }}" width="40"></a>
    <a href="{{ route('front.menu') }}" target="_blank" title="Preview Menu"><img src="{{ asset('public/frontend/img/menu.png') }}" width="40"></a>
 </div>
 <div class="addmenu-body" style="@if(count($errors) > 0){{ 'display: block;' }}@endif">
   <div class="form-wrapper">
     <form action="{{ route('front.save-product') }}" class="food-form" method="POST" enctype="multipart/form-data">
      @csrf
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="foodcategory" class="form-label">Food Category <span>*</span></label>
                  <select class="form-select" name="category">
                    <option value="0">Select food category</option>
                    @if(count($menu) > 0)
                      @foreach($menu as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                    <div class="browse-btn"><img src="{{ asset('public/frontend/img/browse.png') }}"></div>
                    <input type="file" name="food_image" />
                    <span class="text-danger">{{ $errors->first('food_image') }}</span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12 col-lg-5 col-xl-5">
                <div class="form-group">
                <label for="foodname" class="form-label">Food Name <span>*</span></label>
                 <input type="text" class="form-control" placeholder="Food Name" name="food_name">
                 <span class="text-danger">{{ $errors->first('food_name') }}</span>
                 </div>
               </div>
               <div class="col-12 col-md-12 col-lg-7 col-xl-5">
                   <div class="food-price-radio d-flex justify-content-between form-group">
                     <div class="form-check form-check-inline">
                       <input class="form-check-input" type="radio" name="price_type" id="free-edit" value="0">
                       <label class="form-check-label" for="free-edit">Free <span>*</span> ($)</label>
                     </div>
                     <div class="form-check form-check-inline">
                       <input class="form-check-input" type="radio" name="price_type" id="price-edit" value="1" checked>
                       <label class="form-check-label" for="price-edit">Price <span>*</span> ($)</label>
                       <input type="text" class="form-control" placeholder="Enter Price" name="food_price">
                       <span class="text-danger food-price">{{ $errors->first('food_price') }}</span>
                     </div>
                   </div>
               </div>

             </div>
             <div class="row">
               <div class="col">
                 <div class="form-group">
                  <button class="editmenu-btn btn-solid">
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
<div class="catmenu-list">
  @if(count($menu) > 0)
    @foreach($menu as $category)
      <div class="accordion" id="catmenulist_{{ $category->id }}">
        <div class="accordion-item">
          <h2 class="accordion-header catmenu-title" id="catOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{ $category->id }}" aria-expanded="true" aria-controls="collapseOne_{{ $category->id }}">
              {{ ucfirst($category->name) }}
            </button>
          </h2>
          <div id="collapseOne_{{ $category->id }}" class="accordion-collapse collapse show" aria-labelledby="catOne" data-bs-parent="#catmenulist_{{ $category->id }}">
            <div class="accordion-body">
                <div class="catmenulist-table">
                  <!-- menuitem 1 -->
                  @if(count($products) > 0)
                    @foreach($products as $product)
                      @if($product->category_id == $category->id)
                        <div class="catmenulist-tr">
                          <div class="catmenulist-td sort-icon">
                            <i class="fas fa-bars"></i>
                          </div>
                          <div class="catmenulist-td">
                            <div class="item-info">
                              <div class="item-img">
                                <img src="{{ asset('public/frontend/img/user_dishes/'.$product->product_image) }}">
                              </div>
                              <h4 class="item-name">
                                  {{ ucfirst($product->product_name) }}
                              </h4>
                            </div>
                            <div class="price">
                                @if($product->price_type == 1) {{ '$'.number_format($product->product_price,2) }} @else {{ "Free" }} @endif
                            </div>
                          </div>
                          <div class="catmenulist-td action-btn">
                            <a href="#" class="icon-edit"><i class="fas fa-edit"></i></a>
                            <a href="#" class="icon-delete"><i class="fas fa-times-circle"></i></a>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  @endif
                  <!-- end menu item 1 -->
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  @endif
</div>
@section('script')
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
</script>
@endsection
@endsection
