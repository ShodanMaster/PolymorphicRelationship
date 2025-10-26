<?php

use App\Http\Controllers\ImageBlogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TextBlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::resource('text-blogs', TextBlogController::class);
Route::resource('image-blogs', ImageBlogController::class);

Route::get('get-blogs', [IndexController::class, 'getBlogs'])->name('get.blogs');

Route::delete('/blogs/{id}', [IndexController::class, 'destroy'])->name('blogs.destroy');
