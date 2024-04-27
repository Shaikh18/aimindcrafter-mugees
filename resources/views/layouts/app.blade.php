<?php
	$themeClass = '';
	if (!empty($_COOKIE['theme'])) {
		if ($_COOKIE['theme'] == 'dark') {
			$themeClass = 'dark-theme';
		} else if ($_COOKIE['theme'] == 'light') {
			$themeClass = 'light-theme';
		}
	} elseif (empty($_COOKIE['theme'])) {
		$themeClass = auth()->user()->theme;
		setcookie('theme', $themeClass);
	} else {
		$themeClass = config('settings.default_theme');
		setcookie('theme', $themeClass);
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
        <title>{{ config('app.name', 'AiMindCrafter') }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        @include('layouts.header')

	</head>

	<body class="app sidebar-mini <?php echo $themeClass; ?>">

		<div id="loader-line" class="hidden"></div>

		<!-- LOADER -->
		{{-- <div id="preloader" >
			<img src="{{URL::asset('img/svgs/preloader.gif')}}" alt="loader">
		</div> --}}
		<!-- END LOADER -->

		<!-- PAGE -->
		<div class="page">
			<div class="page-main">

				@include('layouts.nav-aside')

				<!-- APP CONTENT -->
				<div class="app-content main-content">

					<div class="side-app">

						@include('layouts.nav-top')

                        {{-- @include('layouts.flash') --}}

                        <div class="row">
                            <div class="col-md-12">
                                @if (session('message'))
                                    <div class="alert alert-{{ session('status') }}"> {{ session('message') }}</div>
                                @endif
                            </div>
                        </div>
						@yield('page-header')
						@yield('content')

                    </div>
                </div>
                <!-- END APP CONTENT -->

                @include('layouts.footer')

            </div>
        </div><!-- END PAGE -->

		@include('layouts.footer-backend')

	</body>
</html>


