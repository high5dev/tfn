<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    // we don't need timestamps in this model as we already have start & finish times
    public $timestamps = false;

    /**
     * get user the scan belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
