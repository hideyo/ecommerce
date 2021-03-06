<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Admin Panel" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin</title>

        @include('backend._partials.assets')
    </head>
    <body>
        @include('backend._partials.header')
        <div class="container-fluid">
      
            @yield('main')
        </div>

        @include('backend._partials.footer')
    </body>
</html>