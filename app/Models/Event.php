<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['is_participating', 'image_url', 'missing_quota'];

    public function users()
    {
        return $this->belongsToMany(User::class)->using(EventUser::class)->withTimestamps()->withPivot('created_at');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }

    public function eventGroup()
    {
        return $this->belongsTo(EventGroup::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function getIsParticipatingAttribute () {
        if (isset($this->relations['users'])) {
            $user = Auth::user();
            return $this->relations['users']->find($user->id) ? 1 : 0;
        } else {
            return -1;
        }
    }

    public function getMissingQuotaAttribute () {
        if (isset($this->relations['users'])) {
            return max(0, $this->quota - count($this->users));
        }else{
            return $this->quota;
        }

    }

    /**
     * Get the URL to the event image
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image
            ? Storage::disk('public')->url('images/'.$this->image)
            : 'http://via.placeholder.com/500x300';
    }
}
