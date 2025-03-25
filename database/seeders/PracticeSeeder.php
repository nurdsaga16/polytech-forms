<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class PracticeSeeder extends Seeder
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

        // Массив практик (без description)
        $practices = [
            ['title' => 'УП по ОС'],
            ['title' => 'УП по верстке'],
            ['title' => 'УП по прог'],
            ['title' => 'УП по сетям'],
            ['title' => 'УП по комп гр'],
            ['title' => 'УП по front'],
            ['title' => 'УП по АИС'],
            ['title' => 'УП по прог ВУ'],
            ['title' => 'УП по проект'],
            ['title' => 'УП по мконтр'],
            ['title' => 'УП по ООП'],
            ['title' => 'УП по web'],
            ['title' => 'УП по аппарат'],
            ['title' => 'УП UI/UX'],
            ['title' => 'УП по без ОС'],
            ['title' => 'УП по марш'],
            ['title' => 'УП по БД'],
            ['title' => 'УП по адм'],
            ['title' => 'УП по мобил'],
            ['title' => 'УП по введ в ИБ'],
            ['title' => 'УП по осн ИТ'],
        ];

        // Заполнение таблицы practices
        foreach ($practices as $practice) {
            DB::table('practices')->insert([
                'title' => $practice['title'],
                'description' => null, // Оставляем пустым
                'active' => true, // Значение по умолчанию из схемы
                'specialization_id' => $specializationId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
