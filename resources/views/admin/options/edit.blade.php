<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.polls.show', $poll) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="h4 mb-0">
                <i class="bi bi-pencil me-1"></i>{{ __('polls.actions.edit_option') }}
            </h1>
        </div>
        <div class="text-muted small mt-1">{{ $poll->title }}</div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.options.update', $option) }}" novalidate>
                @csrf
                @method('PUT')
                @include('admin.options._form', ['poll' => $poll, 'option' => $option])
            </form>
        </div>
    </div>
</x-app-layout>
