<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function lawyers()
    {
        return $this->belongsToMany(Lawyer::class);
    }
}
