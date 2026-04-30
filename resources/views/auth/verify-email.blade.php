<x-guest-layout>
    <h1 class="h5 mb-3"><i class="bi bi-envelope-check me-1"></i>{{ __('Verify Email') }}</h1>

    <p class="text-muted small">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success small">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center justify-content-between mt-3">
        <form method="POST" action="{{ route('verification.send') }}" class="d-grid d-sm-block">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-1"></i>{{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="d-grid d-sm-block">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none">{{ __('messages.nav.logout') }}</button>
        </form>
    </div>
</x-guest-layout>
