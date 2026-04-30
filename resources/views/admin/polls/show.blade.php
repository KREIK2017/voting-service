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
                        <button class="btn btn-sm btn-outline-primary" disabled
                                title="{{ __('polls.actions.add_option') }} ({{ __('messages.common.no_data') }})">
                            <i class="bi bi-plus-lg me-1"></i>{{ __('polls.actions.add_option') }}
                        </button>
                    </div>

                    @if ($poll->options->isEmpty())
                        <div class="text-muted text-center py-4">
                            <i class="bi bi-list fs-2 d-block mb-2"></i>
                            {{ __('polls.show.no_options') }}
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($poll->options as $option)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-light text-dark me-2">#{{ $option->order }}</span>
                                        {{ $option->text }}
                                    </div>
                                    <small class="text-muted">
                                        {{ __('polls.list.votes_count', ['count' => $option->votes()->count()]) }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase">{{ __('messages.common.actions') }}</h2>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-secondary disabled"
                           aria-disabled="true" title="{{ __('messages.common.no_data') }}">
                            <i class="bi bi-graph-up me-1"></i>{{ __('polls.actions.results') }}
                        </a>
                        <a href="#" class="btn btn-outline-secondary disabled"
                           aria-disabled="true" title="{{ __('messages.common.no_data') }}">
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
