<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CommentController extends Controller
{
    public function index(){
        return view('comments');
    }

    public function getComments(Request $request){
        if($request->ajax()){
            $comments = Comment::select('id', 'body');

            return DataTables::of($comments)
                ->addIndexColumn()
                ->editColumn('body', function ($comment) {
                    return '<a href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#commentPostModal" data-comment-id="' . $comment->id . '">' . $comment->body . '</a>';
                })
                ->rawColumns(['body'])
                ->make(true);
        }
    }

    public function getCommentPost($id)
    {
        $comment = Comment::with('commentable')->findOrFail($id);

        $post = $comment->commentable;
        
        $type = class_basename($post);

        return response()->json([
            'type' => $type,
            'comment' => $comment,
            'post' => $post,
        ]);
    }

}
