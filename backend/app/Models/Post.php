<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    # One to Many INVERSE
    # Post belongs to user
    # To get the owner of the post
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
