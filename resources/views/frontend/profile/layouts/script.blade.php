<script src="{{ asset('public/frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
	var base_url = '{{ url("/") }}';
	var csrf_token = '{{ csrf_token() }}';
</script>
<script src="{{ asset('public/frontend/js/custom_js.js') }}?{{ rand(1,9999) }}"></script>
<script src="{{ asset('public/frontend/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/jgrowl.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/noty.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/components_notifications_other.js') }}"></script>
<script src="{{ asset('public/frontend/js/common.js') }}?{{ rand(1,9999) }}"></script>
<script src="{{ asset('public/frontend/js/sweetalert.js') }}"></script>
<script src="{{ asset('public/frontend/js/jquery-ui.min.js') }}"></script>
<script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
{{--<script src='https://masonry.desandro.com/masonry.pkgd.js?ver=1651480222' id='masonary-js-js'></script>--}}
{{--<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js"></script>--}}
<script src="{{ asset('public/frontend/js/new_custom_js.js') }}?{{ rand(1,9999) }}"></script>
<script>
$(document).ready(function(){
  $('.mobile-foodmap').on('click', function(){
    $(".mobile-foodmap").toggleClass("hide-map");
    $(".mobile-loc-map-wrapper").toggleClass("hide-map");
  });
});
@if(request()->segment(1) == "profile")
	var feed_url = 'get-user-news-feed-ids';
	var uId = '{{ request()->route("id") }}';
@elseif(request()->segment(1) == "news-feed")
	var feed_url = 'get-news-feed-ids';
@endif
</script>