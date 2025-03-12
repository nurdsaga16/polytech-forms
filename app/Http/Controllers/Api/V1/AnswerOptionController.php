<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AnswerOptionRequest;
use App\Http\Resources\AnswerOptionResource;
use App\Services\AnswerOptionService;
use Illuminate\Http\JsonResponse;

final class AnswerOptionController extends Controller
{
    protected $answerOptionService;

    public function __construct(AnswerOptionService $answerOptionService)
    {
        $this->answerOptionService = $answerOptionService;
    }

    public function store(AnswerOptionRequest $request): AnswerOptionResource
    {
        $validated = $request->validated();
        $validated['question_id'] = $request->question_id; // Добавляем question_id
        $answerOption = $this->answerOptionService->createAnswerOption($validated);

        return new AnswerOptionResource($answerOption);
    }

    public function update(AnswerOptionRequest $request, $id): AnswerOptionResource
    {
        $validated = $request->validated();
        $answerOption = $this->answerOptionService->updateAnswerOption($id, $validated);

        return new AnswerOptionResource($answerOption);
    }

    public function destroy($id): JsonResponse
    {
        $this->answerOptionService->deleteAnswerOption($id);

        return response()->json(['message' => 'Вариант ответа успешно удален']);
    }
}
