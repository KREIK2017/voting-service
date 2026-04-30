@php
    /** @var \App\Models\Poll $poll */
    if (! $poll->is_active) {
        $key = 'inactive'; $bg = 'secondary';
    } elseif ($poll->starts_at && now()->lt($poll->starts_at)) {
        $key = 'scheduled'; $bg = 'info text-dark';
    } elseif ($poll->ends_at && now()->gt($poll->ends_at)) {
        $key = 'finished'; $bg = 'dark';
    } else {
        $key = 'active'; $bg = 'success';
    }
@endphp
<span class="badge bg-{{ $bg }}">{{ __('polls.status.' . $key) }}</span>
