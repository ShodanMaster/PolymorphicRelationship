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

    public function getBlogs(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = 10;

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

        $blogs = $textBlogs->merge($imageBlogs)
            ->sortByDesc('created_at')
            ->values();

        $paginatedBlogs = $blogs->slice($offset, $limit)->values();

        return response()->json($paginatedBlogs);
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

    public function getBlogComments(Request $request, $id)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $type = $request->input('type');

        if ($type === 'text') {
            $blog = TextBlog::findOrFail($id);
        } elseif ($type === 'image') {
            $blog = ImageBlog::findOrFail($id);
        } else {
            return response()->json(['error' => 'Invalid blog type'], 400);
        }

        $comments = $blog->comments()
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->pluck('body');

        return response()->json($comments);
    }

    public function addBlogComment(Request $request, $id){
        $type = $request->input('type');
        $text = $request->input('text');

        if ($type === 'text') {
            $blog = TextBlog::findOrFail($id);
        } elseif ($type === 'image') {
            $blog = ImageBlog::findOrFail($id);
        } else {
            return response()->json(['error' => 'Invalid blog type'], 400);
        }

        $comment = $blog->comments()->create([
            'body' => $text,
        ]);

        return response()->json(["body" => $comment->body], 201);
    }
}
