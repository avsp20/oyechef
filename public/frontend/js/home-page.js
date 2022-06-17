var page = 1;
var dist_arr = [];
var dist;
loadRecords(page);
$(document).ready(function(){
  var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  if (isMobile) {
    $(document.body).on('touchmove', onScroll); // for mobile
    $(window).on('scroll', onScroll);
  }else{
    $(window).data('ajaxready', true).scroll(function(e) {
      if ($(window).data('ajaxready') == false) return;
      if ($(window).scrollTop() >= ($(document).height() - $(window).height())) {
          $(window).data('ajaxready', false);
          page++;
          loadRecords(page);
      }
    });
  }
});
function onScroll() {
  var addition = ($(window).scrollTop() + window.innerHeight);
  var scrollHeight = (document.body.scrollHeight - 5);
  if (addition > scrollHeight && page < addition) {
    page = addition;
    loadRecords(page);
  }
}
function loadRecords(page) {
  var url = base_url + '/load-page-on-scroll/' + page;
  var id_arr = [];
  var check_arr = [];
  $.ajax({
    url: url,
    type: "GET",
    datatype: "html",
    async: false,
    cache: false,
    beforeSend: function(){
      $(".spin-loader").show();
    },
    complete: function(){
      $(".spin-loader").hide();
    },
    success: function(data){
      // $('.food-list-wrapper-section .product-det:last-child').addClass('last');
      $(window).data('ajaxready', true);
      $(".spin-loader").hide();
      if(page > 1){
        $(".product-det").each(function(i){
          var id = $(this).data('id');
          id_arr[i] = id;
        });
        var checkValues = $('input[name="home_menu"]:checked').map(function()
        {
            return $(this).val();
        }).get();
        var search_data = $("#search_data").val();
        $.ajax({
          url: base_url + '/get-product-ids',
          type: "POST",
          data : {"_token":csrf_token ,"ids":$.unique(id_arr), "check_ids": checkValues, "search_data": search_data},
          dataType:'json',
          success: function(response){
            $(".food-list-wrapper-section").append(response.html);
            $(document).find('.food-list-wrapper-section .product-det:last').addClass('last-child');
          },
          error: function(){}
        });
      }else{
        $(".food-list-wrapper-section").append(data);
        $(document).find('.food-list-wrapper-section .product-det:last').addClass('last-child');
      }
    },
    error: function(){}
  });
}
function menuFilterOption(url,data) {
    var checkValues = $('input[name="home_menu"]:checked').map(function()
    {
        return $(this).val();
    }).get();
    $.ajax(
    {
        url : url,
        type : 'post',
        data : {"_token":csrf_token ,"ids":checkValues},
        dataType:'json',
        beforeSend: function(){
            $(".spin-loader").show();
        },
        success:function(data, textStatus, jqXHR)
        {
            if(data.html != ''){
                $(".spin-loader").hide();
                $(".food-list-wrapper-section").html(data.html);
            }else{
                $(".food-list-wrapper-section").html('<div class="text-center no-result-found">No items match your search.</div>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails
        }
    });
    if(checkValues.length > 0){
      $.ajax(
      {
          url : base_url + '/filter-map',
          type : 'post',
          data : {"_token":csrf_token ,"ids":checkValues},
          dataType:'json',
          success:function(data, textStatus, jqXHR)
          {
            if(data.data != null){
              searchDetails(data.data, map, latitude, longitude);
            }else{
              new Noty({
                theme: ' alert alert-success alert-styled-left p-0 bg-green',
                text: data.responseMessage,
                type: data.status,
              }).show();
            }
          },
          error: function(jqXHR, textStatus, errorThrown)
          {
              //if fails
          }
      });
    }else{
      var lat = $("#lat").val();
      var lng = $("#long").val();
      loadMap(parseFloat(lat),parseFloat(lng));
    }
}
// map js
$("#search_form").submit(function(e)
{   
  e.preventDefault(); 
  //STOP default action
  var search = $("#search_data").val();
  var lat = $("#lat").val();
  var lng = $("#long").val();
  if(search.length > 0){
    searchMenuOnGoogleMap(search,map);    
    triggerSearch(search);
  }else{
    $(".no-result-found").remove();
    loadRecords(page);
    loadMap(parseFloat(lat),parseFloat(lng));
  }
});
function searchMenuOnGoogleMap(search, map) {
  var url = base_url + '/find-latlong';
  $.ajax({
    url: url,
    type: "POST",
    data: { "_token":csrf_token, search_data: search },
    success: function(data){
      if(data.data != null){
        searchDetails(data.data, map, latitude, longitude);
      }else{
        new Noty({
          theme: ' alert alert-success alert-styled-left p-0 bg-green',
          text: data.responseMessage,
          type: data.status,
        }).show();
      }
    },
    error: function(){}
  }); 
}
function calcDistance(p1, p2) {
  return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
}
function searchDetails(search_details, map, latitude, longitude) {
  var bounds = new google.maps.LatLngBounds();
  var distance_arr = [];
  var markers = search_details;
  var infoWindow = new google.maps.InfoWindow();
  var marker, i;
  for (i = 0; i < markers.length; i++) {
      var position = new google.maps.LatLng(markers[i]['latitude'], markers[i]['longitude']);
      bounds.extend(position);
      marker = new google.maps.Marker({
          position: position,
      });
      google.maps.event.addListener(marker, 'click',  (function (marker, i) {
      return function () {
          infoWindow.setContent(markers[i]['address']);
          infoWindow.open(map, marker);
      }
      })(marker, i));
      map.fitBounds(bounds);
    var position1 = new google.maps.LatLng(latitude,longitude);
    var distance = calcDistance(position1, position) * 1000;
    var obj = {
      latitude: markers[i].latitude,
      longitude: markers[i].longitude,
      distance : distance
    }
    distance_arr.push(obj);
    dist_arr.push(distance);
  }
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
      var dis_arr = [];
      distance_arr.sort(function(a,b) {
        return a['distance']-b['distance'];
      });
      var pos = new google.maps.LatLng(distance_arr[0].latitude,distance_arr[0].longitude);
      map.setCenter(pos);
      map.setZoom(13);
      google.maps.event.removeListener(boundsListener);
    });
}
function lowestValueAndKey(obj) {
  var [lowestItems] = Object.entries(obj).sort(([ ,v1], [ ,v2]) => v1 - v2);
  return `Lowest value is ${lowestItems[1]}, with a key of ${lowestItems[0]}`;
}
function triggerSearch(search) {
  var formURL = base_url + '/home-search';
  var type = "POST";
  $.ajax(
  {
      url : formURL,
      type: type,
      data : { "_token":csrf_token, search_data: search },
      success:function(data, textStatus, jqXHR) 
      {
          if(data.html != ''){
              $(".food-list-wrapper-section").html(data.html);
          }else{
              $(".food-list-wrapper-section").html('<div class="text-center no-result-found">No items match your search.</div>');
          }
      },
      error: function(jqXHR, textStatus, errorThrown) 
      {
          //if fails      
      }
  });
}