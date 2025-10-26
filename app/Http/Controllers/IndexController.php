<?php

namespace App\Http\Controllers;

use App\Models\ImageBlog;
use App\Models\TextBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function destroy(Request $request, $id)
    {
        $type = $request->input('type'); // text or image

        if ($type === 'text') {
            $blog = TextBlog::findOrFail($id);
            $blog->delete();
        } elseif ($type === 'image') {
            $blog = ImageBlog::findOrFail($id);

            // Delete the image file from storage
            if ($blog->image_path && Storage::disk('public')->exists($blog->image_path)) {
                Storage::disk('public')->delete($blog->image_path);
            }

            $blog->delete();
        } else {
            return response()->json(['message' => 'Invalid blog type'], 400);
        }

        return response()->json(['message' => 'Blog deleted successfully']);
    }

}
