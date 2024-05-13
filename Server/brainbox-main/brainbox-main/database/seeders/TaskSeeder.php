<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [];
        for ($i = 1; $i <= 10; $i++){
            $task = new Task([
                'id' => $i,
                'title' => "Task $i",
                'todo_list_id' => 1,
            ]);
            $task->save();
            $tasks[] = $task;
        }
    }
    
}
