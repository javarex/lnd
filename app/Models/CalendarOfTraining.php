<?php

namespace App\Models;

use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarOfTraining extends Model implements Eventable
{
    protected $guarded = [];

    public function toEvent(): Event|array {
        return Event::make($this)
            ->action('edit')
            ->title($this->training?->training_name)
            ->start($this->start_date)
            ->end($this->end_date)
            ->extendedProp('participants', $this->participants()->count())
            ->styles([
                'color: black' => true,
                'background-color' => '#ffff00', // Directly applies the background color
                'font-size: 12px'  
            ]);
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function scopeDateBetween(Builder $query, array $fetchinfo)
    {
        $query->whereBetween('start_date', [$fetchinfo['start'], $fetchinfo['end']]);
    }
}
