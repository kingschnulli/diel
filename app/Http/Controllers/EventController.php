<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('description', 'LIKE', "%{$value}%");
            });
        });

        $events = QueryBuilder::for(Event::class)
            ->defaultSort('start_date')
            ->allowedSorts(['name', 'start_date', 'end_date'])
            ->allowedFilters(['name', 'start_date', 'end_date', 'description', $globalSearch])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'name' => 'Name',
                'description' => 'Beschreibung'
            ])->addColumns([
                'start_date' => 'Beginn',
                'end_date' => 'Ende',
                'description' => 'Beschreibung'
            ]);
        });
    }
}
