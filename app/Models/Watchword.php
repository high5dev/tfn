<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchword extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'theword',
    ];
}
