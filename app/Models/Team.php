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
        'quota_month',
        'quota_month_target',
        'quota_year',
        'quota_year_target',
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

    public function getQuotaMonthAttribute () {
        $requestFilter = request()->get('filter');
        if ($requestFilter && isset($requestFilter['month'])) {
            $month = \DateTime::createFromFormat('!m', $requestFilter['month'])->format('F');
        } else {
            $month = date('F');
        }
        $firstDayOfMonth = new \DateTime('first day of ' . $month);
        $firstDayOfMonth->setTime(0, 0, 0);
        $lastDayOfMonth = new \DateTime('last day of ' . $month);
        $lastDayOfMonth->setTime('23', '59', '59');
        $participations = Participation::whereIn('user_id', $this->allUsers()->pluck('id'))->whereBetween('participation_date', [$firstDayOfMonth, $lastDayOfMonth]);
        return $participations->sum('minutes') / 60;
    }

    public function getQuotaMonthTargetAttribute () {
        return 2 * $this->num_children;
    }

    public function getQuotaYearAttribute () {
        $firstDayOfYear = new \DateTime('first day of January ' . date('Y'));
        $firstDayOfYear->setTime(0, 0, 0);
        $lastDayOfYear = new \DateTime('last day of December' . date('Y'));
        $lastDayOfYear->setTime('23', '59', '59');

        $participations = Participation::whereIn('user_id', $this->allUsers()->pluck('id'))->whereBetween('participation_date', [$firstDayOfYear, $lastDayOfYear]);
        return $participations->sum('minutes') / 60;
    }

    public function getQuotaYearTargetAttribute () {
        return 24  * $this->num_children;
    }
}
