@if(count($products) > 0)
  @foreach($products as $product)
    @if(count($product->products) > 0)
      <div class="food-list-wrapper">
        <div class="row">
          <div class="col-12">
            <h4 class="title-h4 cat-item">
                {{ ucfirst($product->name) }}
            </h4>
          </div>
        </div>
        <div class="row">
          @foreach($product->products as $prdct)
            @if($prdct->category_id == $product->id)
              <div class="col-6 col-md-6 col-lg-4 col-xl-4">
                <div class="food-menu-item">
                  <div class="fm-img">
                      <img src="{{ asset('public/frontend/img/user_dishes/'.$prdct->product_image) }}" />
                  </div>
                  <h4 class="fm-name"> {{ ucfirst($prdct->product_name) }} </h4>
                  <p class="fm-price">@if($prdct->price_type == 1) {{ '$'.number_format($prdct->product_price,2) }} @else {{ "Free" }} @endif</p>
                </div>
              </div>
            @endif
          @endforeach
        </div>
      </div>
    @endif
  @endforeach
@endif
