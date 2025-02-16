<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasDateFormat;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarOfTraining extends Model implements Eventable
{
    use HasDateFormat;

    protected $guarded = [];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function toEvent(): Event|array {
        return Event::make($this)
            ->action('edit')
            ->title($this->training?->training_name)
            ->start($this->start_date)
            ->end($this->end_date)
            // ->end(dd($this->end_date))
            ->extendedProp('participants', $this->participants()->count())
            ->extendedProp('with_accreditation', $this->accreditation_number !== null)
            ->styles([
                'color: black' => true,
                'background-color' => $this->accreditation_number ? '#b37820' : '#ffff00', // Directly applies the background color
                'font-size: 12px'
            ]);
    }

    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->longDate($this->start_date)
                    .(!$this->isSameDate()
                        ? " - {$this->longDate($this->end_date)}"
                        : null)
        );
    }

    protected function isSameDate(): bool
    {
        return $this->start_date === $this->end_date;
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
