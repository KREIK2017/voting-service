<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">
            <i class="bi bi-bar-chart-line me-1"></i>{{ __('polls.list.voter_heading') }}
        </h1>
    </x-slot>

    @if ($polls->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                {{ __('messages.home.no_active_polls') }}
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($polls as $poll)
                @php $hasVoted = $poll->hasVoted(auth()->user()); @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                @include('admin.polls._status', ['poll' => $poll])
                                <small class="text-muted">
                                    <i class="bi bi-people me-1"></i>{{ $poll->votes_count }}
                                </small>
                            </div>
                            <h2 class="h6 fw-semibold">{{ $poll->title }}</h2>
                            @if ($poll->description)
                                <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($poll->description, 120) }}</p>
                            @endif
                            <ul class="list-unstyled small text-muted mb-3">
                                <li><i class="bi bi-list-check me-1"></i>{{ __('polls.list.options_count', ['count' => $poll->options_count]) }}</li>
                                @if ($poll->ends_at)
                                    <li><i class="bi bi-calendar-event me-1"></i>{{ __('polls.fields.ends_at') }}: {{ $poll->ends_at->format('d.m.Y H:i') }}</li>
                                @endif
                                @if ($poll->allow_multiple)
                                    <li><i class="bi bi-check2-all me-1"></i>{{ __('polls.show.select_multiple') }}</li>
                                @endif
                            </ul>
                            <div class="mt-auto d-grid gap-2">
                                @if ($hasVoted)
                                    <a href="{{ route('polls.results', $poll) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-graph-up me-1"></i>{{ __('polls.show.view_results') }}
                                    </a>
                                @elseif ($poll->isActive())
                                    <a href="{{ route('polls.show', $poll) }}" class="btn btn-primary">
                                        <i class="bi bi-check2-square me-1"></i>{{ __('polls.actions.vote') }}
                                    </a>
                                @else
                                    <a href="{{ route('polls.results', $poll) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-graph-up me-1"></i>{{ __('polls.show.view_results') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $polls->withQueryString()->links() }}
        </div>
    @endif
</x-app-layout>
