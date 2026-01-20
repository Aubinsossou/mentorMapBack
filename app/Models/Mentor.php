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



class Mentor extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded = [

    ];
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];
    public function demande_mentors(): HasMany
    {
        return $this->hasMany(demandeMentor::class, "mentors_id");
    }

    public function mentorees(): BelongsToMany
    {
        return $this->belongsToMany(demandeMentor::class, "mentoree_id");
    }
    public function domaines(): BelongsToMany
    {
        return $this->belongsToMany(Domaine::class, 'domaine_mentor', 'mentor_id', 'domaine_id');
    }
     public function langages(): BelongsToMany
    {
        return $this->belongsToMany(Langages::class, 'langages_mentor', 'mentors_id', 'langages_id');
    }

}
