<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Note;
use App\Models\TodoList;
use App\Models\Group;




class AdminController extends Controller
{
    public function getUsers($user)
    {


        $adminUser = User::find($user);


        if ($adminUser && $adminUser->hasRole('admin')) {
            // If the user is found and has the 'admin' role
            $users = User::select('id', 'name', 'email', 'role')->get();
            return $users->isEmpty()
                ? response()->json(['error' => 'no users found'], 200)
                : response()->json(['users' => $users], 200);
        } else {
            return response()->json(['error' => 'permission denied'], 403);
        }
    }
    public function search($adminId, $userEmail)
    {
        $adminUser = User::find($adminId);
        if (!$adminUser || !$adminUser->hasRole('admin')) {
            abort(404, 'permission denied');
        }


        try {
            $user = User::where('email', $userEmail)
                ->with([
                    'notes',
                    'todolists' => function ($query) {
                        $query->with('notes', 'tasks');
                    },
                    'groups' => function ($query) {
                        $query->with('todolists.notes', 'todolists.tasks');
                    },
                ])
                ->firstOrFail();

            return $user;
        } catch (ModelNotFoundException $e) {
            abort(404, 'User not found');
        }
    }
    public function blockUser($adminId, $userEmail)
    {
        
            $adminUser = User::find($adminId);
    
            if (!$adminUser || !$adminUser->hasRole('admin')) {
                abort(404, 'Permission denied');
            }
    
            $user = User::where('email', $userEmail)->first();
    
            if (!$user) {
                abort(404, 'User not found');
            }
    
            $user->update(['blocked' => true]);
            return response()->json(['message' => "User with the E-mail {$userEmail} has been successfully blocked."]);
        
    }
    

    public function unblockUser($adminId, $userEmail)
    {
        $adminUser = User::find($adminId);
        if (!$adminUser || !$adminUser->hasRole('admin')) {
            abort(404, 'permission denied');
        }
        ;
        $user = User::where('email', $userEmail);
        abort_if(!$user, 404, 'user not found');
        $user->update(['blocked' => false]);
        return response()->json(['message' => "User with the E-mail {$userEmail} has been successfully unblocked."]);
    }





}
