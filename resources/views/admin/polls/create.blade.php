<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">
            <i class="bi bi-plus-lg me-1"></i>{{ __('polls.actions.create') }}
        </h1>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.polls.store') }}" novalidate>
                @csrf
                @include('admin.polls._form', ['poll' => $poll])
            </form>
        </div>
    </div>
</x-app-layout>
