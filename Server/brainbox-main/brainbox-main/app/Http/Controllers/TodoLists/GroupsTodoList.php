<?php

namespace App\Http\Controllers\TodoLists;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\TodoList;

class GroupsTodoList extends Controller
{
    public function index(User $user, Group $group)
    {
        abort_if(!$user, 404, "user not Found");
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');

        $todoList = $group->todoLists()->get();
        return $todoList;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user, Group $group)
    {
        abort_if(!$user, 404, "user not Found");

        $validatedData = $request->validate([
            'title' => 'required|max:20',
        ]);
        $randomValue = mt_rand(1000, 199900) . time();
        $base64Encoded = base64_encode($randomValue);
        $hashId = Hash::make($base64Encoded);

        $todoList = new TodoList($validatedData);
        $todoList->HashedId = $hashId;
        $group->todoLists()->save($todoList);

        return $todoList;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Group $group, TodoList $todoList)
    {
        abort_if(!$user || !$group ||!$todoList ,404,"not Found");
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');


        return $todoList;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Group $group, TodoList $todoList)
    {
        abort_if(!$user || !$group ||!$todoList ,404,"not Found");
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');

        $this->validate($request, [
            'title' => 'required|max:100',
        ]);
        $todoList->update([
            'description' => $request->title,
        ]);
        return $todoList;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Group $group, TodoList $todoList)
    {
        abort_if(!$user || !$group ||!$todoList ,404,"not Found");
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');


        $$todoList->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
}
