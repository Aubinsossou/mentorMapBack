<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class demandeMentor extends Model
{
    protected $guarded = [
    ];

    public function mentors(): BelongsToMany
    {
         return $this->belongsToMany(
        Mentor::class,
        'demande_mentors',
        'mentoree_id',
        'mentors_id'
    )->withPivot( columns: 'status')
     ->wherePivot('status', true);
    }
    public function mentoree(): BelongsTo
    {
        return $this->belongsTo(Mentoree::class, "mentoree_id");
    }
}
