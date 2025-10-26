<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageBlogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TextBlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::resource('text-blogs', TextBlogController::class);
Route::resource('image-blogs', ImageBlogController::class);

Route::resource('comments', CommentController::class);
Route::post('get-comments', [CommentController::class, 'getComments'])->name('get.comments');
Route::get('/comments/{id}/post', [CommentController::class, 'getCommentPost'])->name('comments.post');

Route::get('get-blogs', [IndexController::class, 'getBlogs'])->name('get.blogs');

Route::delete('/blogs/{id}', [IndexController::class, 'destroy'])->name('blogs.destroy');
Route::get('/blogs/{id}/comments', [IndexController::class, 'getBlogComments'])->name('blogs.comments');
Route::post('/blogs/{id}/comments', [IndexController::class, 'addBlogComment'])->name('blogs.comments.add');
