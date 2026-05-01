<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'poll_id' => $this->poll_id,
            'option_id' => $this->option_id,
            'created_at' => $this->created_at,

            'user' => new UserResource($this->whenLoaded('user')),
            'poll' => new PollResource($this->whenLoaded('poll')),
            'option' => new OptionResource($this->whenLoaded('option')),
        ];
    }
}
