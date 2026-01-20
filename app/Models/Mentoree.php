<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Mentoree extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded = [

    ];
     protected $hidden = [
        'password',
        'remember_token',
        'updated_at'
    ];

    public function mentors(): BelongsToMany
    {
         return $this->belongsToMany(Mentor::class, 'demande_mentors', 'mentoree_id', 'mentors_id')
                ->withPivot('status')
                ->wherePivot('status', "true");
    }

    public function demande_mentors(): BelongsToMany
    {
        return $this->belongsToMany(demandeMentor::class, "mentoree_id");
    }
}
