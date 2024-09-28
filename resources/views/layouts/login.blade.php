<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('include.title_meta_favicon')

        @include('include.login_style')

        @stack('header')
    </head>

    <body>
        {{ $slot }}
        
        @include('include.login_script')

        @stack('footer')
    </body>
</html>
