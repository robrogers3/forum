<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/jquery.atwho.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>

    </style>
    <!-- Scripts -->
    <script>
     window.Laravel = {!!
			 json_encode([
			     'csrfToken' => csrf_token(),
			     'signedIn' => Auth::check(),
			     'user' => auth()->user()
			 ]);
		      !!};
    </script>
    @yield('head')
    </head>
    <body>
        <div id="app">
            <!-- nav -->
            @include('layouts.nav')

	    <div class="container">
		<token-reset></token-reset>
		@if (session('message'))
	
          	<div class="alert alert-{{ session('type') }}">
			{{session('message')}}
		    </div>
		@endif 

		@yield('content')
		<flash message="{{session('flash')}}"></flash>
	    </div>
	</div>

	<!-- Scripts -->
	
    </body>
    <script src="{{ asset('js/app.js') }}"></script>
</html>
