<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\TodoList;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $todos = [];
        for ($i = 1; $i <= 10; $i++){
            $todo = new TodoList([
                'id' => $i,
                'title' => "Task $i",
                'HashedId' => Hash::make($i),
                'user_id' => null,
                'group_id' => null,
            ]);
            $todo->save();
            $todos[] = $todo;
        }
    }
    
}
