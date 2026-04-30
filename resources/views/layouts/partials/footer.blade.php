<footer class="bg-dark text-white-50 py-3 mt-auto">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <small>
            &copy; {{ date('Y') }} {{ __('messages.app.name') }}.
            {{ __('messages.footer.rights') }}
        </small>
        <small class="text-white-50">
            <i class="bi bi-translate me-1"></i>{{ __('messages.nav.language') }}:
            <a class="link-light text-decoration-none {{ app()->getLocale() === 'uk' ? 'fw-semibold' : '' }}"
               href="{{ route('locale.switch', 'uk') }}">UA</a>
            /
            <a class="link-light text-decoration-none {{ app()->getLocale() === 'en' ? 'fw-semibold' : '' }}"
               href="{{ route('locale.switch', 'en') }}">EN</a>
        </small>
    </div>
</footer>
