<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageBlog extends Model
{
    protected $fillable = ['title', 'image_path'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
