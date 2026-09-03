@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        @includeif('icons.info')
        {{ session('success') }}
        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
        @includeif('icons.alert-triangle')
        {{ session('error') }}
        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
    </div>
@endif
