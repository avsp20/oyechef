@php
  use App\Http\Controllers\Frontend\CommonController as Common;
  $disclaimer = Common::checkDisclaimer();
@endphp
@extends('frontend.master')
@section('content')
@if(Auth::guard('users')->check() && $disclaimer == 0)
  <div class="local-market-wrapper">
    <div class="lm-logo">
      <img src="{{ asset('public/frontend/img/logo.png') }}">
    </div>
    <p>
      The local market is a place for Restaurants, Farmers and Home Cooks to sell their food or give it away for free. It is also a place to help reduce Food Waste.
    </p>
    <div class="lm-banner">
      <img src="{{ asset('public/frontend/img/food/faggots.png') }}">
    </div>
    <p>
      I accept to follow my local laws, rules and regulations related to food.
    </p>
    <div class="lm-btn-wrapper">
      <a href="{{ route('front.check-disclaimer') }}" class="btn btn-accept">I Accept</a>
    </div>
  </div>
@else
  <input type="hidden" class="page_number" value="1">
  <input type="hidden" class="total_record" value="12">
  <div class="main-content-top">
    <div class="main-content-head">
      <div class="mob-sidebar">
       <button class="mobilemenu-icon btn btn-filter collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mobilesidebar" aria-controls="mobilesidebar" aria-expanded="false" aria-label="Toggle navigation">
       <i class="fas fa-bars"></i>
        </button>
      </div>
      <div class="map-wrapper">
        <img src="{{ asset('public/frontend/img/icons/map.png') }}" class="normal-img">
        <img src="{{ asset('public/frontend/img/icons/map_active.png') }}" class="active-img">
      </div>
      <div class="search-wrapper">
        <img src="{{ asset('public/frontend/img/icons/search.png') }}" width="30">
      </div>
      <div class="menu-wrapper">
        @if(Auth::guard('users')->check())
          @php
            $route = route('front.edit-menu');
          @endphp
        @else
          @php
            $route = route('front.login');
          @endphp
        @endif
        <a href="{{ $route }}"> <img src="{{ asset('public/frontend/img/icons/Create_Edit-Menu-Icon.png') }}"></a>
      </div>
    </div>
    <div class="main-content-toggle-data">
        <div class="map-wrapper-content">
              <!-- <img src="img/location-map.png" class="mw-100"> -->
            <!-- <div class="loc-map-wrappers">
              <div id="food_map"></div>
              <div id="food_mobile_map"></div>
            </div> -->
            <div class="mobile-loc-map-wrapper">
              <div id="food_mobile_map"></div>
            </div>
            <div class="loc-map-wrapper">
              <div id="food_map"></div>
            </div>
        </div>
        <div class="search-wrapper-content">
          <form class="" method="POST" id="search_form">
            @csrf
              <div class="header-search">
                  <input class="form-control" type="search" name="search" id="search_data" placeholder="Search" aria-label="Search">
                  <button class="btn btn-search" type="submit" id="search_btn">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19.8026 18.645L14.5483 13.3907C15.8752 11.7595 16.5256 9.68135 16.3655 7.58473C16.2054 5.4881 15.2469 3.53284 13.6877 2.12206C12.1285 0.711281 10.0874 -0.0474494 7.98525 0.00230061C5.88311 0.0520507 3.88019 0.906489 2.38945 2.38945C0.906489 3.88019 0.0520507 5.88311 0.00230061 7.98525C-0.0474494 10.0874 0.711281 12.1285 2.12206 13.6877C3.53284 15.2469 5.4881 16.2054 7.58473 16.3655C9.68135 16.5256 11.7595 15.8752 13.3907 14.5483L18.645 19.8026C18.8021 19.9371 19.0041 20.0074 19.2107 19.9994C19.4173 19.9914 19.6133 19.9058 19.7595 19.7595C19.9058 19.6133 19.9914 19.4173 19.9994 19.2107C20.0074 19.0041 19.9371 18.8021 19.8026 18.645ZM3.54704 12.8406C2.62807 11.9222 2.00211 10.752 1.74834 9.47781C1.49457 8.20364 1.62438 6.88283 2.12136 5.68246C2.61835 4.48208 3.46017 3.45606 4.54035 2.73418C5.62053 2.0123 6.89053 1.62699 8.18972 1.62699C9.48891 1.62699 10.7589 2.0123 11.8391 2.73418C12.9193 3.45606 13.7611 4.48208 14.2581 5.68246C14.7551 6.88283 14.8849 8.20364 14.6311 9.47781C14.3773 10.752 13.7514 11.9222 12.8324 12.8406C12.2254 13.455 11.5024 13.9427 10.7055 14.2756C9.9085 14.6085 9.05341 14.78 8.18972 14.78C7.32604 14.78 6.47094 14.6085 5.674 14.2756C4.87705 13.9427 4.1541 13.455 3.54704 12.8406Z" fill="#030303"></path>
                </svg>
                </button>
              </div>
          </form>
        </div>
    </div>
  </div>
    <!-- <div class="loc-map-wrapper">
        <img src="img/location-map.png" class="mw-100" />
    </div> -->
    <!-- food list wrapper -->
    <div class="food-list-wrapper">
      <input type="hidden" name="latitude" id="lat" value="{{ (isset($lat_long) && !empty($lat_long)) ? $lat_long->latitude : '' }}">
      <input type="hidden" name="longitude" id="long" value="{{ (isset($lat_long) && !empty($lat_long)) ? $lat_long->longitude : '' }}">
      <input type="hidden" name="address" id="addr" value="{{ (isset($lat_long) && !empty($lat_long)) ? $lat_long->address : '' }}">
      <div class="row">
        <div class="col-12">
          <h4 class="title-h4">
            Your local market
          </h4>
        </div>
      </div>
      <!-- Food list -->
      <div class="row food-list-wrapper-section">
        <div class="">
          <div class="spinner-grow text-muted spin-loader" style="display: none;"></div>
        </div>
      </div>
      <!-- End Food List -->
        <!-- Loader -->
        <div class="row">
          <div class="col-12">
            <div class="load-more-food">
              <img src="{{ asset('public/frontend/img/loader.png') }}">
            </div>
          </div>
        </div>
        <!-- End Loader -->
    </div>
