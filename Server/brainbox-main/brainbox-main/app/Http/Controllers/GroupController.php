<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;


class GroupController extends Controller
{
    public function index(User $user)
    {
        $groups = $user->groups();
        return $groups;
    }

    public function store(Request $request, User $user)
    {
        $user = User::find($user->id);
        abort_if(!$user, 404, 'User does not exist.');
        $this->validate($request, [
            'title' => 'required|max:50',
        ]);

        $group = new Group();
        $group->title = $request->title;
        $group->save();
        $user->groups()->attach($group->id, ['is_admin' => true]);

        return $group;
    }
    public function update(Request $request, User $user, Group $group)
    {
        $group = Group::find($group->id);

        abort_if(!$group, 404, 'Group not found.');

        $user = User::find($user->id);
        abort_if(!$user, 404, 'User does not exist.');

        $isAdmin = $user->groups()->where('group_id', $group->id)->where('is_admin', true)->exists();

        abort_if(!$isAdmin, 403, 'You do not have permission to update this group.');

        $this->validate($request, [
            'title' => 'required|max:50',
        ]);

        $group->update([
            'title' => $request->title,
        ]);

        return $group;
    }

    public function destroy(User $user, Group $group)
    {
        abort_if(!$group, 404, 'Group not found.');
        abort_if(!$user->isAdminOfGroup($group->id), 403, 'You do not have permission to delete this group.');
        $group->delete();

        return response()->json(['message' => 'Group deleted successfully']);
    }

    public function addMember(Request $request, User $adminId, Group $groupId)
{
    $this->validate($request, [
        'user_id' => 'required|exists:users,id',
    ]);

    $admin = User::findOrFail($adminId);
    $group = Group::findOrFail($groupId);

    abort_if(!$group->users()->where('user_id', $admin->id)->where('is_admin', true)->exists(), 403, 'Permission denied.');

    abort_if($group->users()->where('user_id', $request->user_id)->exists(), 400, 'User with ID ' . $request->user_id . ' is already a member of the group.');

    $group->users()->attach($request->user_id);

    return response()->json(['message' => 'User added to the group']);
}



public function removeMember(Request $request, User $adminId, Group $groupId)
{
    $this->validate($request, [
        'user_id' => 'required|exists:users,id',
    ]);

    try {
        $admin = User::findOrFail($adminId);
        $group = Group::findOrFail($groupId);

        abort_if(!$group->users()->where('user_id', $admin->id)->where('is_admin', true)->exists(), 403, 'Permission denied.');

        abort_if(!$group->users()->where('user_id', $request->user_id)->exists(), 404, 'User not found in the group.');

        $group->users()->detach($request->user_id);

        return response()->json(['message' => 'User removed from the group']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error processing the request'], 500);
    }
}


    public function makeAdmin(Request $request, User $adminId, Group $groupId)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $admin = User::findOrFail($adminId);
            $group = Group::findOrFail($groupId);
    
            abort_if(!$group->users()->where('user_id', $admin->id)->where('is_admin', true)->exists(), 403, 'Permission denied.');
    
            $userId = $request->input('user_id');
    
            abort_if(!$group->users()->where('id', $userId)->exists(), 404, 'User not found in the group.');
    
            $group->users()->updateExistingPivot($userId, ['is_admin' => true]);
    
            return response()->json(['message' => 'User is now an admin in the group']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error processing the request'], 500);
        }


}}