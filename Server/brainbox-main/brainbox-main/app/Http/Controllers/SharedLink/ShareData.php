<?php

namespace App\Http\Controllers\SharedLink;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\TodoList;
use App\Models\Note;
use App\Models\Group;
use App\Models\User;

class ShareData extends Controller
{
    public function makeNoteLink($userId, $noteId)
    {
        $user=User::find($userId);
        $note = Note::find($noteId);
        if ($note && $note->user_id == $user->id) {
             $hashedId = $note->HashedId;     
   
                $link = url("{$hashedId}");
                return $link;}

             else {
                return response()->json(['message' => 'Note not found '], 404);
            }
        

    }
    public function makeNoteGroupLink($userId, $groupId,$noteId)
    {
        $user=User::find($userId);
        $noteGroup = Group::find($noteId);
        $note = Note::find($noteId);
        if ($note) {
             $hashedId = $note->HashedId;     
   
                $link = url("/{$hashedId}");
                return $link;}

             else {
                return response()->json(['message' => 'Note not found '], 404);
            }
        

    }
    public function makeTodoLink($userId, $todolistId)
    {
        $todolist = TodoList::find($todolistId);
        if ($todolist->user_id == $userId) {
             $hashedId = $todolist->HashedId;     
   
                $link = url("/{$hashedId}");
                return $link;}

             else {
                return response()->json(['message' => 'todolist not found '], 404);
            }
        

    }
    public function makeTodoGroupLink($userId,$groupId, $todolistId)
    {
        $todolist = TodoList::find($todolistId);
        if ($todolist->user_id == $userId && $todolist->group_id == $groupId) {
             $hashedId = $todolist->HashedId;     
   
                $link = url("/{$hashedId}");
                return $link;}

             else {
                return response()->json(['message' => 'todolist not found '], 404);
            }
        

    }
    //die folgende function ist für groups, falls die benötigt bitte benutzen.
   /* public function makeGroupLink($userId, $groupId){
        $group = Group::find($groupId);
        if ($group) {
            $title = $group->title;
            $hashedId=Hash::make($title);
            $link = url("/{$hashedId}");
            return $link;
        }
        
        else {
            return response()->json(['message' => 'Group not found '], 404);
        }
        
    }*/
}
