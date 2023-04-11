<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FamilyController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            // we will just grab that in the Teams model attribute
            // not nice, but works
        });

        $teams = QueryBuilder::for(Team::class)
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters([$globalSearch])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
        ])->table(function (InertiaTable $table) {
            $table
                ->addColumns([
                    'name' => 'Familie',
                    'quota_target' => 'Stunden Plan',
                    'quota' => 'Stunden ist',
                    'quota_delta' => 'Delta',
                    'active_kids' => 'Rel. Monate. ges.'
                ]);
        });
    }

}
