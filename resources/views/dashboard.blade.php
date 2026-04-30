@php $user = auth()->user(); @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-1">{{ __('messages.dashboard.welcome', ['name' => $user->name]) }}</h1>
                <span class="badge bg-{{ $user->isAdmin() ? 'warning text-dark' : 'info text-dark' }}">
                    {{ $user->isAdmin() ? __('messages.dashboard.role_admin') : __('messages.dashboard.role_voter') }}
                </span>
            </div>
            <i class="bi bi-speedometer2 fs-2 text-muted"></i>
        </div>
    </x-slot>

    @if (! $user->hasVerifiedEmail())
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
            <div>{{ __('messages.dashboard.verify_pending') }}</div>
        </div>
    @endif

    @if ($user->isAdmin())
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5">{{ __('messages.dashboard.admin_panel') }}</h2>
                <p class="text-muted">{{ __('messages.dashboard.admin_intro') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ url('/admin/polls') }}" class="btn btn-primary">
                        <i class="bi bi-bar-chart-line me-1"></i>{{ __('messages.dashboard.manage_polls') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-1"></i>{{ __('messages.dashboard.profile_link') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary fs-1"><i class="bi bi-collection"></i></div>
                        <div class="display-6 fw-bold">{{ \App\Models\Poll::count() }}</div>
                        <div class="text-muted small">{{ __('polls.title') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success fs-1"><i class="bi bi-list-check"></i></div>
                        <div class="display-6 fw-bold">{{ \App\Models\Option::count() }}</div>
                        <div class="text-muted small">{{ __('polls.fields.options') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info fs-1"><i class="bi bi-people"></i></div>
                        <div class="display-6 fw-bold">{{ \App\Models\Vote::count() }}</div>
                        <div class="text-muted small">{{ __('polls.results.total_votes', ['count' => '']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5">{{ __('messages.dashboard.voter_panel') }}</h2>
                <p class="text-muted">{{ __('messages.dashboard.voter_intro') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ url('/polls') }}" class="btn btn-primary">
                        <i class="bi bi-bar-chart-line me-1"></i>{{ __('messages.dashboard.browse_polls') }}
                    </a>
                    <a href="{{ url('/my-votes') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-check2-circle me-1"></i>{{ __('messages.dashboard.my_votes_link') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-1"></i>{{ __('messages.dashboard.profile_link') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary fs-1"><i class="bi bi-bar-chart-line"></i></div>
                        <div class="display-6 fw-bold">{{ \App\Models\Poll::where('is_active', true)->count() }}</div>
                        <div class="text-muted small">{{ __('polls.list.voter_heading') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success fs-1"><i class="bi bi-check2-circle"></i></div>
                        <div class="display-6 fw-bold">{{ $user->votes()->count() }}</div>
                        <div class="text-muted small">{{ __('messages.nav.my_votes') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
