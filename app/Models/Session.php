<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * get user the session belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
