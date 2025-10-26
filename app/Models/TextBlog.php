<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextBlog extends Model
{
    protected $fillable = ['title', 'content'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
