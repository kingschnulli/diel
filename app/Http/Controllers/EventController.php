<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventGroup;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
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
            ->allowedFilters(['name', 'start_date', 'end_date', $globalSearch])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'name' => 'Name'
            ])->addColumns([
                'start_date' => 'Beginn',
                'end_date' => 'Ende'
            ]);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Events/Create', [
            'interests' => Interest::all(),
            'groups' => EventGroup::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('create', new Event());

        $input = $request->request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ])->validateWithBag('createEvent');

        Event::create($input);

        return redirect()->route('events.index')->with('success', 'Neue Aufgabe wurde erstellt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('Events/Edit', [
            'event' => Event::with('interests')->find($id),
            'interests' => Interest::all(),
            'groups' => EventGroup::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        Gate::authorize('update', $event);

        $input = $request->request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ])->validateWithBag('updatedEvent');

        $event->update($input);

        $event->interests()->sync($input['interests']);

        return redirect()->route('events.index')->with('success', 'Aufgabe wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        Gate::authorize('update', $event);

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Aufgabe wurde gelöscht.');
    }
}
