<?php


namespace App\Http\Controllers\Tasks;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;


class TaskController extends Controller
{
    public function index(User $user,TodoList $todolist)
    {
        abort_if(!$user || !$todolist,404,"user or todolist not found");
        abort_if(!$todolist->belongsToUser($user), 403, 'User does not have access to this todolist.');       
        $tasks = $todolist->tasks()->get();
        return $tasks;
    }

    public function store(Request $request, User $user, TodoList $todolist)
    {
        abort_if(!$user ,404,"user or todolist not found");
        abort_if(!$todolist->belongsToUser($user), 403, 'User does not have access to this todolist.'); 
        $validatedData = $request->validate([
            'title' => 'required|max:20',
        ]);

        $task = new Task($validatedData);
        $todolist->tasks()->save($task);

        return $task;
    }
    

    public function update(Request $request,User $user, TodoList $todolist, Task $task)
    {
        abort_if(!$user || !$todolist,404,"user or todolist not found");
        abort_if(!$todolist->belongsToUser($user), 403, 'User does not have access to this todolist.'); 
        
        $this->validate($request, [
            'title' => 'required|max:50',
        ]);
        $task->update([
            'title' => $request->title,
        ]);
        return $task;

    }
    public function show(User $user,TodoList $todolist,  $taskId)
    {
        abort_if(!$user ,404,"user or todolist not found");
        abort_if(!$todolist->belongsToUser($user), 403, 'User does not have access to this todolist.'); 
       
        $task = Task::where('id', $taskId)
        ->where('todo_list_id', $todolist->id)
        ->first();
       
        return $task;

    }

    public function destroy(User $user,TodoList $todolist, Task $task)
    {

        abort_if(!$user ,404,"user or todolist not found");
        abort_if(!$todolist->belongsToUser($user), 403, 'User does not have access to this todolist.'); 
        abort_if(!$task,404,'task not found');
        
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }



}
