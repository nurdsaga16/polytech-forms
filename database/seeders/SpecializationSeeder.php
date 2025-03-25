<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем ID отделений из таблицы departments (предполагаем, что они уже созданы)
        $departments = DB::table('departments')->pluck('id', 'title')->all();

        $specializations = [
            // Отделение "Программирование"
            ['title' => 'Радиотехника, электроника и телекоммуникации', 'department_id' => $departments['Программирование']],
            ['title' => 'Системы информационной безопасности', 'department_id' => $departments['Программирование']],
            ['title' => 'Программное обеспечение (по видам)', 'department_id' => $departments['Программирование']],

            // Отделение "Техническое обслуживание, ремонт и эксплуатация автомобильного транспорта"
            ['title' => 'Техническое обслуживание, ремонт и эксплуатация автомобильного транспорта', 'department_id' => $departments['Техническое обслуживание, ремонт и эксплуатация автомобильного транспорта']],
            ['title' => 'Монтаж и эксплуатация оборудования и систем газоснабжения', 'department_id' => $departments['Техническое обслуживание, ремонт и эксплуатация автомобильного транспорта']],

            // Отделение "Строительство и дизайн"
            ['title' => 'Строительство и эксплуатация зданий и сооружений', 'department_id' => $departments['Строительство и дизайн']],
            ['title' => 'Дизайн, реставрация и реконструкция гражданских зданий', 'department_id' => $departments['Строительство и дизайн']],

            // Отделение "Сервис и Экономика"
            ['title' => 'Учет и аудит', 'department_id' => $departments['Сервис и Экономика']],
        ];

        foreach ($specializations as $specialization) {
            DB::table('specializations')->insert([
                'title' => $specialization['title'],
                'department_id' => $specialization['department_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
