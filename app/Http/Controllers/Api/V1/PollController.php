<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePollRequest;
use App\Http\Requests\Admin\UpdatePollRequest;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class PollController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        // Allow public viewing without Gate check if we want public polls
        // Gate::authorize('viewAny', Poll::class);

        $query = Poll::query()->withCount('votes');

        if ($request->user() && $request->user()->isAdmin()) {
            // Admin sees everything
        } else {
            // Guests and voters see only active polls
            $query->where('is_active', true);
        }

        $polls = $query->latest()->paginate($request->integer('per_page', 15));

        return PollResource::collection($polls);
    }

    public function show(Request $request, Poll $poll): PollResource
    {
        Gate::authorize('view', $poll);

        $poll->load(['options', 'user'])->loadCount('votes');

        return new PollResource($poll);
    }

    public function store(StorePollRequest $request): JsonResponse
    {
        $poll = Poll::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        $poll->load('options')->loadCount('votes');

        return (new PollResource($poll))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdatePollRequest $request, Poll $poll): PollResource
    {
        $poll->update($request->validated());

        $poll->load('options')->loadCount('votes');

        return new PollResource($poll);
    }

    public function destroy(Poll $poll): JsonResponse
    {
        Gate::authorize('delete', $poll);

        $poll->delete();

        return response()->json(['message' => __('polls.flash.deleted')]);
    }

    public function results(Request $request, Poll $poll): JsonResponse
    {
        Gate::authorize('view', $poll);

        $poll->load(['options' => fn ($q) => $q->withCount('votes')->orderBy('order')])
            ->loadCount('votes');

        $totalVotes = $poll->votes_count;
        $myOptionIds = $request->user()
            ? $poll->votes()->where('user_id', $request->user()->id)->pluck('option_id')->all()
            : [];

        $results = $poll->options->map(function ($option) use ($totalVotes, $myOptionIds) {
            return [
                'id' => $option->id,
                'text' => $option->text,
                'order' => $option->order,
                'votes_count' => $option->votes_count,
                'percentage' => $totalVotes > 0 ? round($option->votes_count / $totalVotes * 100, 2) : 0,
                'is_my_choice' => in_array($option->id, $myOptionIds, true),
            ];
        });

        return response()->json([
            'poll' => new PollResource($poll),
            'total_votes' => $totalVotes,
            'results' => $results,
        ]);
    }
}
