<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    // which posts a member has
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
