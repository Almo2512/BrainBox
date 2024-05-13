<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $emailData = [
            'name' => $user->name,
            'email' => $user->email,
        ];
    
        //Mail::send('welcome', $emailData, function ($message) use ($user) {
          //  $message->to($user->email)->subject('Registrierung erfolgreich');
       // });
    


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'userId'=>$user ->id,
        ]);
    }

    public function login(Request $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'invalid Login'], 401);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->blocked) {
            return response()->json(['message' => "This account is blocked. PLease contact support for assistande."]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'userId'=>$user ->id,
        ]);
    }

    public function updateName(Request $request, User $user)
    {


        abort_if(!$user, 404, 'User not found');

        $this->validate($request, [
            'name' => 'required',
        ]);
        $name = $request->name;

        $user->update([
            'name' => $request->name,
        ]);
        return response()->json(['message' => "Name  has been successfully updated."]);
    }
    public function updatePassword(Request $request, User $user)
    {
        abort_if(!$user, 404, 'User not found');
        if (!Hash::check($request->Oldpassword, $user->password)) {
            return response()->json(['message' => "Password ist Falsch"], 401);

        }


        $this->validate($request, [
            'password' => 'required |min:8',
        ]);

        $user->update([
            'password' => $request->password,
        ]);
        return response()->json(['message' => "password has been successfully updated."]);
    }
}