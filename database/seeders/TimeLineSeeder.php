<?php

namespace Database\Seeders;

use App\Models\timeLine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        timeLine::create([
            'nama' => 'gelombang_1',
            'waktu_mulai'=>'2024-01-01',
            'waktu_berakhir'=>'2024-02-02',
            'status'=>0,
        ]);

        timeLine::create([
            'nama' => 'gelombang_2',
            'waktu_mulai'=>'2024-01-01',
            'waktu_berakhir'=>'2024-02-02',
            'status'=>0,
        ]);
    }
}
