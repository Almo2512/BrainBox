<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        \App\Models\User::create([
            'name' => 'AlmotasemAdmin',
            'email' => 'Almotasem@Ahmed.com',
            'password' => Hash::make('aahmed12345A§'),
            'role' => 'admin',
        ]);
        \App\Models\User::create([
            'name' => 'AlmotasemUser',
            'email' => 'Almotasem233@Ahmed.com',
            'password' => Hash::make('aahmed12345AS@'),
            'role' => 'user',
        ]);
        
    }
    
}
