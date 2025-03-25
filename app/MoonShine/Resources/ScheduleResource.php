<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Schedule;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Core\Paginator\PaginatorCaster;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Schedule>
 */
final class ScheduleResource extends ModelResource
{
    protected string $model = Schedule::class;

    protected string $title = 'График';

    protected int $itemsPerPage = 10;

    protected array $with = ['user', 'group', 'practice'];

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
            BelongsTo::make('Практика', 'practice', 'title', PracticeResource::class)->sortable(),
            BelongsTo::make('Преподаватель', 'user', 'full_name', UserResource::class)->sortable(),
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
                BelongsTo::make('Практика', 'practice', 'title', PracticeResource::class)
                    ->required()
                    ->searchable(),
                BelongsTo::make('Преподаватель', 'user', 'full_name', UserResource::class)
                    ->required()
                    ->searchable(),
                BelongsTo::make('Группа', 'group', 'title', GroupResource::class)
                    ->required()
                    ->searchable(),
                Date::make('Начало', 'start_date')->required(),
                Date::make('Конец', 'end_date')->required(),
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
            BelongsTo::make('Практика', 'practice', 'title', PracticeResource::class),
            BelongsTo::make('Преподаватель', 'user', 'full_name', UserResource::class),
            BelongsTo::make('Группа', 'group', 'title', GroupResource::class),
            Date::make('Начало', 'start_date')->format('d.m.Y'),
            Date::make('Конец', 'end_date')->format('d.m.Y'),
        ];
    }

    /**
     * @param  Schedule  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'practice_id' => ['required', 'exists:practices,id'],
            'user_id' => ['required', 'exists:users,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
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
            Date::make('Начало', 'start_date')->nullable(),
            Date::make('Конец', 'end_date')->nullable(),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'practice.title',
            'practice.description',
            'user.full_name',
            'group.title',
            'start_date',
            'end_date',
        ];
    }

    protected function components(): iterable
    {
        $model = Schedule::query()->paginate();

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
