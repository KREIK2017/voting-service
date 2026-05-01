<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoteResource;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class VoteController extends Controller
{
    public function vote(Request $request, Poll $poll): JsonResponse
    {
        if (! $poll->isActive()) {
            return response()->json([
                'message' => __('polls.flash.vote_not_active'),
            ], 422);
        }

        $user = $request->user();

        if ($poll->hasVoted($user)) {
            return response()->json([
                'message' => __('polls.flash.vote_already'),
            ], 422);
        }

        $rules = [
            'option_ids' => ['required', 'array', 'min:1'],
            'option_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('options', 'id')->where('poll_id', $poll->id),
            ],
        ];

        if (! $poll->allow_multiple) {
            $rules['option_ids'][] = 'max:1';
        }

        $data = $request->validate($rules);

        DB::transaction(function () use ($poll, $user, $data) {
            foreach ($data['option_ids'] as $optionId) {
                Vote::create([
                    'user_id' => $user->id,
                    'poll_id' => $poll->id,
                    'option_id' => $optionId,
                ]);
            }
        });

        $poll->load(['options' => fn ($q) => $q->withCount('votes')->orderBy('order')])
            ->loadCount('votes');

        $totalVotes = $poll->votes_count;
        $myOptionIds = $data['option_ids'];

        $results = $poll->options->map(fn ($option) => [
            'id' => $option->id,
            'text' => $option->text,
            'order' => $option->order,
            'votes_count' => $option->votes_count,
            'percentage' => $totalVotes > 0 ? round($option->votes_count / $totalVotes * 100, 2) : 0,
            'is_my_choice' => in_array($option->id, $myOptionIds, true),
        ]);

        return response()->json([
            'message' => __('polls.flash.vote_recorded'),
            'total_votes' => $totalVotes,
            'results' => $results,
        ], 201);
    }

    public function myVotes(Request $request): AnonymousResourceCollection
    {
        $votes = Vote::where('user_id', $request->user()->id)
            ->with(['poll', 'option'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return VoteResource::collection($votes);
    }

    public function pollVotes(Request $request, Poll $poll): AnonymousResourceCollection
    {
        Gate::authorize('view', $poll);

        $votes = $poll->votes()
            ->with(['user', 'option'])
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return VoteResource::collection($votes);
    }
}
