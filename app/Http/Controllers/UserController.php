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

        Auth::login($user);

        $user->tokens()->delete();
        // Create new token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ], 200);
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
            
            $user = Auth::user();
            $user->tokens()->delete();
            // Create new token
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        }
        else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Credentials' 
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function addInfluencer(Request $request)
    {
        $creator = User::where('id', $request->creator_id)->first();
        $request->user()->influencers()->attach($request->creator_id);
        
        return response()->json([
            'status' => 200,
            'message' => 'You are now following '.$creator->name 
        ]);
    }
    public function removeInfluencer(Request $request)
    {
        $creator = User::where('id', $request->creator_id)->first();
        $request->user()->influencers()->detach($request->creator_id);
        
        return response()->json([
            'status' => 200,
            'message' => 'You are now unfollowing '.$creator->name 
        ]);
    }
}
