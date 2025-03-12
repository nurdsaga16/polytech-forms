<?php

namespace App\Http\Resources;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PracticeResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'group' => new GroupResource($this->whenLoaded('group')),
            'practice' => new PracticeResource($this->whenLoaded('practice')),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
