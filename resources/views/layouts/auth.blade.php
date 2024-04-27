<?php
	$themeClass = '';
	if (!empty($_COOKIE['theme'])) {
		if ($_COOKIE['theme'] == 'dark') {
			$themeClass = 'dark-theme';
		} else if ($_COOKIE['theme'] == 'light') {
			$themeClass = 'light-theme';
		}  
	} elseif (empty($_COOKIE['theme'])) {
		$themeClass = config('settings.default_theme');
		setcookie('theme', $themeClass);
	} 
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!-- Meta data -->
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
	    <meta name="keywords" content="">
	    <meta name="description" content="">
		
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Title -->
        <title>{{ config('app.name') }}</title>

		<!-- Style css -->
		<link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet" />

		@include('layouts.header')

	</head>

	<body class="app sidebar-mini white-background <?php echo $themeClass; ?>">

		<div id="loader-line" class="hidden"></div>

		<!-- Page -->
		<div class="page">
			<div class="page-main">
				
				<!-- App-Content -->			
				<div class="main-content">
					<div class="side-app">

						@yield('content')

					</div>                   
				</div>
		
		</div><!-- End Page -->

		@include('layouts.footer-frontend')
        
	</body>
</html>


