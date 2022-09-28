<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
