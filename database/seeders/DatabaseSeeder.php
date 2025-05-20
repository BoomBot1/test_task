<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::factory()
            ->admin()
            ->create();

        $this->call([
            UserSeeder::class,
        ]);
    }
}
