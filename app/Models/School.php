<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $guarded = [];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'brgyCode');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
