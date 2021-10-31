<?php

namespace App\Http\Controllers;

use App\Models\EventGroup;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventGroupController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('description', 'LIKE', "%{$value}%");
            });
        });

        $eventGroups = QueryBuilder::for(EventGroup::class)
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters(['name', $globalSearch])
            ->paginate()
            ->withQueryString();

        return Inertia::render('EventGroups/Index', [
            'eventGroups' => $eventGroups,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'name' => 'Name'
            ])->addColumns([
                'description' => 'Beschreibung'
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
        Gate::authorize('create', new EventGroup());

        return Inertia::render('EventGroups/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createEventGroup');

        $eventGroup = EventGroup::create($input);
        Gate::authorize('create', $eventGroup);

        return redirect()->route('eventgroups.index')->with('success', 'Neue Aufgabengruppe wurde erstellt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inertia::render('EventGroups/Show', [
            'eventGroup' => EventGroup::with('users')->find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eventGroup = EventGroup::find($id);
        Gate::authorize('update', $eventGroup);
        return Inertia::render('EventGroups/Edit', [
            'eventGroup' => EventGroup::find($id)
        ]);
    }

    /**
     * Participate in event group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function participate(Request $request, $id)
    {
        $eventGroup = EventGroup::with('users')->find($id);
        Gate::authorize('participate', $eventGroup);
        $uid = $request->user()->id;

        if ($eventGroup->users()->find($uid)) {
            $eventGroup->users()->detach($request->user()->id);
            $message = 'Sie haben die Gruppe verlassen';
        } else {
            $eventGroup->users()->attach($request->user()->id);
            $message = 'Sie sind jetzt Teilnehmer dieser Gruppe';
        }
        return redirect()->route('eventgroups.show', $id)->with('success', $message);
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
        $eventGroup = EventGroup::find($id);
        Gate::authorize('update', $eventGroup);

        $input = $request->request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255']
        ])->validateWithBag('updatedEventGroup');

        $eventGroup->update($input);

        return redirect()->route('eventgroups.index')->with('success', 'Aufgabengruppe wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eventGroup = EventGroup::find($id);
        Gate::authorize('update', $eventGroup);

        $eventGroup->delete();

        return redirect()->route('eventgroups.index')->with('success', 'Aufgabengruppe wurde gelöscht.');
    }
}
