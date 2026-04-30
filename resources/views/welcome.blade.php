<x-app-layout>
    <section class="bg-white rounded-3 p-4 p-md-5 mb-4 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3">{{ __('messages.home.hero_title') }}</h1>
                <p class="lead text-muted mb-4">{{ __('messages.home.hero_lead') }}</p>
                @guest
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-plus me-1"></i>{{ __('messages.home.cta_register') }}
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('messages.home.cta_login') }}
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2 me-1"></i>{{ __('messages.nav.dashboard') }}
                    </a>
                @endguest
            </div>
            <div class="col-lg-5 text-center">
                <i class="bi bi-bar-chart-fill text-primary" style="font-size: 9rem;"></i>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <h2 class="h4 mb-3">{{ __('messages.home.features_title') }}</h2>
        <div class="row g-3">
            @foreach (['simple', 'secure', 'results'] as $i => $feat)
                @php
                    $icon = ['simple' => 'bi-lightning-charge', 'secure' => 'bi-shield-check', 'results' => 'bi-graph-up'][$feat];
                @endphp
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-primary fs-2 mb-2"><i class="bi {{ $icon }}"></i></div>
                            <h3 class="h5">{{ __('messages.home.feature_' . $feat . '_title') }}</h3>
                            <p class="text-muted mb-0">{{ __('messages.home.feature_' . $feat . '_text') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-3">
        <h2 class="h4 mb-3">{{ __('messages.home.active_polls') }}</h2>
        @if ($polls->isEmpty())
            <div class="alert alert-info mb-0">{{ __('messages.home.no_active_polls') }}</div>
        @else
            <div class="row g-3">
                @foreach ($polls as $poll)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-success">{{ __('polls.status.active') }}</span>
                                    <small class="text-muted">
                                        <i class="bi bi-people"></i> {{ $poll->votesCount() }}
                                    </small>
                                </div>
                                <h3 class="h6 fw-semibold">{{ $poll->title }}</h3>
                                @if ($poll->description)
                                    <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($poll->description, 90) }}</p>
                                @endif
                                <ul class="list-unstyled small text-muted mt-auto mb-0">
                                    <li>
                                        <i class="bi bi-list-check me-1"></i>
                                        {{ __('polls.list.options_count', ['count' => $poll->options->count()]) }}
                                    </li>
                                    @if ($poll->ends_at)
                                        <li>
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ __('polls.fields.ends_at') }}: {{ $poll->ends_at->format('d.m.Y') }}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</x-app-layout>
