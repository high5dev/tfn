<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'started',
        'startid',
        'startts'
    ];

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
