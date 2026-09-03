<!DOCTYPE html>
<html data-bs-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.head')

    <body>
        <div class="page">
            @include('layouts.navbar')
            <div class="container">
                <div class="page-wrapper">
                    @include('layouts.alerts')
                    @yield('content')
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </body>

</html>
