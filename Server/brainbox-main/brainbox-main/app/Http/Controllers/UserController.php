<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = $request->user();
        abort_if(!$user || !$user->hasRole('admin'), 403, "Admin not found");
        $users = User::all();
        return $users;
    }


    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return $user;
    }


    public function signIn(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        abort_if(!$user, 404, 'User not found');
        if ( !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => "Password ist Falsch"], 401); ;
        }
        if ($user->blocked) {
            return "This account is blocked. Please contact support for assistance.";
        }

        $accessToken = $user->createToken($user->id)->plainTextToken;
        return response()->json(['token' => $accessToken], 200); ;


    }


}
