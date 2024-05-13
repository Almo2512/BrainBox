<?php


namespace App\Http\Controllers\Notes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Note;
use App\Models\Group;
use App\Models\User;


class GroupNote extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user, Group $group)
    {
        $notes = $group->notes()->get();
        return $notes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,User $user, Group $group)
    {
        $randomValue = mt_rand(1000, 199900) . time();
        $base64Encoded = base64_encode($randomValue);
        $hashId = Hash::make($base64Encoded);        

        $validatedData = $request->validate([
            'description' => 'required|max:100',
        ]);

        $note = new Note($validatedData);
        $note->HashedId = $hashId;
        $group->notes()->save($note);

        return $note;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user,Group $group, Note $note)
    {
        if ($note->group_id !== $group->id) {
            return response()->json(['error' => 'Note not found for the specified Group.'], 404);
        }

        return $note;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user,Group $group, Note $note)
    {
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
    public function destroy(User $user,Group $group, Note $note)
    {
        if ($note->group_id !== $group->id) {
            return response()->json(['error' => 'Note not found or has been alrady deleted.'], 404);
        }

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
   
}
