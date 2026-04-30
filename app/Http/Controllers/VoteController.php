<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoteController extends Controller
{
    public function index(): View
    {
        $polls = Poll::where('is_active', true)
            ->withCount(['votes', 'options'])
            ->latest()
            ->paginate(12);

        return view('polls.index', compact('polls'));
    }

    public function show(Request $request, Poll $poll): View|RedirectResponse
    {
        if (! $poll->isActive()) {
            return redirect()
                ->route('polls.index')
                ->with('warning', __('polls.flash.vote_not_active'));
        }

        if ($poll->hasVoted($request->user())) {
            return redirect()
                ->route('polls.results', $poll)
                ->with('success', __('polls.show.already_voted'));
        }

        $poll->load('options');

        return view('polls.show', compact('poll'));
    }

    public function vote(Request $request, Poll $poll): RedirectResponse
    {
        if (! $poll->isActive()) {
            return redirect()
                ->route('polls.index')
                ->with('warning', __('polls.flash.vote_not_active'));
        }

        $user = $request->user();

        if ($poll->hasVoted($user)) {
            return redirect()
                ->route('polls.results', $poll)
                ->with('warning', __('polls.flash.vote_already'));
        }

        if ($poll->allow_multiple) {
            $data = $request->validate([
                'option_ids' => ['required', 'array', 'min:1'],
                'option_ids.*' => [
                    'integer',
                    'distinct',
                    Rule::exists('options', 'id')->where('poll_id', $poll->id),
                ],
            ]);
            $optionIds = $data['option_ids'];
        } else {
            $data = $request->validate([
                'option_id' => [
                    'required', 'integer',
                    Rule::exists('options', 'id')->where('poll_id', $poll->id),
                ],
            ]);
            $optionIds = [$data['option_id']];
        }

        DB::transaction(function () use ($poll, $user, $optionIds) {
            foreach ($optionIds as $optionId) {
                Vote::create([
                    'user_id' => $user->id,
                    'poll_id' => $poll->id,
                    'option_id' => $optionId,
                ]);
            }
        });

        return redirect()
            ->route('polls.results', $poll)
            ->with('success', __('polls.flash.vote_recorded'));
    }

    public function results(Poll $poll): View
    {
        $options = $poll->options()->withCount('votes')->orderBy('order')->get();
        $totalVotes = $poll->votesCount();

        $myOptionIds = $poll->votes()
            ->where('user_id', auth()->id())
            ->pluck('option_id')
            ->all();

        return view('polls.results', compact('poll', 'options', 'totalVotes', 'myOptionIds'));
    }

    public function myVotes(Request $request): View
    {
        $votes = Vote::where('user_id', $request->user()->id)
            ->with(['poll', 'option'])
            ->latest()
            ->paginate(15);

        return view('votes.my', compact('votes'));
    }
}
