<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

final class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $schedules = Schedule::with(['user', 'group', 'practice'])
            ->where('user_id', Auth::id())
            ->paginate();

        return ScheduleResource::collection($schedules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request): ScheduleResource
    {
        $schedule = Schedule::create($request->validated());

        return new ScheduleResource($schedule->load(['user', 'group', 'practice']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule): ScheduleResource
    {
        // Загружаем связанные данные
        $schedule->load(['user', 'group', 'practice']);

        // Возвращаем данные расписания в виде ресурса
        return new ScheduleResource($schedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, Schedule $schedule): ScheduleResource
    {
        $schedule->update($request->validated());

        return new ScheduleResource($schedule->load(['user', 'group', 'practice']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json(['message' => 'Расписание успешно удалено']);
    }
}
