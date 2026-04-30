<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.polls.show', $poll) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="h4 mb-0">
                    <i class="bi bi-people me-1"></i>{{ __('polls.admin_votes.heading') }}
                </h1>
            </div>
            <div class="text-muted small">
                {{ $poll->title }} · {{ __('polls.results.total_votes', ['count' => $votes->total()]) }}
            </div>
        </div>
    </x-slot>

    @if ($votes->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                {{ __('polls.admin_votes.empty') }}
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('polls.admin_votes.col_user') }}</th>
                            <th>{{ __('polls.admin_votes.col_email') }}</th>
                            <th>{{ __('polls.admin_votes.col_option') }}</th>
                            <th>{{ __('polls.admin_votes.col_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($votes as $vote)
                            <tr>
                                <td>{{ $vote->user?->name ?? '—' }}</td>
                                <td class="text-muted small">{{ $vote->user?->email ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark me-1">#{{ $vote->option?->order }}</span>
                                    {{ $vote->option?->text ?? '—' }}
                                </td>
                                <td class="text-muted small">{{ $vote->created_at->format('d.m.Y H:i') }}</td>
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
