<?php

namespace App\Models;

use Carbon\Carbon;
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

    /**
     * return last_Activity in human readable form
     */
    public function last()
    {
        return Carbon::now();
        //return Carbon::createFromTimeStamp($this->last_activity)->diffForHumans();
    }
}
