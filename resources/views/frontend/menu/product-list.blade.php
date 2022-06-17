@if(count($products) > 0)
  @foreach($products as $product)
    @if(count($product->products) > 0)
      <div class="catmenu-list">
        <div class="accordion" id="catmenulist_{{ $product->id }}">
          <div class="accordion-item">
            <h2 class="accordion-header catmenu-title" id="catOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{ $product->id }}" aria-expanded="true" aria-controls="collapseOne_{{ $product->id }}">
                {{ ucfirst($product->name) }}
              </button>
            </h2>
            <div id="collapseOne_{{ $product->id }}" class="accordion-collapse collapse show" aria-labelledby="catOne" data-bs-parent="#catmenulist_{{ $product->id }}">
              <div class="accordion-body">
                  <div class="catmenulist-table">
                    <!-- menuitem 1 -->
                    @foreach($product->products as $prdct)
                      @if($prdct->category_id == $product->id)
                        <div class="catmenulist-tr">
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
                                @if($prdct->price_type == 1) {{ '$'.number_format($prdct->product_price,2) }} @else {{ "Free" }} @endif
                            </div>
                          </div>
                          <div class="catmenulist-td action-btn">
                            <a href="#" class="icon-edit"><i class="fas fa-edit"></i></a>
                            <a href="#" class="icon-delete"><i class="fas fa-times-circle"></i></a>
                          </div>
                        </div>
                      @endif
                    @endforeach
                    <!-- end menu item 1 -->
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endforeach
@endif