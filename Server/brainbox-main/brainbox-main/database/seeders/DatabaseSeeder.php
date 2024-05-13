<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Seeders\GroupSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            GroupsSeeder::class,
            NoteSeeder::class,
            TodoSeeder::class,
            TaskSeeder::class,
        ]);
    }
    
}
