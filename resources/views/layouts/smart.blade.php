<?php
	$themeClass = '';
	if (!empty($_COOKIE['theme'])) {
		if ($_COOKIE['theme'] == 'dark') {
			$themeClass = 'dark-theme';
		} else if ($_COOKIE['theme'] == 'light') {
			$themeClass = 'light-theme';
		}  
	}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!-- METADATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="" name="description">
		<meta content="" name="author">
		<meta name="keywords" content=""/>
		
        <!-- CSRF TOKEN -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- TITLE -->
        <title>{{ config('app.name', 'Davinci') }}</title>
        
        @include('layouts.header')

	</head>

	<body class="app sidebar-mini <?php echo $themeClass; ?>">

		<div id="loader-line" class="hidden"></div>

		<!-- PAGE -->
		<div class="page">
			<div class="page-main smart-main-page">

				<!-- APP CONTENT -->			
				<div class="smart-content">

					<div class="side-app">

						@include('layouts.nav-top-smart')

                        {{-- @include('layouts.flash') --}}

						@yield('page-header')

						@yield('content')						

                    </div>                   
                </div>
                <!-- END APP CONTENT -->               

            </div>		
        </div><!-- END PAGE -->
        
		@include('layouts.footer-backend')        

	</body>
</html>


