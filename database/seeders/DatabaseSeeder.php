<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(DivisiSeeder::class);
        $this->call(JadwalSeeder::class);
        $this->call(PendaftaranSeeder::class);
        $this->call(AlatSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(TimeLineSeeder::class);
    }
}
