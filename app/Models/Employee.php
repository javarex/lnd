<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $fillable = [
    //     'first_name',
    //     'middle_name',
    //     'last_name',
    //     'school_id',
    //     'employee_type',
    // ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function twgTrainings(): HasMany
    {
        return $this->hasMany(TwgTraining::class);
    }
}
