<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\AnswerOption;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Core\Paginator\PaginatorCaster;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<AnswerOption>
 */
final class AnswerOptionResource extends ModelResource
{
    protected string $model = AnswerOption::class;

    protected string $title = 'Варианты ответов';

    protected int $itemsPerPage = 10;

    protected array $with = ['question'];

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
            Text::make('Вариант', 'title'),
            Number::make('Номер порядка', 'order')->sortable(),
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
                Text::make('Вариант', 'title')->required(),
                Number::make('Номер порядка', 'order')->required(),
                BelongsTo::make('Вопрос', 'question', 'title', QuestionResource::class)
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
        ];
    }

    /**
     * @param  AnswerOption  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
            'question_id' => ['required', 'exists:questions,id'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make('Вопрос', 'question', 'title', QuestionResource::class)
                ->nullable()
                ->searchable(),
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)
                ->nullable()
                ->searchable(),
            Number::make('Номер порядка', 'order'),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'question.title',
        ];
    }

    protected function components(): iterable
    {
        $model = AnswerOption::query()->paginate();

        $paginator = (new PaginatorCaster(
            $model->appends(request()->except('page'))->toArray(),
            $model->items()
        ))->cast();

        return [
            TableBuilder::make()
                ->fields([
                    Text::make('Name'),
                ])
                ->items($paginator),
        ];
    }
}
