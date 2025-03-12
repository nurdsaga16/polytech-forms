<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\AnswerOptionResource;
use App\MoonShine\Resources\ChoiceAnswerResource;
use App\MoonShine\Resources\DepartmentResource;
use App\MoonShine\Resources\GroupResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\PracticeResource;
use App\MoonShine\Resources\QuestionResource;
use App\MoonShine\Resources\ResponseResource;
use App\MoonShine\Resources\ScaleAnswerResource;
use App\MoonShine\Resources\ScheduleResource;
use App\MoonShine\Resources\SpecializationResource;
use App\MoonShine\Resources\SurveyResource;
use App\MoonShine\Resources\TextAnswerResource;
use App\MoonShine\Resources\UserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;

final class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     */
    public function boot(CoreContract $core, ConfiguratorContract $config, ColorManager $colorManager): void
    {
        // Настройка цветовой палитры
        $colorManager->bulkAssign([
            'primary' => '120, 67, 233', // #7843E9
            'secondary' => '236, 65, 118', // #EC4180
            'body' => '27, 37, 59',

            'dark' => [
                'DEFAULT' => '30, 31, 67',
                50 => '83, 103, 132',
                100 => '74, 90, 121',
                200 => '65, 81, 114',
                300 => '53, 69, 103',
                400 => '48, 61, 93',
                500 => '41, 53, 82',
                600 => '40, 51, 78',
                700 => '39, 45, 69',
                800 => '27, 37, 59',
                900 => '15, 23, 42',
            ],

            'success-bg' => '0, 170, 0',
            'success-text' => '255, 255, 255',
            'warning-bg' => '255, 220, 42',
            'warning-text' => '139, 116, 0',
            'error-bg' => '224, 45, 45',
            'error-text' => '255, 255, 255',
            'info-bg' => '0, 121, 255',
            'info-text' => '255, 255, 255',
        ]);

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                DepartmentResource::class,
                SpecializationResource::class,
                UserResource::class,
                GroupResource::class,
                PracticeResource::class,
                ScheduleResource::class,
                SurveyResource::class,
                QuestionResource::class,
                AnswerOptionResource::class,
                ResponseResource::class,
                TextAnswerResource::class,
                ChoiceAnswerResource::class,
                ScaleAnswerResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ]);
    }
}
