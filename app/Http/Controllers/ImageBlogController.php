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

        $imageBlog = new ImageBlog();
        $imageBlog->title = $request->input('title');
        $imageBlog->image_path = $imagePath;
        $imageBlog->save();

        return response()->json(['message' => 'Image blog post created successfully!'], 201);
    }
}
