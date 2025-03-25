<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Group;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Core\Paginator\PaginatorCaster;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Enum;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Group>
 */
final class GroupResource extends ModelResource
{
    protected string $model = Group::class;

    protected string $title = 'Группы';

    protected string $column = 'title';

    protected int $itemsPerPage = 10;

    protected array $with = ['user', 'specialization', 'department'];

    protected bool $columnSelection = true;

    protected SortDirection $sortDirection = SortDirection::ASC;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title'),
            Number::make('Курс', 'course')->sortable(),
            BelongsTo::make('Куратор', 'user', 'full_name', UserResource::class),
            BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class)->sortable(),
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
                Enum::make('Курс', 'course')->options([1 => '1', 2 => '2', 3 => '3'])->required(),
                BelongsTo::make('Куратор', 'user', 'full_name', UserResource::class)
                    ->required()
                    ->searchable(),
                BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class)
                    ->required()
                    ->searchable(),
                Switcher::make('Активный', 'active'),
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
            Text::make('Название', 'title'),
            Number::make('Курс', 'course'),
            BelongsTo::make('Куратор', 'user', 'full_name', UserResource::class),
            BelongsTo::make('Отделение', 'department', 'title', DepartmentResource::class),
            BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class),
            Text::make('Активность', 'active', fn ($item) => $item->active ? 'Активный' : 'Неактивный')
                ->badge(fn ($value) => $value === 1 ? 'green' : 'red')->sortable(),
        ];
    }

    /**
     * @param  Group  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:groups,title,'.($item->id ?? 'null')],
            'course' => ['required', 'integer', 'between:1,3'],
            'active' => ['boolean'],
            'user_id' => ['required', 'exists:users,id'],
            'specialization_id' => ['required', 'exists:specializations,id'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            Enum::make('Курс', 'course')->options([1 => '1', 2 => '2', 3 => '3'])->nullable(),
            BelongsTo::make('Отделение', 'department', 'title', DepartmentResource::class)
                ->nullable()
                ->searchable(),
            BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class)
                ->nullable()
                ->searchable(),
            BelongsTo::make('Куратор', 'user', 'full_name', UserResource::class)
                ->nullable()
                ->searchable(),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'user.full_name',
            'department.title',
            'specialization.title',
        ];
    }

    protected function components(): iterable
    {
        $model = Group::query()->paginate();

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
