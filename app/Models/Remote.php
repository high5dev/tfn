<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payload',
        'last_activity',
    ];
}
