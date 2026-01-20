<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Langages_mentor extends Model
{
    protected $guarded = [];
    protected $table = 'langages_mentor';

     public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class, 'mentors_id');
    }
    public function langage(): BelongsTo
    {
        return $this->belongsTo(Mentor::class, 'mentors_id');
    }
}
