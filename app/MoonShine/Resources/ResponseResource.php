<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Response;
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

/**
 * @extends ModelResource<Response>
 */
final class ResponseResource extends ModelResource
{
    protected string $model = Response::class;

    protected string $title = 'Отклики';

    protected array $with = ['survey'];

    protected int $itemsPerPage = 10;

    protected bool $cursorPaginate = true;

    protected bool $columnSelection = true;

    protected SortDirection $sortDirection = SortDirection::ASC;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

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
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)->sortable(),
            BelongsTo::make('Группа', 'group', 'title', GroupResource::class)->sortable(),
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
        ];
    }

    /**
     * @param  Response  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'survey_id' => ['required', 'exists:surveys,id'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make('Опрос', 'survey', 'title', SurveyResource::class)
                ->nullable()
                ->searchable(),
            BelongsTo::make('Группа', 'group', 'title', GroupResource::class)
                ->nullable()
                ->searchable(),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'survey.title',
            'group.title',
        ];
    }
}
