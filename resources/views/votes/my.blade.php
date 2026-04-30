<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">
            <i class="bi bi-check2-circle me-1"></i>{{ __('polls.my.heading') }}
        </h1>
    </x-slot>

    @if ($votes->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                {{ __('polls.my.empty') }}
                <div class="mt-3">
                    <a href="{{ route('polls.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-bar-chart-line me-1"></i>{{ __('messages.dashboard.browse_polls') }}
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('polls.my.col_poll') }}</th>
                            <th>{{ __('polls.my.col_option') }}</th>
                            <th>{{ __('polls.my.col_date') }}</th>
                            <th class="text-end">{{ __('messages.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($votes as $vote)
                            <tr>
                                <td>
                                    <a class="text-decoration-none fw-semibold"
                                       href="{{ route('polls.results', $vote->poll) }}">
                                        {{ $vote->poll->title }}
                                    </a>
                                </td>
                                <td>{{ $vote->option->text }}</td>
                                <td class="text-muted small">{{ $vote->created_at->format('d.m.Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('polls.results', $vote->poll) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-graph-up me-1"></i>{{ __('polls.actions.results') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $votes->withQueryString()->links() }}
        </div>
    @endif
</x-app-layout>
