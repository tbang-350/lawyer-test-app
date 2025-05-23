<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lawyer extends Model
{
    //

    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class);
    }
}
