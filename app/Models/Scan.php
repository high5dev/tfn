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

    // get formatted datetime string for startts
    public function getStarttsAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->format('Y-m-d H:m');
    }

    // get formatted datetime string for stopts
    public function getStoptsAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->format('Y-m-d H:m');
    }

    /**
     * get user the scan belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
