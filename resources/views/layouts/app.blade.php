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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
     .level {
         display: flex;
         align-items: center;
         _justify-content: flex-start;
     }
     .flex {
         flex: 1;
     }
     .mr-1 {margin-right: 1em;}
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
    </head>
    <body>
        <div id="app">
            <!-- nav -->
            @include('layouts.nav')

	    <div class="container">
		<flash message="{{session('flash')}}"></flash>
		@yield('content')
	    </div>
	</div>

	<!-- Scripts -->
	
    </body>
    <script src="{{ asset('js/app.js') }}"></script>
</html>
