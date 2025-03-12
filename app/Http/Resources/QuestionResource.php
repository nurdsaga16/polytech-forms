<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'question_type' => $this->question_type,
            'order' => $this->order,
            'answer_options' => AnswerOptionResource::collection($this->whenLoaded('answerOptions')),
        ];
    }
}
