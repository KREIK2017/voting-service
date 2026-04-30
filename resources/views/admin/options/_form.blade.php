@php
    /** @var \App\Models\Option $option */
    /** @var \App\Models\Poll $poll */
@endphp

<div class="mb-3">
    <label for="text" class="form-label">{{ __('polls.fields.option_text') }} <span class="text-danger">*</span></label>
    <input id="text" type="text" name="text" value="{{ old('text', $option->text) }}"
           class="form-control @error('text') is-invalid @enderror"
           required minlength="1" maxlength="500" autofocus>
    @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="order" class="form-label">{{ __('polls.fields.order') }}</label>
    <input id="order" type="number" name="order" value="{{ old('order', $option->order) }}"
           class="form-control @error('order') is-invalid @enderror"
           min="0" step="1">
    @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>{{ __('messages.common.save') }}
    </button>
    <a href="{{ route('admin.polls.show', $poll) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('messages.common.back') }}
    </a>
</div>
