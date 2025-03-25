<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'email_verified_at' => $this->email_verified_at,
            // Расписания
            'schedules_created' => $this->schedules()->count(), // Количество созданных расписаний
            'active_schedules' => $this->schedules()
                ->where('end_date', '>=', now()) // Активные расписания (дата окончания >= текущая дата)
                ->count(),
            // Опросы
            'surveys_created' => $this->surveys()->count(), // Количество созданных опросов
            'active_surveys' => $this->surveys()
                ->where('active', 1) // Активные опросы (поле active = 1)
                ->count(),
        ];
    }
}
