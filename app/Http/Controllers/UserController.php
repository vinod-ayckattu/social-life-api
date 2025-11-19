<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|max:15',
            'gender' => 'required'
        ]);

       // $photo = $request->file('photo')->store('/photos');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        //    'photo' => $photo,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'status' => 200,
            'user' => $user 
        ]);
    }

    public function userProfile(Request $request)
    {
        $user = User::where('id', $request->id)->with('posts')->first();

        return response()->json([
            'status' => 200,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            
            $user = auth()->user();

           // $token = $user->
        }
    }
}
