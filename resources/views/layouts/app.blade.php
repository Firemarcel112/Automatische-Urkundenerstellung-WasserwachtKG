<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.head')

    <body>
        <div class="page">
            @include('layouts.navbar')
            <div class="container">
                <div class="page-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
    </body>

</html>
