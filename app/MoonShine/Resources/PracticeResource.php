<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Practice;
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
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Practice>
 */
final class PracticeResource extends ModelResource
{
    protected string $model = Practice::class;

    protected string $title = 'Практики';

    protected string $column = 'title';

    protected int $itemsPerPage = 10;

    protected array $with = ['specialization'];

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
            Text::make('Название', 'title')->unescape(),
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
                Textarea::make('Описание', 'description')->nullable(),
                BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class)
                    ->required()
                    ->searchable(),
                Switcher::make('Активность', 'active'),
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
            Text::make('Название', 'title')->unescape(),
            Textarea::make('Описание', 'description'),
            BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class),
            Text::make('Активность', 'active', fn ($item) => $item->active ? 'Активный' : 'Неактивный')
                ->badge(fn ($value) => $value === 1 ? 'green' : 'red')->sortable(),
        ];
    }

    /**
     * @param  Practice  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'active' => ['boolean'],
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make('Специальность', 'specialization', 'title', SpecializationResource::class)
                ->nullable()
                ->searchable(),
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'description',
            'specialization.title',
        ];
    }

    protected function components(): iterable
    {
        $model = Practice::query()->paginate();

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
