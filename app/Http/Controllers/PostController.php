<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('user.isInfluencer')->orderBy('created_at', 'desc')->get();


        return response()->json([
            'status' => 200,
            'posts' => $posts
        ]);
    }
}
