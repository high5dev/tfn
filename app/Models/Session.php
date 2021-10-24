<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $casts = [
        'id' => 'string',
        ];
    /**
     * get user the session belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * return last_activity in human readable form
     */
    public function getHowLongAttribute()
    {
        return Carbon::createFromTimeStamp($this->last_activity)->diffForHumans();
    }
}
