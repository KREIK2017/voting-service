<x-guest-layout>
    <h1 class="h4 mb-3 text-center">{{ __('messages.nav.register') }}</h1>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('validation.attributes.name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   required autofocus autocomplete="name">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('validation.attributes.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autocomplete="username">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('validation.attributes.password') }}</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('validation.attributes.password_confirmation') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" required autocomplete="new-password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">{{ __('validation.attributes.role') }}</label>
            <select id="role" name="role"
                    class="form-select @error('role') is-invalid @enderror" required>
                <option value="voter" {{ old('role', 'voter') === 'voter' ? 'selected' : '' }}>
                    {{ __('messages.dashboard.role_voter') }}
                </option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                    {{ __('messages.dashboard.role_admin') }}
                </option>
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>{{ __('messages.nav.register') }}
            </button>
        </div>

        <div class="text-center small">
            <a class="text-decoration-none" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
        </div>
    </form>
</x-guest-layout>
