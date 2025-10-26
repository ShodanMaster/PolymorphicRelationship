<?php

namespace App\Http\Controllers;

use App\Models\ImageBlog;
use Illuminate\Http\Request;

class ImageBlogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image',
        ]);

        $imagePath = $request->file('image')->store('images', 'public');

        ImageBlog::create([
            'title' => $request->input('title'),
            'image_path' => $imagePath,
        ]);

        return response()->json(['message' => 'Image blog post created successfully!'], 201);
    }
}
