<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'member_id',
        'title',
        'justification',
        'found',
        'warnings',
        'warning_emails',
        'body'
    ];

    /**
     * get user the report belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
