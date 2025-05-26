<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_name',
    ];

    public function calendarOfTraining(): HasMany
    {
        return $this->hasMany(CalendarOfTraining::class);
    }
}
