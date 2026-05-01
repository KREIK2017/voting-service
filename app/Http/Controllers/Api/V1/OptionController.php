<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOptionRequest;
use App\Http\Requests\Admin\UpdateOptionRequest;
use App\Http\Resources\OptionResource;
use App\Models\Option;
use App\Models\Poll;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OptionController extends Controller
{
    public function store(StoreOptionRequest $request, Poll $poll): JsonResponse
    {
        $data = $request->validated();
        $data['order'] ??= ($poll->options()->max('order') ?? 0) + 1;

        $option = $poll->options()->create($data);
        $option->loadCount('votes');

        return (new OptionResource($option))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateOptionRequest $request, Option $option): OptionResource
    {
        $option->update($request->validated());
        $option->loadCount('votes');

        return new OptionResource($option);
    }

    public function destroy(Option $option): JsonResponse
    {
        Gate::authorize('delete', $option);

        if ($option->poll->votes()->exists()) {
            return response()->json([
                'message' => __('polls.flash.cant_delete_option_with_votes'),
            ], 422);
        }

        $option->delete();

        return response()->json(['message' => __('polls.flash.option_deleted')]);
    }
}
