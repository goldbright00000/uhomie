<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Uhomie</title>
	<!-------Bootstrap Plugin---------->
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" />
<link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
<link href="{{ asset('css/break_bs_v_1_2.css') }}" rel="stylesheet">
<link href="{{ asset('css/costum.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<script charset="UTF-8" src="//cdn.sendpulse.com/js/push/80d831a5274cd079abe7b91208c4402f_1.js" async></script>

	<style>
	.dropdown-menu>li>a {
		white-space: normal;
	}

	.section-in-holder-spl{
		height: auto;
	}

	.field-select+.btn-group input{
		display: block;
	}

	.field-select+.btn-group input[type=radio]{
		display: none !important;
	}


	.btn-remove{
		float: right !important;
		background: none !important;
		color: grey;
		font-size: 17px;
	}


	#map {
		margin: 20px auto;
		width: 100%;
		height: 300px !important;
		position: relative !important;
		overflow: hidden !important;
	}
	html, body {
		height: 100%;
	}
	#map #infowindow-content {
		display: inline;
	}

</style>

@yield('custom-css')


<!------Script section------>
</head>
<body>
	<!-- pixel perfet checker alpha version by vj -->
	<!-- <div class="pixel-perfect" style="position:absolute; top: 0px; z-index: 99; left:0px; background-size: 100%; width:100%; height:100%; background-position:0px 0px; background-image:url(../check/dashboard/1.png); pointer-events:none; opacity:0.3; ">
	<style>
	.pixel-perfect:after{content:""; position: fixed; top: 0px; left: 0px; background-position: left; background-repeat: repeat; background-image: url(../check/grid.svg); opacity: 0.7; pointer-events: none;width: 100%;
	height: 100%;
	background-size: 108px;
	z-index: 9999;}
</style>
</div> -->
<!-- End Pixel Perfect -->
<div class="ndiv page-holder">
<!--header space-->

    <!--header space-->

    	<div id="header">
        <!--header section-->
		<header class="ndiv header-section">
		  <div class="ndiv dash-inner-bracket">
		    <div class="ndiv dash-logo-holder">
		      <img class="imgres" src="{{url('images/logo.png')}}">
		    </div>
		    <div class="ndiv header-text-holder" style="padding: 10px 30px;">
		        
		    	<span>
		    		<strong>
		    			{{ $header["title"] }}
		    		</strong> 

		    		{{ $header["subtitle"] }}
		    	</span>
		    </div>
		    <div class="ndiv dash-close-holder">
		      <a href="{{ url('/') }}" class="dash-close">X</a>
		    </div>
		  </div>
		</header>
		<!--header section-->

    </div>
    <!--/header space-->
@yield('content')
</div>
<script type="text/javascript" src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('plugins/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
<script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>	
<script>
function cerrar() {
	$("#phone-ok").css("display", "none");
}
</script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
    $('select').select2();

    });


</script>
@yield('custom-js')
<!--language translation ends-->
</body>
</html>
