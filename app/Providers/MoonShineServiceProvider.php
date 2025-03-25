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
use MoonShine\Contracts\ColorManager\ColorManagerContract;
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
    public function boot(CoreContract $core, ConfiguratorContract $config, ColorManagerContract $colorManager): void
    {
        // Настройка цветовой палитры
        $colorManager->primary('59, 130, 246'); // Ярко-синий (#3B82F6)
        $colorManager->secondary('244, 63, 94'); // Малиновый (#F43F5E)

        $config->logo('storage/logo/logo-polytech-forms-v2.png')->logo('storage/logo/logo-polytech.png', small: true);

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
