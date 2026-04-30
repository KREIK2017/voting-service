@foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $bsType)
    @if (session()->has($key))
        <div class="alert alert-{{ $bsType }} alert-dismissible fade show" role="alert">
            <strong>{{ __('messages.flash.' . $key) }}.</strong> {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach

@if ($errors->any() && ! request()->routeIs(['login', 'register', 'password.*']))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('messages.flash.error') }}.</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
