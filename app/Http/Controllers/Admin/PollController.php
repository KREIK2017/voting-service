<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePollRequest;
use App\Http\Requests\Admin\UpdatePollRequest;
use App\Models\Poll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PollController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Poll::class);

        $polls = Poll::with('options')
            ->withCount(['options', 'votes'])
            ->latest()
            ->paginate(15);

        return view('admin.polls.index', compact('polls'));
    }

    public function create(): View
    {
        Gate::authorize('create', Poll::class);

        $poll = new Poll([
            'is_active' => true,
            'allow_multiple' => false,
        ]);

        return view('admin.polls.create', compact('poll'));
    }

    public function store(StorePollRequest $request): RedirectResponse
    {
        $poll = Poll::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.polls.show', $poll)
            ->with('success', __('polls.flash.created'));
    }

    public function show(Poll $poll): View
    {
        Gate::authorize('view', $poll);

        $poll->load(['options', 'user']);

        return view('admin.polls.show', compact('poll'));
    }

    public function edit(Poll $poll): View
    {
        Gate::authorize('update', $poll);

        return view('admin.polls.edit', compact('poll'));
    }

    public function update(UpdatePollRequest $request, Poll $poll): RedirectResponse
    {
        $poll->update($request->validated());

        return redirect()
            ->route('admin.polls.show', $poll)
            ->with('success', __('polls.flash.updated'));
    }

    public function destroy(Poll $poll): RedirectResponse
    {
        Gate::authorize('delete', $poll);

        $poll->delete();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', __('polls.flash.deleted'));
    }
}
