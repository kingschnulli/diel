<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventGroup;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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

        $archiveFilter = AllowedFilter::callback('archive', function($query, $value) {
            if (!$value || $value == 'yes') {
                $query->where('end_date', '<', new \DateTime());
            } elseif ($value == 'no') {
                $query->where('end_date', '>=',  new \DateTime());
            }
        });

        $participatingFilter = AllowedFilter::callback('participating', function($query, $value) {
            if ($value == 'yes') {
                $query->whereHas('users', function($query) {
                    $query->where('user_id', auth()->user()->id);
                });
            }elseif($value == 'no') {
                $query->whereDoesntHave('users', function($query) {
                    $query->where('user_id', auth()->user()->id);
                });
            }
        });

        $events = QueryBuilder::for(Event::class)
            ->defaultSort('start_date')
            ->allowedSorts(['name', 'start_date', 'end_date', 'approximate_hours'])
            ->allowedFilters(['name', 'start_date', 'end_date', 'approximate_hours', $globalSearch, $archiveFilter, $participatingFilter])
            ->with('users')
            ->paginate()
            ->withQueryString();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'name' => 'Name'
            ])
                ->addFilter('archive', 'Archivierte Aufgaben', ['no' => 'Nein', 'yes' => 'Ja'])
                ->addFilter('participating', 'Aufgaben an denen ich teilnehme', ['no' => 'Nein', 'yes' => 'Ja'])
                ->addColumns([
                'missing_quota' => 'Benötigte Personen',
                'approximate_hours' => 'Zeitaufwand',
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
        Gate::authorize('create', new Event());

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
        $input = $request->request->all();

        if ($request->file('image')){
            $input['image'] = basename($request->file('image')->store('public/images/'));
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:0.5'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ])->validateWithBag('createEvent');

        $event = Event::create($input);
        Gate::authorize('create', $event);

        if (isset($input['interests']))
            $event->interests()->sync($input['interests']);

        return redirect()->route('events.edit', $event->id)->with('success', 'Neue Aufgabe wurde erstellt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inertia::render('Events/Show', [
            'event' => Event::with(['interests', 'users'])->find($id)
        ]);
    }

    /**
     * Participate in event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function participate(Request $request, $id)
    {
        $event = Event::with('users')->find($id);
        Gate::authorize('participate', $event);
        $uid = $request->user()->id;

        if ($event->users()->find($uid)) {
            $event->users()->detach($request->user()->id);
            $message = 'Sie nehmen jetzt nicht mehr an dieser Aufgabe teil';
        } else {
            $event->users()->attach($request->user()->id);
            $message = 'Sie nehmen jetzt an dieser Aufgabe teil';
        }
        return redirect()->route('events.show', $id)->with('success', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        Gate::authorize('update', $event);
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
        $oldImage = null;

        if ($request->file('image')){
            $input['image'] = basename($request->file('image')->store('public/images/'));
            $oldImage = 'public/images/' . $event->image;
        } else {
            $input['image'] = $event->image;
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:0.5'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ])->validateWithBag('updatedEvent');

        $event->update($input);

        if ($oldImage) {
            Storage::delete($oldImage);
        }
        if (isset($input['interests']))
            $event->interests()->sync($input['interests']);

        return redirect()->route('events.edit', $id)->with('success', 'Aufgabe wurde aktualisiert.');
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

        if ($event->image) {
            Storage::delete('public/images/'.$event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Aufgabe wurde gelöscht.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyImage($id)
    {
        $event = Event::find($id);
        Gate::authorize('update', $event);

        if ($event->image) {
            Storage::delete('public/images/'.$event->image);
            $event->image = null;
            $event->save();
        }

        return redirect()->route('events.edit', $id)->with('success', 'Bild wurde entfernt.');
    }
}
