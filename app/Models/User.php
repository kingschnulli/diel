<?php

namespace App\Models;

use App\Traits\AuthorizedAttributes;
use Database\Factories\EventGroupFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use AuthorizedAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class)->using(EventUser::class)->withTimestamps()->withPivot('created_at');
    }

    public function eventGroups()
    {
        return $this->belongsToMany(EventGroup::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

}
