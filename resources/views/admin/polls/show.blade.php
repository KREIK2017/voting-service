<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.polls.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="h4 mb-0">{{ $poll->title }}</h1>
                @include('admin.polls._status', ['poll' => $poll])
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.polls.edit', $poll) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>{{ __('messages.common.edit') }}
                </a>
                <button type="button" class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal" data-bs-target="#deleteThisPoll">
                    <i class="bi bi-trash me-1"></i>{{ __('messages.common.delete') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase">{{ __('polls.fields.description') }}</h2>
                    <p class="mb-0">{{ $poll->description ?: '—' }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">{{ __('polls.show.options') }}</h2>
                        <a href="{{ route('admin.polls.options.create', $poll) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-lg me-1"></i>{{ __('polls.actions.add_option') }}
                        </a>
                    </div>

                    @if ($poll->options->isEmpty())
                        <div class="text-muted text-center py-4">
                            <i class="bi bi-list fs-2 d-block mb-2"></i>
                            {{ __('polls.show.no_options') }}
                        </div>
                    @else
                        @php $totalVotes = $poll->votesCount(); @endphp
                        <div class="d-flex flex-column gap-3">
                            @foreach ($poll->options as $option)
                                @php
                                    $count = $option->votesCount();
                                    $pct = $totalVotes > 0 ? round($count / $totalVotes * 100, 1) : 0;
                                    $progressStyle = 'width: '.$pct.'%';
                                @endphp
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3 min-w-0">
                                            <span class="badge bg-light text-dark">#{{ $option->order }}</span>
                                            <span class="text-truncate">{{ $option->text }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted text-nowrap">
                                                {{ $count }} · {{ $pct }}%
                                            </small>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.options.edit', $option) }}"
                                                   class="btn btn-outline-primary"
                                                   title="{{ __('polls.actions.edit_option') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteOpt{{ $option->id }}"
                                                        title="{{ __('polls.actions.delete_option') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress" role="progressbar"
                                         aria-label="{{ $option->text }}"
                                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                                         style="height: 8px;">
                                        <div class="progress-bar" style="{{ $progressStyle }}"></div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteOpt{{ $option->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.options.destroy', $option) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('polls.actions.delete_option') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-1"><strong>{{ $option->text }}</strong></p>
                                                    @if ($totalVotes > 0)
                                                        <div class="alert alert-warning small mb-0 mt-2">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            {{ __('polls.flash.cant_delete_option_with_votes') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        {{ __('messages.common.cancel') }}
                                                    </button>
                                                    <button type="submit" class="btn btn-danger" {{ $totalVotes > 0 ? 'disabled' : '' }}>
                                                        <i class="bi bi-trash me-1"></i>{{ __('messages.common.delete') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase">{{ __('messages.common.actions') }}</h2>
                    <div class="d-grid gap-2">
                        <a href="{{ route('polls.results', $poll) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-graph-up me-1"></i>{{ __('polls.actions.results') }}
                        </a>
                        <a href="{{ route('admin.polls.votes', $poll) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-people me-1"></i>{{ __('polls.actions.view_voters') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">{{ __('polls.fields.created_by') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->user?->name ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.created_at') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->created_at->format('d.m.Y H:i') }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.starts_at') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->starts_at?->format('d.m.Y H:i') ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.ends_at') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->ends_at?->format('d.m.Y H:i') ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.allow_multiple') }}</dt>
                        <dd class="col-7 mb-2">
                            {{ $poll->allow_multiple ? __('messages.common.yes') : __('messages.common.no') }}
                        </dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.options') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->options->count() }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.singular') }}</dt>
                        <dd class="col-7 mb-0">{{ $poll->votesCount() }} {{ strtolower(__('polls.results.total_votes', ['count' => ''])) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteThisPoll" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('polls.actions.delete') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0"><strong>{{ $poll->title }}</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('messages.common.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>{{ __('messages.common.delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
