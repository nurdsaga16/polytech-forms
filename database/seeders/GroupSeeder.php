<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем ID специальности "Программное обеспечение (по видам)" из таблицы specializations
        $specializationId = DB::table('specializations')
            ->where('title', 'Программное обеспечение (по видам)')
            ->value('id');

        // Проверяем, найдена ли специальность
        if (! $specializationId) {
            throw new \Exception('Специальность "Программное обеспечение (по видам)" не найдена в базе данных.');
        }

        // Массив названий групп
        $groups = [
            'П-23-60гб',
            'П-23-61б',
            'П-23-62б',
            'Иб-23-4гб',
            'Иб-23-5б',
            'Ис-23-18гб',
            'Ис-23-19б',
            'Иб-22-2гб',
            'Иб-22-3б',
            'Ис-22-16гб',
            'Ис-22-17б',
            'П-22-58гб',
            'П-22-59б',
            'ИБ-24-6гб',
            'ИБ-24-7б',
            'ИБ-24-8к',
            'П-24-63ГБ',
            'П-24-64ГБ',
            'П-24-65ГБ',
            'П-24-66Б',
            'П-24-67Б',
            'П-24-68Б',
            'П-24-69К',
            'ИС-24-20ГБ',
            'ИС-24-21ГБ',
            'ИС-24-22Б',
            'ИС-24-23Б',
        ];

        // Заполнение таблицы groups
        foreach ($groups as $title) {
            // Извлекаем курс из названия группы (вторая часть, например, 24, 23, 22)
            preg_match('/-(\d{2})-/', $title, $matches);
            $courseYear = $matches[1] ?? null;
            $course = $this->getCourseFromYear($courseYear);

            DB::table('groups')->insert([
                'title' => $title,
                'course' => $course,
                'specialization_id' => $specializationId,
                'user_id' => 1, // Устанавливаем user_id как 1 для всех групп
                'active' => true, // Значение по умолчанию из схемы
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Определяет курс на основе года в названии группы.
     */
    private function getCourseFromYear(?string $year): int
    {
        return match ($year) {
            '24' => 1,
            '23' => 2,
            '22' => 3,
            default => 1, // По умолчанию курс 1, если год не распознан
        };
    }
}
