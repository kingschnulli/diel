<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['is_participating'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }

    public function eventGroup()
    {
        return $this->belongsTo(EventGroup::class);
    }

    public function getIsParticipatingAttribute () {
        if (isset($this->relations['users'])) {
            $user = Auth::user();
            return $this->relations['users']->find($user->id) ? 1 : 0;
        } else {
            return -1;
        }
    }
}
