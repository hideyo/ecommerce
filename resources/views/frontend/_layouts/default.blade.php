<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Frontend" />
        <meta name="author" content="" />
        <title>@yield('meta_title')</title>
        <meta name="description" content="@yield('meta_description')" />
        <meta name="keywords" content="@yield('meta_keywords')" />
        <meta name="robots" content="index, follow" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="{{ URL::asset('/css/frontend.css')}}">  
    </head>
    <body>
        @include('frontend._partials.header')
        <div class=" container">      
            @yield('main')
        </div>
        @include('frontend._partials.footer')
        <script src="{{ URL::asset('/js/frontend.js')}}"></script>
    </body>
</html>