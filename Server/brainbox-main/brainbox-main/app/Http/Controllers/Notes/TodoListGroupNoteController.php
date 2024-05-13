<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Models\Note;
use App\Models\Group;
use App\Models\User;
use App\Models\TodoList;



use Illuminate\Http\Request;

class TodoListGroupNoteController extends Controller
{
    public function index(User $user, Group $group, TodoList $todolist)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');


        $note = $todolist->notes()->get();
        return $note;


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user, Group $group, TodoList $todolist)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');

        if ($todolist->notes()->exists()) {
            return response()->json(['error' => 'A note with the same todoList_id already exists.'], 422);
        }
        $randomValue = mt_rand(1000, 199900) . time();
        $base64Encoded = base64_encode($randomValue);
        $hashId = Hash::make($base64Encoded);
        $validatedData = $request->validate([
            'description' => 'required|max:100',
        ]);

        $note = new Note($validatedData);
        $note->HashedId = $hashId;
        $todolist->notes()->save($note);
        return $note;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Group $group, TodoList $todolist, Note $note)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');
        abort_if(!$note, 404, "note not found");

        return $note;
    }


    public function update(Request $request, User $user, Group $group, TodoList $todolist, Note $note)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');
        abort_if(!$note, 404, "note not found");

        $this->validate($request, [
            'description' => 'required|max:100',
        ]);
        $note->update([
            'description' => $request->description,
        ]);
        return $note;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Group $group, TodoList $todolist, Note $note)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');
        abort_if(!$note, 404, "note not found");

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }

}
