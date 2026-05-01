<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'poll_id' => $this->poll_id,
            'text' => $this->text,
            'order' => $this->order,
            'votes_count' => $this->whenCounted('votes'),
            'created_at' => $this->created_at,
        ];
    }
}
