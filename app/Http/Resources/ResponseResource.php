<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'survey_id' => $this->survey_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'text_answers' => $this->whenLoaded('textAnswers', function () {
                return $this->textAnswers->map(function ($answer) {
                    return [
                        'id' => $answer->id,
                        'question_id' => $answer->question_id,
                        'answer' => $answer->answer,
                        'created_at' => $answer->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $answer->updated_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),
            'choice_answers' => $this->whenLoaded('choiceAnswers', function () {
                return $this->choiceAnswers->map(function ($answer) {
                    return [
                        'id' => $answer->id,
                        'question_id' => $answer->question_id,
                        'answer_option_id' => $answer->answer_option_id,
                        'created_at' => $answer->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $answer->updated_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),
            'scale_answers' => $this->whenLoaded('scaleAnswers', function () {
                return $this->scaleAnswers->map(function ($answer) {
                    return [
                        'id' => $answer->id,
                        'question_id' => $answer->question_id,
                        'answer' => $answer->answer,
                        'created_at' => $answer->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $answer->updated_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),
        ];
    }
}