@endif
{{--<div class="loc-map-wrapper" >
  <div id="food_map"></div>
</div>
<!-- food list wrapper -->
<div class="food-list-wrapper">
  <input type="hidden" name="latitude" id="lat" value="{{ (isset($lat_long) && !empty($lat_long)) ? $lat_long->latitude : '' }}">
  <input type="hidden" name="longitude" id="long" value="{{ (isset($lat_long) && !empty($lat_long)) ? $lat_long->longitude : '' }}">
  <input type="hidden" name="address" id="addr" value="{{ (isset($lat_long) && !empty($lat_long)) ? $lat_long->address : '' }}">
<div class="row">
  <div class="col-12">
    <h4 class="title-h4">
      Your local market
    </h4>
  </div>
</div>
<!-- Food list -->
<div class="row food-list-wrapper-section">
  <div class="">
    <div class="spinner-grow text-muted spin-loader" style="display: none;"></div>
  </div>
</div>
<!-- End Food List -->
<!-- Loader -->
  <div class="row">
    <div class="col-12">
     <div class="load-more-food">
      <img src="{{ asset('public/frontend/img/loader.png') }}">
     </div>
    </div>
  </div>
<!-- End Loader -->
</div>--}}
@section('script')
<script type="text/javascript">
  var address = $("#addr").val();
  var latitude = $("#lat").val();
  var longitude = $("#long").val();
</script>
<script>
  $(document).ready(function() {
      $('.mobile-foodmap').on('click', function() {
          $(".mobile-foodmap").toggleClass("hide-map");
          $(".mobile-loc-map-wrapper").toggleClass("hide-map");
      });
  });
</script>
<script src="{{ asset('public/frontend/js/home-page.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnCj3IWhjiuan4RaQdzjOWQWvDEt4pKxk&libraries=places&libraries=geometry"></script>
<script type="text/javascript">
  let map;
  var locations = <?php echo json_encode($locations) ?>;
  var address = $("#addr").val();
  var latitude = $("#lat").val();
  var longitude = $("#long").val();
  var icon = {
    url: base_url + '/public/frontend/img/restaurant-pin-icon.png',//'/public/frontend/img/restaurant.png', // url
    scaledSize: new google.maps.Size(45, 45), // scaled size
    origin: new google.maps.Point(0,0), // origin
    anchor: new google.maps.Point(0, 0) // anchor
  };
  $(document).ready(function(){
    @if(Session::has('level'))
        new Noty({
            text: "{{ session('content') }}",
            type: "{{ session('level') }}"
        }).show();
    @endif
    if((address.length > 0) && (latitude.length > 0) && (longitude.length > 0)){
      loadMap(parseFloat(latitude),parseFloat(longitude));
    }else{
      getLocation();
    }
  });
