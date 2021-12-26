<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // which reports a user has
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

}
