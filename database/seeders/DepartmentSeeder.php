<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Программирование',
            'Техническое обслуживание, ремонт и эксплуатация автомобильного транспорта',
            'Строительство и дизайн',
            'Сервис и Экономика',
        ];

        foreach ($departments as $title) {
            DB::table('departments')->insert([
                'title' => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
