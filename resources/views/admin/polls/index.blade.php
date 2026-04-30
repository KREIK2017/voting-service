<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">
                <i class="bi bi-bar-chart-line me-1"></i>{{ __('polls.list.admin_heading') }}
            </h1>
            <a href="{{ route('admin.polls.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>{{ __('polls.actions.create') }}
            </a>
        </div>
    </x-slot>

    @if ($polls->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                {{ __('polls.list.empty') }}
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('polls.fields.title') }}</th>
                            <th class="text-center">{{ __('polls.fields.is_active') }}</th>
                            <th class="text-center">{{ __('polls.fields.options') }}</th>
                            <th class="text-center">{{ __('polls.results.total_votes', ['count' => '']) }}</th>
                            <th>{{ __('polls.fields.starts_at') }} → {{ __('polls.fields.ends_at') }}</th>
                            <th class="text-end">{{ __('messages.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polls as $poll)
                            <tr>
                                <td>
                                    <a class="text-decoration-none fw-semibold"
                                       href="{{ route('admin.polls.show', $poll) }}">
                                        {{ $poll->title }}
                                    </a>
                                    @if ($poll->allow_multiple)
                                        <span class="badge bg-light text-dark ms-1" title="{{ __('polls.fields.allow_multiple') }}">
                                            <i class="bi bi-check2-all"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">@include('admin.polls._status', ['poll' => $poll])</td>
                                <td class="text-center">{{ $poll->options_count }}</td>
                                <td class="text-center">{{ $poll->votes_count }}</td>
                                <td class="small text-muted">
                                    {{ $poll->starts_at?->format('d.m.Y H:i') ?? '—' }}
                                    <br>
                                    {{ $poll->ends_at?->format('d.m.Y H:i') ?? '—' }}
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.polls.show', $poll) }}"
                                           class="btn btn-outline-secondary"
                                           title="{{ __('polls.actions.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.polls.edit', $poll) }}"
                                           class="btn btn-outline-primary"
                                           title="{{ __('polls.actions.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deletePoll{{ $poll->id }}"
                                                title="{{ __('polls.actions.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <div class="modal fade" id="deletePoll{{ $poll->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('polls.actions.delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p class="mb-0">
                                                            <strong>{{ $poll->title }}</strong>
                                                        </p>
                                                        <small class="text-muted">
                                                            {{ __('polls.list.options_count', ['count' => $poll->options_count]) }} ·
                                                            {{ __('polls.list.votes_count', ['count' => $poll->votes_count]) }}
                                                        </small>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $polls->withQueryString()->links() }}
        </div>
    @endif
</x-app-layout>
