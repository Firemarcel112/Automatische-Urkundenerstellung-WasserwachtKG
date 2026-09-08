@if (session()->has('success'))
    @foreach ((array) session('success') as $message)
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            @includeif('icons.info')
            {{ $message }}
            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
        </div>
    @endforeach
    @php
        session()->forget('success');
    @endphp
@endif

@if (session()->has('error'))
    @foreach ((array) session('error') as $message)
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            @includeif('icons.alert-triangle')
            {{ $message }}
            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
        </div>
    @endforeach
    @php
        session()->forget('error');
    @endphp
@endif
