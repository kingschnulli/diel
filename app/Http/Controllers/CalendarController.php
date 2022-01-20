<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CalendarController extends Controller
{
    public function index()
    {
        //@TODO: add monthly filter, we are overfetching
        $events = Event::with('users')->get();

        return Inertia::render('Calendar/Index', [
            'events' => $events,
        ]);
    }

}
