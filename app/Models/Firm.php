<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    //

    public function lawyers()
    {
        return $this->hasMany(Lawyer::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
