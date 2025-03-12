<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class ResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'survey_id' => 'required|exists:surveys,id',

            // Валидация текстовых ответов (если они есть)
            'text_answers' => 'nullable|array',
            'text_answers.*.question_id' => 'required_with:text_answers|exists:questions,id',
            'text_answers.*.answer' => 'required_with:text_answers|string',

            // Валидация ответов с выбором (если они есть)
            'choice_answers' => 'nullable|array',
            'choice_answers.*.question_id' => 'required_with:choice_answers|exists:questions,id',
            'choice_answers.*.answer_option_id' => 'required_with:choice_answers|exists:answer_options,id',

            // Валидация ответов по шкале (если они есть)
            'scale_answers' => 'nullable|array',
            'scale_answers.*.question_id' => 'required_with:scale_answers|exists:questions,id',
            'scale_answers.*.answer' => 'required_with:scale_answers|integer|min:1|max:10',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'survey_id.required' => 'ID опроса обязателен.',
            'survey_id.exists' => 'Указанный опрос не существует.',

            'text_answers.*.question_id.required_if' => 'ID вопроса обязателен для текстового ответа.',
            'text_answers.*.answer.required_if' => 'Текстовый ответ обязателен для указанного вопроса.',
            'text_answers.*.answer.string' => 'Текстовый ответ должен быть строкой.',

            'choice_answers.*.question_id.required_if' => 'ID вопроса обязателен для ответа с выбором.',
            'choice_answers.*.answer_option_id.required_if' => 'ID варианта ответа обязателен для ответа с выбором.',
            'choice_answers.*.answer_option_id.exists' => 'Указанный вариант ответа не существует.',

            'scale_answers.*.question_id.required_if' => 'ID вопроса обязателен для ответа по шкале.',
            'scale_answers.*.answer.required_if' => 'Ответ по шкале обязателен для указанного вопроса.',
            'scale_answers.*.answer.integer' => 'Ответ по шкале должен быть числом.',
            'scale_answers.*.answer.min' => 'Минимальное значение ответа по шкале — 1.',
            'scale_answers.*.answer.max' => 'Максимальное значение ответа по шкале — 10.',
        ];
    }
}
