<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use App\Models\Group;



class GroupsTodolistsTasks extends Controller
{
    public function index(User $user, Group $group, TodoList $todolist)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');

        $tasks = $todolist->tasks()->get();
        return $tasks;
    }

    public function store(Request $request, User $user, Group $group, TodoList $todolist)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');
        
        $validatedData = $request->validate([
            'title' => 'required|max:20',
        ]);

        $task = new Task($validatedData);
        $todolist->tasks()->save($task);

        return $task;
    }

    public function update(Request $request, User $user, Group $group, TodoList $todolist, Task $task)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');

        $this->validate($request, [
            'title' => 'required|max:50',
        ]);
        $task->update([
            'title' => $request->title,
        ]);
        return $task;

    }
    public function show(User $user, Group $group, TodoList $todolist, Task $task)
    {
        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');



        return $task;
    }

    public function destroy(User $user, Group $group, TodoList $todolist, Task $task)
    {

        abort_if(!$user->belongsToGroup($group), 403, 'User does not have access to this group.');
        abort_if($todolist->group_id !== $group->id, 400, 'Todo list does not belong to the specified group.');




        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
