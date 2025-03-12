<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Question;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Enum;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Question>
 */
final class QuestionResource extends ModelResource
{
    protected string $model = Question::class;

    protected string $title = 'Вопросы';

    protected int $itemsPerPage = 10;

    protected array $with = ['survey'];

    protected bool $cursorPaginate = true;

    protected bool $columnSelection = true;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected SortDirection $sortDirection = SortDirection::ASC;

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Вопрос', 'title'),
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)->sortable(),
            Text::make('Тип вопроса', 'question_type', fn ($item) => match ($item->question_type) {
                'text' => 'Текст',
                'multiple_choice' => 'Множественный выбор',
                'scale' => 'Оценка',
            })->badge('purple')->sortable(),
            Number::make('Номер порядка', 'order')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Вопрос', 'title')->required(),
                Textarea::make('Описание', 'description')->nullable(),
                Enum::make('Тип вопроса', 'question_type')
                    ->options([
                        'text' => 'Текст',
                        'multiple_choice' => 'Множественный выбор',
                        'scale' => 'Оценка'])->required(),
                Number::make('Номер порядка', 'order')->required(),
                BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)
                    ->required()
                    ->searchable(),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Вопрос', 'title'),
            Textarea::make('Описание', 'description'),
            Text::make('Тип вопроса', 'question_type', fn ($item) => match ($item->question_type) {
                'text' => 'Текст',
                'multiple_choice' => 'Множественный выбор',
                'scale' => 'Оценка',
            })->badge('purple'),
            Number::make('Номер порядка', 'order'),
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class),
        ];
    }

    /**
     * @param  Question  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'question_type' => ['required', 'in:Текст,Множественный выбор,Оценка'],
            'order' => ['required', 'integer', 'min:1'],
            'survey_id' => ['required', 'exists:surveys,id'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)
                ->nullable()
                ->searchable(),
            Enum::make('Тип вопроса', 'question_type')
                ->options([
                    'text' => 'Текст',
                    'multiple_choice' => 'Множественный выбор',
                    'scale' => 'Оценка'])->nullable(),
            Number::make('Номер порядка', 'order'),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'description',
            'question_type',
            'survey.title',
        ];
    }
}