function getLocation(){
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition,showError);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}
function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      $.getJSON("https://api.ipify.org?format=json", function (data) {
        $.getJSON('https://ipapi.co/'+data.ip+'/json', function(data1){
          $("#lat").val(data1.latitude);
          $("#long").val(data1.longitude);
          loadMap(data1.latitude,data1.longitude);
        });
      });
      break;
    case error.POSITION_UNAVAILABLE:
      break;
    case error.TIMEOUT:
      break;
    case error.UNKNOWN_ERROR:
      break;
  }
}
function showPosition(position) {
  var lat = parseFloat(position.coords.latitude);
  var lng = parseFloat(position.coords.longitude);
  $("#lat").val(position.coords.latitude);
  $("#long").val(position.coords.longitude);
  loadMap(lat,lng);
}
function loadMap(lat,lng) {
  var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent); //iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  if (isMobile || (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) {
    map = new google.maps.Map(document.getElementById('food_mobile_map'), {
      center: {
        lat: lat,
        lng: lng
      },
      mapTypeControl: false,
      zoom: 13,
      // mapTypeId: 'roadmap'
    });
  }else{
    map = new google.maps.Map(document.getElementById('food_map'), {
      center: {
        lat: lat,
        lng: lng
      },
      zoom: 13,
      // mapTypeId: 'roadmap',
      mapTypeControl: false
    });
  }
  const styles = {
  default: [],
  hide: [
    {
      featureType: "poi",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: 'transit',
      elementType: 'labels.icon',
      stylers: [{visibility: 'off'}]
    },
    {
      featureType: "landscape",
      elementType: "labels",
      stylers: [
          {visibility: "off"}
      ]
    }
  ],
};
map.setOptions({ styles: styles["hide"] });
var infoWindow = new google.maps.InfoWindow();
locations.forEach(function(position){
  var myLatlng = new google.maps.LatLng(position.lat, position.lng);
  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    icon:icon
  });
  let contentString =
    '<a target="_blank" style="text-decoration: none;" href="' + base_url + '/menu/' + position.user_id + '"><div id="content" style="width: 200px;"><div class="store-img">' ;
  if(position.user_image != null){
    var url = base_url + '/public/frontend/img/user_profiles/' + position.user_image;
    contentString += '<img src="' + url + '"/>';
  }else{
    contentString += '<img src="' + base_url + '/public/frontend/img/no-profile-img.jpg">';
  }
  contentString += '</div><div class="food-name">' + position.user_name + '</div>'+
    '<div class="food-rating">'+
      '<span class="avg-rating">';
  if(position.mnu_rating > 0){
    contentString += position.mnu_rating;
  }
  contentString += '</span><span class="star">';
  if(position.mnu_rating > 0){
    for(i = 0; i < 5; i++){
      if(Math.floor(position.mnu_rating) - i >= 1){
        contentString += '<i class="fas fa-star"> </i>';
      } else if (position.mnu_rating - i > 0){
        contentString += '<i class="fas fa-star-half-alt"> </i>';
      }else{
        contentString += '<i class="fas fa-star empty-star"> </i>';
      }
    }
  }else{
    for(i = 0; i < 5; i++){
      contentString += '<i class="fas fa-star empty-star common-star"></i>';
    }
  }
  contentString += '</span><span class="total-rating">';
  if(position.rating_cnt > 0){
    contentString += '(' + position.rating_cnt + ')';
  }else{
    contentString += '(' + position.rating_cnt + ')';
  }
  contentString += '</span></div> <!--<div class="store-address"></div>--></div></a>';

  (function (marker, position) {
    google.maps.event.addListener(marker, "click", function (e) {
        infoWindow.setContent(contentString);
        infoWindow.open(map, marker);
    });
  })(marker, position);
  // Filtration
});
}
  /*'https://ipapi.co/101.32.93.255/json'*/
</script>
@endsection
<!-- food list wrapper -->
@endsection

