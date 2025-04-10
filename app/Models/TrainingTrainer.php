<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingTrainer extends Model
{
    protected $table = 'training_trainer';

    protected $guarded = [];
    
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function calendarOfTraining(): BelongsTo
    {
        return $this->belongsTo(CalendarOfTraining::class);
    }
}
