<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class QuestionRequest extends FormRequest
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
            'survey_id' => ['required', 'exists:surveys,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'question_type' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:1'],
            'answer_options' => ['nullable', 'array'],
            'answer_options.*.title' => ['required_with:answer_options', 'string', 'max:255'],
            'answer_options.*.order' => ['required_with:answer_options', 'integer', 'min:1'],
        ];
    }

    /**
     * Условная валидация на основе типа вопроса.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'question_type' => $this->input('question_type'),
            'survey_id' => $this->input('survey_id'),
        ]);

        if (in_array($this->input('question_type'), ['text', 'scale'])) {
            $this->request->remove('answer_options');
        }
    }
}
