<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbar-menu" data-bs-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3">
            <a aria-label="{{ config('app.name') }}" href=".">
                <img class="navbar-brand-image" src="{{ asset('images/logo.png') }}">
            </a>
        </div>
    </div>
</header>

<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-column flex-md-row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            <li class="nav-item @if (Route::is('home')) active @endif">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <span class="nav-link-title">Start</span>
                                </a>
                            </li>

                            <li class="nav-item @if (Route::is('documents.index')) active @endif">
                                <a class="nav-link" href="{{ route('documents.index') }}">
                                    <span class="nav-link-title">Dokumente</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-end">
                    <a aria-label="Tag-/Nachtmodus umschalten" class="btn btn-icon" data-bs-toggle="tooltip" id="theme-toggle" title="Tag-/Nachtmodus umschalten" type="button">
                        @include('icons.sun', ['classes' => 'd-none dark-icon'])
                        @include('icons.moon', ['classes' => 'd-none light-icon'])
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
