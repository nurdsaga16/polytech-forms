<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Question;

final class QuestionService
{
    public function createQuestion(array $data): Question
    {
        $answerOptions = $data['answer_options'] ?? [];
        unset($data['answer_options']);

        $question = Question::create($data);

        foreach ($answerOptions as $optionData) {
            $question->answerOptions()->create($optionData);
        }

        return $question->load('answerOptions');
    }

    public function updateQuestion($id, array $data): Question
    {
        $question = Question::findOrFail($id);

        $answerOptions = $data['answer_options'] ?? [];
        unset($data['answer_options']);

        $question->update($data);

        // Удаляем старые варианты ответов только если переданы новые
        if (! empty($answerOptions)) {
            $question->answerOptions()->delete();
            foreach ($answerOptions as $optionData) {
                $question->answerOptions()->create($optionData);
            }
        }

        return $question->load('answerOptions');
    }

    public function deleteQuestion($id): void
    {
        $question = Question::findOrFail($id);
        $question->delete();
    }
}
