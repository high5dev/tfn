<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'firstip',
        'status',
        'zapped',
        'joined_recently',
        'created_at',
        'updated_at'
    ];

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
