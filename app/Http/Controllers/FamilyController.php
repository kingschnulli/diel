<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Carbon\Carbon;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FamilyController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%");
            });
        });

        $monthFilter = AllowedFilter::callback('month', function($query, $value) {
            // we will just grab that in the Teams model attribute
            // not nice, but works
        });

        $teams = QueryBuilder::for(Team::class)
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters(['name', $monthFilter, $globalSearch])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
        ])->table(function (InertiaTable $table) {
            $table
                ->addFilter('month', 'Monat', $this->getMonths())
                ->addColumns([
                    'name' => 'Familie',
                    'quota_month_target' => 'Stunden Plan',
                    'quota_month' => 'Stunden ist',
                    'quota_year_target' => 'Stunden Jahr Plan',
                    'quota_year' => 'Stunden Jahr ist'
                ]);
        });
    }
    private function getMonths()
    {
        return [
            '1' => 'Januar',
            '2' => 'Februar',
            '3' => 'März',
            '4' => 'April',
            '5' => 'Mai',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'August',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Dezember'
        ];
    }
}
