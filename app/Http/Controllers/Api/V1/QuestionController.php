<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;

final class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function store(QuestionRequest $request): QuestionResource
    {
        $validated = $request->validated();
        $validated['survey_id'] = $request->survey_id; // Добавляем survey_id
        $question = $this->questionService->createQuestion($validated);

        return new QuestionResource($question);
    }

    public function update(QuestionRequest $request, $id): QuestionResource
    {
        $validated = $request->validated();
        $question = $this->questionService->updateQuestion($id, $validated);

        return new QuestionResource($question);
    }

    public function destroy($id): JsonResponse
    {
        $this->questionService->deleteQuestion($id);

        return response()->json(['message' => 'Вопрос успешно удален']);
    }
}
