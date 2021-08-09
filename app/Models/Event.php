<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }

    public function eventGroups()
    {
        return $this->belongsTo(EventGroup::class);
    }
}
