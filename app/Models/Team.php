<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    protected $with = ['kids'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team',
        'num_children'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'quota',
        'quota_target',
        'quota_delta',
        'full_quota',
        'full_quota_target',
        'active_kids'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function kids()
    {
        return $this->hasMany(Kid::class);
    }

    public function getQuotaAttribute () {
        if (!$this->owner) {
            return 0;
        }

        $startDate = $this->getRequestStartDate();
        $endDate = $this->getRequestEndDate();

        $participations = Participation::whereIn('user_id', $this->allUsers()->pluck('id'));

        if ($startDate) {
            $participations = $participations->where('participation_date', '>=', $startDate);
        }
        if ($endDate) {
            $participations = $participations->where('participation_date', '<=', $endDate);
        }

        return $participations->sum('minutes') / 60;
    }

    public function getQuotaTargetAttribute () {
        if (!$this->owner) {
            return 0;
        }

        $startDate = $this->getRequestStartDate();
        $endDate = $this->getRequestEndDate();

        if ($startDate && $endDate) {
            return $this->active_kids * 2;
        }
    }

    public function getFullQuotaAttribute() {
        if (!$this->owner) {
            return 0;
        }

        $participations = Participation::whereIn('user_id', $this->allUsers()->pluck('id'));
        return $participations->sum('minutes') / 60;
    }

    public function getFullQuotaTargetAttribute() {
        if (!$this->owner) {
            return 0;
        }

        $startDate = new \DateTime('1990-01-01');
        $endDate = new \DateTime();

        $kids = $this->getActiveKidsInDateRange($startDate, $endDate);
        return $kids * 2;
    }

    public function getQuotaDeltaAttribute() {
        if (!$this->owner) {
            return 0;
        }

        if ($this->quota_target) {
            return $this->quota_target - $this->quota;
        }
    }

    private $activeKids = null;

    public function getActiveKidsAttribute() {

        if (!$this->owner) {
            return 0;
        }

        //if ($this->activeKids !== null) {
        //    return $this->activeKids;
        //}

        $startDate = $this->getRequestStartDate();
        $endDate = $this->getRequestEndDate();

        if (!$startDate || !$endDate) {
            return 0;
        }

        $value = $this->getActiveKidsInDateRange($startDate, $endDate);

        $this->activeKids = $value;

        return $value;

        /*
        if ($startDate) {
            $kids = $kids->where('entry_date', '>=', $startDate);
        }
        if ($endDate) {
            $kids = $kids->where('leave_date', '<=', $endDate);
        }

        return $kids->count();
        */
    }

    private function getActiveKidsInDateRange($startDate, $endDate) {
        $kids = $this->kids;

        $value = 0;

        foreach($kids as $kid) {

            $entryDate = new \DateTime($kid->entry_date);
            $leaveDate = new \DateTime($kid->leave_date);

            // if the kid is not active in the given time frame, skip it
            if (
                $startDate < $entryDate &&
                $endDate < $entryDate ||
                $startDate > $leaveDate &&
                $endDate > $leaveDate
            ) {
                continue;
            }

            \Log::notice("Kid {$kid->name} entry/leave: " . $entryDate->format('Y-m-d') . ' - ' . $leaveDate->format('Y-m-d') );

            $x = max($startDate, $entryDate);
            $y = min($endDate, $leaveDate);

            \Log::notice( "Relevant start/end : " . $x->format('Y-m-d') . ' - ' . $y->format('Y-m-d') );

            $diff = $y->diff($x);
            $months = (($diff->y) * 12) + ($diff->m);

            \Log::notice( "Months: " . $months );

            $value += $months;
        }

        $this->activeKids = $value;

        return $value;
    }

    private function getRequestStartDate () {
        $filter = $this->getRequestFilter();

        if ($filter) {
            $startDate = new \DateTime($filter->begin);
            $startDate->setTime(0, 0, 0);
            return $startDate;
        }
    }

    private function getRequestEndDate () {
        $filter = $this->getRequestFilter();
        if ($filter) {
            $endDate = new \DateTime($filter->end);
            $endDate->setTime(23, 59, 59);
            return $endDate;
        }
    }

    private function getRequestFilter () {
        $requestFilter = request()->get('filter');
        if ($requestFilter && isset($requestFilter['global'])) {
            $filter = json_decode($requestFilter['global']);
            if ($filter) {
                return $filter;
            }
        }
        return null;
    }

}
