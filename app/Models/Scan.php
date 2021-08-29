<?php

namespace App\Models;

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
    public function getStarttsAttribute()
    {
        if ($this->startts) {
            return Carbon::createFromFormat('Y-m-d H:i', $this->startts, 'UTC');
        } else {
            return null;
        }
    }

    // get formatted datetime string for stopts
    public function getStoptsAttribute()
    {
        if ($this->stopts) {
            return Carbon::createFromFormat('Y-m-d H:i', $this->stopts, 'UTC');
        } else {
            return null;
        }
    }

    /**
     * get user the scan belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
