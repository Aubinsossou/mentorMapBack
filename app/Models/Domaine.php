<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Domaine extends Model
{
    protected $guarded = [];

    public function mentors(): BelongsToMany
    {
        return $this->belongsToMany(Mentor::class, 'domaine_mentor', 'domaine_id', 'mentor_id');
    }

}
