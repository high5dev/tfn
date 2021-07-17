<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logg extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    /**
     * get user the log belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
