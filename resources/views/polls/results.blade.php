<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('polls.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="h4 mb-0">{{ $poll->title }}</h1>
            @include('admin.polls._status', ['poll' => $poll])
        </div>
        <div class="text-muted small mt-1">
            <i class="bi bi-graph-up me-1"></i>{{ __('polls.results.heading') }}
            · {{ __('polls.results.total_votes', ['count' => $totalVotes]) }}
        </div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($options->isEmpty())
                <div class="alert alert-warning mb-0">{{ __('polls.show.no_options') }}</div>
            @elseif ($totalVotes === 0)
                <div class="alert alert-info mb-0">{{ __('polls.results.no_votes') }}</div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach ($options as $option)
                        @php
                            $count = $option->votes_count;
                            $pct = $totalVotes > 0 ? round($count / $totalVotes * 100, 1) : 0;
                            $isMine = in_array($option->id, $myOptionIds, true);
                            $progressStyle = 'width: '.$pct.'%';
                        @endphp
                        <div class="{{ $isMine ? 'p-3 border border-primary border-2 rounded bg-primary-subtle' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
                                    <span class="badge bg-light text-dark">#{{ $option->order }}</span>
                                    <span>{{ $option->text }}</span>
                                    @if ($isMine)
                                        <span class="badge bg-primary">
                                            <i class="bi bi-check2 me-1"></i>{{ __('polls.results.your_choice') }}
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted text-nowrap">{{ $count }} · {{ $pct }}%</small>
                            </div>
                            <div class="progress" role="progressbar"
                                 aria-label="{{ $option->text }}"
                                 aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                                 style="height: 10px;">
                                <div class="progress-bar {{ $isMine ? 'bg-primary' : 'bg-secondary' }}"
                                     style="{{ $progressStyle }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('polls.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>{{ __('polls.results.back_to_polls') }}
        </a>
        <a href="{{ route('votes.my') }}" class="btn btn-outline-secondary">
            <i class="bi bi-check2-circle me-1"></i>{{ __('polls.my.heading') }}
        </a>
    </div>
</x-app-layout>
