<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\TextAnswer;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<TextAnswer>
 */
final class TextAnswerResource extends ModelResource
{
    protected string $model = TextAnswer::class;

    protected string $title = 'Текст';

    protected int $itemsPerPage = 10;

    protected array $with = ['question', 'response', 'survey'];

    protected bool $cursorPaginate = true;

    protected bool $columnSelection = true;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected SortDirection $sortDirection = SortDirection::ASC;

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::VIEW);
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Ответ', 'answer'),
            BelongsTo::make('ID отклика', 'response', 'id', ResponseResource::class)->sortable(),
            BelongsTo::make('Вопрос', 'question', 'title', QuestionResource::class)->sortable(),
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)->sortable(),

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
                Text::make('Ответ', 'answer')->required(),
                BelongsTo::make('ID отклика', 'response', 'id', ResponseResource::class)
                    ->searchable()
                    ->required(),
                BelongsTo::make('Вопрос', 'question', 'title', QuestionResource::class)
                    ->searchable()
                    ->required(),
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
        ];
    }

    /**
     * @param  TextAnswer  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'answer' => ['required', 'string'],
            'response_id' => ['required', 'exists:responses,id'],
            'question_id' => ['required', 'exists:questions,id'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make('ID отклика', 'response', 'id', ResponseResource::class)
                ->searchable()
                ->nullable(),
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)
                ->searchable()
                ->nullable(),
            BelongsTo::make('Вопрос', 'question', 'title', QuestionResource::class)
                ->searchable()
                ->nullable(),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'answer',
            'question.title',
            'survey.title',
        ];
    }
}
