<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Note;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [];
        for ($i = 1; $i <= 10; $i++){
            $note = new Note([
                'id' => $i,
                'description' => "Note $i",
                'HashedId' => Hash::make($i),
                'user_id' => null,
                'group_id' => null,
                'todo_list_id' => null,
            ]);
            $note->save();
            $notes[] = $note;
        }
    }
    
}
