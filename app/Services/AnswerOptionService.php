<?php

namespace App\Services;

use App\Models\AnswerOption;

class AnswerOptionService
{
    public function createAnswerOption(array $data): AnswerOption
    {
        return AnswerOption::create($data);
    }

    public function updateAnswerOption($id, array $data): AnswerOption
    {
        $answerOption = AnswerOption::findOrFail($id);
        $answerOption->update($data);
        return $answerOption;
    }

    public function deleteAnswerOption($id): void
    {
        $answerOption = AnswerOption::findOrFail($id);
        $answerOption->delete();
    }
}