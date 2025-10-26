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

        $textBlog = new TextBlog();
        $textBlog->title = $request->input('title');
        $textBlog->content = $request->input('content');
        $textBlog->save();

        return response()->json(['message' => 'Text blog post created successfully!'], 201);
    }

    public function delete($id){
        $textBlog = TextBlog::find($id);
        if ($textBlog) {
            $textBlog->delete();
            return response()->json(['message' => 'Text blog post deleted successfully!'], 200);
        } else {
            return response()->json(['message' => 'Text blog post not found!'], 404);
        }
    }
}
