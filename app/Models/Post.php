<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // we don't need timestamps in this model as we already have start & finish times
    public $timestamps = false;

    /**
     * get member the post belongs to
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
