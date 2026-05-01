<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'is_currently_active' => $this->isActive(),
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'allow_multiple' => (bool) $this->allow_multiple,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'options' => OptionResource::collection($this->whenLoaded('options')),
            'user' => new UserResource($this->whenLoaded('user')),

            'total_votes' => $this->resource->votes_count ?? $this->votes()->count(),
            'user_has_voted' => $user ? $this->hasVoted($user) : false,
        ];
    }
}
