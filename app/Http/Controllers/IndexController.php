<?php

namespace App\Http\Controllers;

use App\Models\ImageBlog;
use App\Models\TextBlog;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function getBlogs(){
        
        $textBlogs = TextBlog::select('id', 'title', 'content', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'text';
                return $item;
            });

        $imageBlogs = ImageBlog::select('id', 'title', 'image_path', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'image';
                return $item;
            });

        $blogs = $textBlogs->merge($imageBlogs);
        $sortedBlogs = $blogs->sortByDesc('created_at')->take(10)->values();

        return response()->json($sortedBlogs);
    }


}
