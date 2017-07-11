<?php

namespace App;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Clock extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['in_at', 'out_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Return if the authenticated user is clocked in.
     * 
     * @return boolean
     */
    public static function status()
    {
        $user = auth()->user();

        return (bool) self::userClockedIn($user)->count();
    }

    /**
     * Clocks a user in or out based on their status.
     */
    public static function toggle()
    {
        $user = auth()->user();

        if (self::userClockedIn($user)->count()) {
            self::clockOut($user);
        }
        else {
            self::clockIn($user);
        }
    }

    /**
     * Clocks in a user.
     * 
     * @param  App\User   $user
     */
    protected static function clockIn(User $user)
    {
        self::create([
            'user_id' => $user->id,
            'in_at' => Carbon::now()
        ]);
    }

    /**
     * Clocks out a user.
     * 
     * @param  App\User   $user
     */
    protected static function clockOut(User $user)
    {
        $records = self::userClockedIn($user)->get();

        $records->each(function($record) {
            $record->update(['out_at' => Carbon::now()]);
        });
    }

    /**
     * Return records where the user is clocked in.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param  App\User $user
     * @return integer
     */
    public function scopeUserClockedIn($query, $user)
    {
        return $query->whereNull('out_at')
            ->whereUserId($user->id);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
