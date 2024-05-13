<?php

namespace App\Http\Controllers\SharedLink;

use Illuminate\Http\Request;
use App\Models\TodoList;
use App\Models\Note;
use App\Models\Group;

use App\Http\Controllers\Controller;



class GetLinkData extends Controller
{
    public function findLinkData($hashedId)
    {

        $todolist = TodoList::where('HashedId', $hashedId)->with('notes', 'tasks')->first();
        $note = Note::where('HashedId', $hashedId)->first();



        if ($todolist) {
            return $todolist;
        }
        if ($note) {
            return $note;
        } else {
            return response()->json(['message' => 'link not valied'], 404);
        }

    }
}
