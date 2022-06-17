@php
  use App\Http\Controllers\Frontend\FrontUserController as Curr;
@endphp
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	{{--<h3>Product A costs $800 is {{ json_encode($geoPlugin_array) }}</h3>--}}
	@php
		$currency = Curr::cc(300);
	@endphp
	<h3>Product A costs $300 is {{ $currency }}</h3>
</body>
</html>