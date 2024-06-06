<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Aplikasi',
            
            'email' => 'admin@ukmolahraga.com',
            'password' => bcrypt('admin'),
            'remember_token' => Str::random(60),
        ]);
        User::create([
            'name' => 'root',
           
            'email' => 'root@email.com',
            'password' => bcrypt('root'),
            'remember_token' => Str::random(60),
        ]);

        User::create([
            'name' => 'anggota',
        
            'email' => 'anggota@email.com',
            'password' => bcrypt('anggota'),
            'remember_token' => Str::random(60),
        ]);

        
    }
}

