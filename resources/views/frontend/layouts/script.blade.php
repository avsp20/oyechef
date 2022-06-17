<script src="{{ asset('public/frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
	var base_url = '{{ url("/") }}';
	var csrf_token = '{{ csrf_token() }}';
</script>
<script src="{{ asset('public/frontend/js/custom_js.js') }}"></script>
<script src="{{ asset('public/frontend/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/jgrowl.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/noty.min.js') }}"></script>
<script src="{{ asset('public/frontend/js/components_notifications_other.js') }}"></script>
<script src="{{ asset('public/frontend/js/common.js') }}"></script>
<script src="{{ asset('public/frontend/js/sweetalert.js') }}"></script>
<!-- New custom js -->
<script src="{{ asset('public/frontend/js/new_custom_js.js') }}"></script>
<script>
$(document).ready(function(){
  $('.mobile-foodmap').on('click', function(){
    $(".mobile-foodmap").toggleClass("hide-map");
    $(".mobile-loc-map-wrapper").toggleClass("hide-map");
  });
});
</script>