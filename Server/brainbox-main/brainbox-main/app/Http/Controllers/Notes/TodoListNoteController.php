<?php

namespace App\Http\Controllers\Notes;

use App\Models\TodoList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\User;



class TodoListNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user, TodoList $todolist)
    {
        abort_if(!$user || !$todolist, response()->json(['error' => 'User or todoist not found'], 404));
        $note = $todolist->notes()->get();
        return $note;


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user, TodoList $todolist)
    {
        abort_if(!$user || !$todolist,404,'User or todoist not found');

        if ($todolist->notes()->exists()) {
            return response()->json(['error' => 'A note with the same todoList_id already exists.'], 422);
        }
        //prevent "/" in the hashId
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
    public function show(User $user, TodoList $todolist, Note $note)
    {
        abort_if(!$user || !$todolist,404,'User or todoist not found');


        return $note;
    }

    public function update(Request $request, User $user, TodoList $todolist, Note $note)
    {
        abort_if(!$user || !$todolist, 404,'User or todoist not found');



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
    public function destroy(User $user, TodoList $todolist, Note $note)
    {

        abort_if(!$user || !$todolist,404,'User or todoist not found');

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
}
