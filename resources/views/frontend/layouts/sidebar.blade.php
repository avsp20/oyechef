@php
  use App\Http\Controllers\Frontend\CommonController;
  $menu = CommonController::MenuCategory();
  $fav_menu = CommonController::userFavoriteMenus();
@endphp
<!-- Sidebar -->
<!-- Sidebar -->
  <div class="sidebar mobilesidebar" id="mobilesidebar">
      <div class="sidebar-wrapper">
          <div class="sidebar-title">
              <h4><i class="fas fa-sliders-h"></i> Filter</h4>
          </div>
          <div class="sidebar-content">
              <ul class="filter-list">
                  <li>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input item-list" id="free" data-id="6" name="home_menu" value="6" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                        <label class="form-check-label" for="free">Free</label>
                      </div>
                  </li>
                  <li>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input item-list" id="pickup" data-id="7" name="home_menu" value="7" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                        <label class="form-check-label" for="pickup">Pickup</label>
                      </div>
                  </li>
                  <li>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input item-list" id="delivery" data-id="8" name="home_menu" value="8" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                        <label class="form-check-label" for="delivery">Delivery</label>
                      </div>
                  </li>
                  @if(count($menu) > 0)
                    @foreach($menu as $category)
                      <li>
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input item-list" id="item_{{ $category->id }}" data-id="{{ $category->id }}" name="home_menu" value="{{ $category->id }}" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
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
  {{--<div class="sidebar mobilesidebar" id="mobilesidebar">
      <div class="sidebar-wrapper">
          <div class="sidebar-title">
              <h4 class="filter-title">Filter</h4>
          </div>
          <div class="sidebar-content">
              <ul class="filter-list">
                <li>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input item-list" id="free" data-id="6" name="home_menu" value="6" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                    <label class="form-check-label" for="free">Free</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input item-list" id="pickup" data-id="7" name="home_menu" value="7" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                    <label class="form-check-label" for="pickup">Pickup</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input item-list" id="delivery" data-id="8" name="home_menu" value="8" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                    <label class="form-check-label" for="delivery">Delivery</label>
                  </div>
                </li>
                @if(count($menu) > 0)
                  @foreach($menu as $category)
                    <li>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input item-list" id="item_{{ $category->id }}" data-id="{{ $category->id }}" name="home_menu" value="{{ $category->id }}" onchange="menuFilterOption('{{ route("front.filter-option") }}',this)">
                        <label class="form-check-label" for="item_{{ $category->id }}">{{ $category->name }}</label>
                      </div>
                    </li>
                  @endforeach
                @endif
              </ul>
          </div>
        </div>
        @include('frontend.fav-menu')
  </div>--}}
<!-- End Sidebar -->