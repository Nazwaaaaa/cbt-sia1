<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    //trait
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    // relasi one to many dengan tabel question
    public function questions() : HasMany
    {
        return $this->hasMany(Question::class);
    }

    // relasi many to many dengan model exam
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class);
    }
}