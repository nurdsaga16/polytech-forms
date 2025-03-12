<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Survey;
use Illuminate\Support\Facades\Auth;

final class SurveyService
{
    public function getAllSurveys()
    {
        $userId = Auth::id(); // ID авторизованного пользователя

        return Survey::with(['schedule', 'user', 'practice', 'group', 'questions.answerOptions'])
            ->whereHas('schedule', function ($query) use ($userId) {
                $query->where('user_id', $userId); // Фильтруем по user_id через schedule
            })
            ->paginate();
    }

    public function getSurveyById($id)
    {
        return Survey::with(['schedule', 'user', 'practice', 'group', 'questions.answerOptions'])->findOrFail($id);
    }

    public function createSurvey(array $data): Survey
    {
        $survey = Survey::create($data);

        return $survey->load(['schedule', 'user', 'practice', 'group', 'questions.answerOptions']);
    }

    public function updateSurvey($id, array $data): Survey
    {
        $survey = Survey::findOrFail($id);
        $survey->update($data);

        return $survey->load(['schedule', 'user', 'practice', 'group', 'questions.answerOptions']);
    }

    public function deleteSurvey($id): void
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();
    }
}
