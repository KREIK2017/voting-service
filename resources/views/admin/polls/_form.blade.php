@php
    /** @var \App\Models\Poll $poll */
    $startsAt = old('starts_at', optional($poll->starts_at)->format('Y-m-d\TH:i'));
    $endsAt = old('ends_at', optional($poll->ends_at)->format('Y-m-d\TH:i'));
    $isActive = (bool) old('is_active', $poll->is_active ?? true);
    $allowMultiple = (bool) old('allow_multiple', $poll->allow_multiple ?? false);
@endphp

<div class="mb-3">
    <label for="title" class="form-label">{{ __('polls.fields.title') }} <span class="text-danger">*</span></label>
    <input id="title" type="text" name="title" value="{{ old('title', $poll->title) }}"
           class="form-control @error('title') is-invalid @enderror"
           required minlength="3" maxlength="255" autofocus>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">{{ __('polls.fields.description') }}</label>
    <textarea id="description" name="description" rows="4"
              class="form-control @error('description') is-invalid @enderror"
              maxlength="2000">{{ old('description', $poll->description) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="starts_at" class="form-label">{{ __('polls.fields.starts_at') }}</label>
        <input id="starts_at" type="datetime-local" name="starts_at" value="{{ $startsAt }}"
               class="form-control @error('starts_at') is-invalid @enderror">
        @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="ends_at" class="form-label">{{ __('polls.fields.ends_at') }}</label>
        <input id="ends_at" type="datetime-local" name="ends_at" value="{{ $endsAt }}"
               class="form-control @error('ends_at') is-invalid @enderror">
        @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-check form-switch mt-3">
    <input type="hidden" name="is_active" value="0">
    <input id="is_active" type="checkbox" name="is_active" value="1"
           class="form-check-input @error('is_active') is-invalid @enderror"
           {{ $isActive ? 'checked' : '' }}>
    <label for="is_active" class="form-check-label">{{ __('polls.fields.is_active') }}</label>
    @error('is_active') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="form-check form-switch">
    <input type="hidden" name="allow_multiple" value="0">
    <input id="allow_multiple" type="checkbox" name="allow_multiple" value="1"
           class="form-check-input @error('allow_multiple') is-invalid @enderror"
           {{ $allowMultiple ? 'checked' : '' }}>
    <label for="allow_multiple" class="form-check-label">{{ __('polls.fields.allow_multiple') }}</label>
    @error('allow_multiple') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>{{ __('messages.common.save') }}
    </button>
    <a href="{{ route('admin.polls.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('messages.common.back') }}
    </a>
</div>
