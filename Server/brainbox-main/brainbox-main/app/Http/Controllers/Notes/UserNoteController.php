<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Note;



class UserNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        abort_if(!$user,404,'User not found');

        $notes = $user->notes()->get();
        return $notes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        abort_if(!$user,401,'User not found');

        //hier lösche ich die "/" von HashId:
        $randomValue = mt_rand(1000, 199900) . time();
        $base64Encoded = base64_encode($randomValue);
        $hashId = Hash::make($base64Encoded);   

        $validatedData = $request->validate([
            'description' => 'required|max:100',
        ]);

        $note = new Note($validatedData);
        $note->HashedId = $hashId;
        $user->notes()->save($note);

        return $note;
    }
    public function show(User $user, Note $note)
    {

        abort_if($note->user_id !== $user->id,404,'Note not found for the specified user.');
        return $note;
    }

    public function update(Request $request, User $user, Note $note)
    {
        

        abort_if(!$user,404,'User not found');
        abort_if($note->user_id !== $user->id,404,'Note not found for the specified user.');

        $this->validate($request, [
            'description' => 'required|max:100',
        ]);
      

        $note->update([
            'description' => $note->request->description,
        ]);
        return $note;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Note $note)
    {
       // $note = $user->notes()->find($note);

        abort_if(!$user,404,'User not found');
        abort_if(!$note,404, 'Note not found for the specified user.');

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
    

   
}
