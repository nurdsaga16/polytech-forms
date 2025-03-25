<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\User;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Core\Paginator\PaginatorCaster;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Url;

/**
 * @extends ModelResource<User>
 */
final class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Преподаватели';

    protected int $itemsPerPage = 10;

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
            Text::make('ФИО', 'full_name'),
            Email::make('Почта', 'email'),
            Url::make('Аватар', 'avatar'),
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
                Tabs::make([
                    Tab::make('Основная информация', [
                        ID::make(),
                        Text::make('ФИО', 'full_name'),
                        Email::make('Почта', 'email')->required(),
                        Url::make('Аватар', 'avatar')
                            ->nullable(),
                        Switcher::make('Активный', 'active'),
                    ])->icon('user-circle'),
                    Tab::make('Пароль', [
                        Password::make(__('moonshine::ui.resource.password'), 'password')
                            ->customAttributes(['autocomplete' => 'new-password'])
                            ->eye(),

                        PasswordRepeat::make(__('moonshine::ui.resource.repeat_password'), 'password_repeat')
                            ->customAttributes(['autocomplete' => 'confirm-password'])
                            ->eye(),
                    ])->icon('lock-closed'),
                ]),
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
     * @param  User  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.($item->id ?? 'NULL').',id'],
            'avatar' => ['nullable', 'string', 'url'],
            'active' => ['boolean'],
            'password' => $item->exists
                ? 'sometimes|nullable|min:6|required_with:password_repeat|same:password_repeat'
                : 'required|min:6|required_with:password_repeat|same:password_repeat',
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'full_name',
            'email',
        ];
    }

    protected function components(): iterable
    {
        $model = User::query()->paginate();

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
