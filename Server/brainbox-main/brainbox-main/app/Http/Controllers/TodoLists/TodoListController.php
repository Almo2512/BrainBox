<?php

namespace App\Http\Controllers\TodoLists;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\TodoList;
use App\Models\User;



class TodoListController extends Controller
{
    public function index(User $user)
    {
        abort_if(!$user,404,"user not found");
        $todoList = $user->todoLists()->get();
        return $todoList;
    }

    public function store(Request $request,  User $user)
    {
        abort_if(!$user,404,"user not found");

        $this->validate($request, [
            'title' => 'required|max:20',
        ]);
        $randomValue = mt_rand(1000, 199900) . time();
        $base64Encoded = base64_encode($randomValue);
        $hashId = Hash::make($base64Encoded);

        $todolist = new TodoList();
        $todolist->title = $request->title;
        $todolist->HashedId = $hashId;
        $user->todoLists()->save($todolist);

        return $todolist;
    }

    public function show(User $user,TodoList $todolist)
    {
        abort_if(!$user,404,"user not found");

        return $todolist;
    }

    public function update(Request $request,User $user, todoList $todoList)
    {
        abort_if(!$user,404,"user not found");
        abort_if(!$todoList,404,"user not found");


        $this->validate($request, [
            'title' => 'required|max:20',
        ]);
       

        
        $todoList->update([
            'title' => $request->title,
        ]);

        return $todoList;
    }
    public function destroy(Request $request, User $user,TodoList $todoList)
    {
        abort_if(!$user,404,"user not found");
        abort_if(!$todoList,404,"user not found");

        $todoList->delete();
        return response()->json(['message' => 'TodoList deleted successfully']);
    }
    
   
}