<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    //trait
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    // relasi inverse ke model subject
    public function subject() : BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    //relasi one to many dengan answer
    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
