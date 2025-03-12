<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ResponseRequest;
use App\Http\Resources\ResponseResource;
use App\Models\ChoiceAnswer;
use App\Models\Response as SurveyResponse;
use App\Models\ScaleAnswer;
use App\Models\TextAnswer;

final class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responses = SurveyResponse::with(['textAnswers', 'choiceAnswers', 'scaleAnswers'])->get();

        return ResponseResource::collection($responses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResponseRequest $request)
    {
        // Получение валидированных данных
        $responseData = $request->validated();

        // Создание основного ответа
        $response = SurveyResponse::create(['survey_id' => $responseData['survey_id']]);

        // Сохранение текстовых ответов
        if (isset($responseData['text_answers'])) {
            foreach ($responseData['text_answers'] as $textAnswer) {
                TextAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $textAnswer['question_id'],
                    'answer' => $textAnswer['answer'],
                ]);
            }
        }

        // Сохранение ответов с выбором
        if (isset($responseData['choice_answers'])) {
            foreach ($responseData['choice_answers'] as $choiceAnswer) {
                ChoiceAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $choiceAnswer['question_id'],
                    'answer_option_id' => $choiceAnswer['answer_option_id'],
                ]);
            }
        }

        // Сохранение ответов по шкале
        if (isset($responseData['scale_answers'])) {
            foreach ($responseData['scale_answers'] as $scaleAnswer) {
                ScaleAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $scaleAnswer['question_id'],
                    'answer' => $scaleAnswer['answer'],
                ]);
            }
        }

        return new ResponseResource($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyResponse $response)
    {
        $response->load(['textAnswers', 'choiceAnswers', 'scaleAnswers']);

        return new ResponseResource($response);
    }
}
