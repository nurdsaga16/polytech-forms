<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Survey;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Core\Paginator\PaginatorCaster;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Survey>
 */
final class SurveyResource extends ModelResource
{
    protected string $model = Survey::class;

    protected string $title = 'Опросы';

    protected string $column = 'title';

    protected int $itemsPerPage = 10;

    protected array $with = ['schedule', 'group', 'user', 'practice'];

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
            Text::make('Название', 'title'),
            BelongsTo::make('График', 'schedule', 'id', ScheduleResource::class),
            Text::make('Активность', 'active', fn ($item) => $item->active ? 'Активный' : 'Неактивный')
                ->badge(fn ($value) => $value === 1 ? 'green' : 'red')->sortable(),
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
                Text::make('Название', 'title')->required(),
                Textarea::make('Описание', 'description')->nullable(),
                Number::make('Лимит', 'response_limit')->nullable(),
                BelongsTo::make('График', 'schedule', 'id', ScheduleResource::class)
                    ->required()
                    ->searchable(),
                Switcher::make('Активность', 'active'),
                Switcher::make('Шаблон', 'template'),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            Text::make('Название', 'title'),
            Textarea::make('Описание', 'description'),
            Number::make('Лимит', 'response_limit'),
            BelongsTo::make('Практика', 'practice', 'title', PracticeResource::class),
            BelongsTo::make('Преподаватель', 'user', 'full_name', UserResource::class),
            BelongsTo::make('Группа', 'group', 'title', GroupResource::class),
            Text::make('Активность', 'active', fn ($item) => $item->active ? 'Активный' : 'Неактивный')
                ->badge(fn ($value) => $value === 1 ? 'green' : 'red')->sortable(),
            Text::make('Шаблон', 'template', fn ($item) => $item->template ? 'Да' : 'Нет')
                ->badge(fn ($value) => $value === 1 ? 'green' : 'red')->sortable(),
        ];
    }

    /**
     * @param  Survey  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'response_limit' => ['nullable', 'integer', 'min:1'],
            'schedule_id' => ['required', 'exists:schedules,id'],
            'active' => ['boolean'],
            'template' => ['boolean'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make('Практика', 'practice', 'title', PracticeResource::class)
                ->nullable()
                ->searchable(),
            BelongsTo::make('Преподаватель', 'user', 'full_name', UserResource::class)
                ->nullable()
                ->searchable(),
            BelongsTo::make('Группа', 'group', 'title', GroupResource::class)
                ->nullable()
                ->searchable(),
            Switcher::make('Шаблон', 'template')->nullable(),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'description',
            'practice.title',
            'user.full_name',
            'group.title',
        ];
    }

    protected function components(): iterable
    {
        $model = Survey::query()->paginate();

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
