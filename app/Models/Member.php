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

    // get formatted datetime string for mobile_verified_at
    public function getdaysAgoJoinedAttribute()
    {
        if ($this->created_at) {
            return $this->created_at->diffForHumans();
        } else {
            return null;
        }
    }
}
