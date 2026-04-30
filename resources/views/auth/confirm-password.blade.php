<x-guest-layout>
    <h1 class="h5 mb-3">{{ __('Confirm Password') }}</h1>
    <p class="text-muted small">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('validation.attributes.password') }}</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-shield-lock me-1"></i>{{ __('messages.common.confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
