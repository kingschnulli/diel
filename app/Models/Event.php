<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
}
