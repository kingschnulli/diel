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
        'quota_delta'
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

    public function getQuotaAttribute () {
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
        $startDate = $this->getRequestStartDate();
        $endDate = $this->getRequestEndDate();

        if ($startDate && $endDate) {
            $diff = $endDate->diff($startDate);
            $months = (($diff->y) * 12) + ($diff->m);
            return $months * $this->num_children * 2;
        }
    }

    public function getQuotaDeltaAttribute() {
        if ($this->quota_target) {
            return $this->quota_target - $this->quota;
        }
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
