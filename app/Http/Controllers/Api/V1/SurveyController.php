<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Services\SurveyService;
use Illuminate\Http\JsonResponse;

final class SurveyController extends Controller
{
    protected $surveyService;

    public function __construct(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function index()
    {
        $surveys = $this->surveyService->getAllSurveys();

        return SurveyResource::collection($surveys);
    }

    public function store(SurveyRequest $request): SurveyResource
    {
        $validated = $request->validated();
        $survey = $this->surveyService->createSurvey($validated);

        return new SurveyResource($survey);
    }

    public function show($identifier): SurveyResource
    {
        $survey = $this->surveyService->getSurveyById($identifier);

        return new SurveyResource($survey);
    }

    public function update(SurveyRequest $request, $id): SurveyResource
    {
        $validated = $request->validated();
        $survey = $this->surveyService->updateSurvey($id, $validated);

        return new SurveyResource($survey);
    }

    public function destroy($id): JsonResponse
    {
        $this->surveyService->deleteSurvey($id);

        return response()->json(['message' => 'Опрос успешно удален']);
    }
}
