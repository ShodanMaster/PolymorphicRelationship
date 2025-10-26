<?php

namespace App\Http\Controllers;

use App\Models\TextBlog;
use Illuminate\Http\Request;

class TextBlogController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        TextBlog::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return response()->json(['message' => 'Text blog post created successfully!'], 201);
    }
}
