<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwgTraining extends Model
{

    protected $table = 'twg_training';
    
    public function empoloyee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function calendarOfTraining(): BelongsTo
    {
        return $this->belongsTo(CalendarOfTraining::class);
    }
}
