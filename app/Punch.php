<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Punch extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPunchedIn()
    {
        return $this->in_at && !$this->out_at;
    }
}
