<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostFavorite extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
    ];
}
