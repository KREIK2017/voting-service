<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOptionRequest;
use App\Http\Requests\Admin\UpdateOptionRequest;
use App\Models\Option;
use App\Models\Poll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OptionController extends Controller
{
    public function create(Poll $poll): View
    {
        Gate::authorize('create', [Option::class, $poll]);

        $option = new Option([
            'order' => ($poll->options()->max('order') ?? 0) + 1,
        ]);

        return view('admin.options.create', compact('poll', 'option'));
    }

    public function store(StoreOptionRequest $request, Poll $poll): RedirectResponse
    {
        $data = $request->validated();
        $data['order'] ??= ($poll->options()->max('order') ?? 0) + 1;

        $poll->options()->create($data);

        return redirect()
            ->route('admin.polls.show', $poll)
            ->with('success', __('polls.flash.option_created'));
    }

    public function edit(Option $option): View
    {
        Gate::authorize('update', $option);

        $poll = $option->poll;

        return view('admin.options.edit', compact('poll', 'option'));
    }

    public function update(UpdateOptionRequest $request, Option $option): RedirectResponse
    {
        $option->update($request->validated());

        return redirect()
            ->route('admin.polls.show', $option->poll)
            ->with('success', __('polls.flash.option_updated'));
    }

    public function destroy(Option $option): RedirectResponse
    {
        Gate::authorize('delete', $option);

        if ($option->poll->votes()->exists()) {
            abort(422, __('polls.flash.cant_delete_option_with_votes'));
        }

        $poll = $option->poll;
        $option->delete();

        return redirect()
            ->route('admin.polls.show', $poll)
            ->with('success', __('polls.flash.option_deleted'));
    }
}
