<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">
                <i class="bi bi-pencil me-1"></i>{{ __('polls.actions.edit') }}
            </h1>
            <a href="{{ route('admin.polls.show', $poll) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-eye me-1"></i>{{ __('polls.actions.view') }}
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.polls.update', $poll) }}" novalidate>
                @csrf
                @method('PUT')
                @include('admin.polls._form', ['poll' => $poll])
            </form>
        </div>
    </div>
</x-app-layout>
