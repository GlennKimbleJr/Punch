<?php

namespace App;

use App\Punch;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
        'password', 'remember_token',
    ];

    public function punches()
    {
        return $this->hasMany(Punch::class);
    }

    public function mostRecentPunch()
    {
        return $this->hasOne(Punch::class)->orderByDesc('updated_at');
    }

    public function isPunchedIn()
    {
        if ($this->punches->isEmpty()) {
            return false;
        }

        return $this->mostRecentPunch->isPunchedIn();
    }
}
