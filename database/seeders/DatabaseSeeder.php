<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DepartmentSeeder::class);
        $this->call(SpecializationSeeder::class);
        $this->call(PracticeSeeder::class);
        $this->call(GroupSeeder::class);
    }
}
