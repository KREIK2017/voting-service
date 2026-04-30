<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('polls.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="h4 mb-0">{{ $poll->title }}</h1>
            @include('admin.polls._status', ['poll' => $poll])
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-8">
            @if ($poll->description)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <p class="mb-0">{{ $poll->description }}</p>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-1">{{ __('polls.show.options') }}</h2>
                    <p class="text-muted small mb-3">
                        {{ $poll->allow_multiple ? __('polls.show.select_multiple') : __('polls.show.select_one') }}
                    </p>

                    @if ($poll->options->isEmpty())
                        <div class="alert alert-warning mb-0">{{ __('polls.show.no_options') }}</div>
                    @else
                        <form method="POST" action="{{ route('polls.vote', $poll) }}" novalidate>
                            @csrf
                            <div class="d-flex flex-column gap-2 mb-4">
                                @foreach ($poll->options as $option)
                                    <label class="form-check border rounded p-3 m-0 d-flex align-items-center"
                                           for="opt-{{ $option->id }}">
                                        @if ($poll->allow_multiple)
                                            <input id="opt-{{ $option->id }}"
                                                   type="checkbox"
                                                   name="option_ids[]"
                                                   value="{{ $option->id }}"
                                                   class="form-check-input me-2"
                                                   {{ in_array($option->id, (array) old('option_ids', [])) ? 'checked' : '' }}>
                                        @else
                                            <input id="opt-{{ $option->id }}"
                                                   type="radio"
                                                   name="option_id"
                                                   value="{{ $option->id }}"
                                                   class="form-check-input me-2"
                                                   {{ (int) old('option_id') === $option->id ? 'checked' : '' }}>
                                        @endif
                                        <span class="form-check-label flex-grow-1">{{ $option->text }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('option_id') <div class="alert alert-danger small">{{ $message }}</div> @enderror
                            @error('option_ids') <div class="alert alert-danger small">{{ $message }}</div> @enderror
                            @error('option_ids.*') <div class="alert alert-danger small">{{ $message }}</div> @enderror

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2-square me-1"></i>{{ __('polls.show.submit_vote') }}
                                </button>
                                <a href="{{ route('polls.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>{{ __('messages.common.back') }}
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">{{ __('polls.fields.created_by') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->user?->name ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.starts_at') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->starts_at?->format('d.m.Y H:i') ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.ends_at') }}</dt>
                        <dd class="col-7 mb-2">{{ $poll->ends_at?->format('d.m.Y H:i') ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('polls.fields.allow_multiple') }}</dt>
                        <dd class="col-7 mb-0">
                            {{ $poll->allow_multiple ? __('messages.common.yes') : __('messages.common.no') }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
