<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SurveyResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'response_limit' => $this->response_limit,
            'active' => $this->active,
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'user' => new UserResource($this->whenLoaded('user')),
            'practice' => new PracticeResource($this->whenLoaded('practice')),
            'group' => new GroupResource($this->whenLoaded('group')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
