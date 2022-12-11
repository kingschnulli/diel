<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ParticipationController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('description', 'LIKE', "%{$value}%");
            });
        });

        $participations = QueryBuilder::for(Participation::class);

        if (!auth()->user()->admin) {
            $participations = $participations->where('user_id', auth()->user()->id);
        }

        $participations = $participations->defaultSort('-participation_date')
            ->allowedFilters([$globalSearch])
            ->with(['event', 'user'])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Participations/Index', [
            'participations' => $participations,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'description' => 'description'
            ])->addColumns([
                'event' => 'Aufgabe',
                'description' => 'Beschreibung',
                'participation_date' => 'Datum',
                'minutes' => 'Zeitaufwand'
            ]);
            if (auth()->user()->admin) {
                $table->addColumn('user', 'Benutzer');
            }
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', new Participation());

        return Inertia::render('Participations/Create', [
            'events' => Event::where('end_date', '>', new \DateTime('-7 days'))->get(),
            'users' => auth()->user()->admin ? \App\Models\User::all() : null
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

        Validator::make($input, [
            'minutes' => ['required', 'integer', 'min:1'],
            'participation_date' => ['required', 'date'],
        ])->validateWithBag('createParticipation');

        $participation = Participation::create($input);
        Gate::authorize('create', $participation);

        return redirect()->route('participations.edit', $participation->id)->with('success', 'Elternzeit wurde erfasst.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $participation = Participation::find($id);
        Gate::authorize('update', $participation);
        return Inertia::render('Participations/Edit', [
            'participation' => $participation,
            'events' => Event::where('end_date', '>', new \DateTime('-7 days'))->get(),
            'users' => auth()->user()->admin ? \App\Models\User::all() : null
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
        $participation = Participation::find($id);
        Gate::authorize('update', $participation);

        $input = $request->request->all();

        Validator::make($input, [
            'minutes' => ['required', 'integer', 'min:1'],
            'participation_date' => ['required', 'date'],
        ])->validateWithBag('updateParticipation');

        $participation->update($input);

        return redirect()->route('participations.edit', $id)->with('success', 'Elternzeiteintrag wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $participation = Participation::find($id);
        Gate::authorize('delete', $participation);

        $participation->delete();

        return redirect()->route('participations.index')->with('success', 'Elternzeiteintrag wurde gelöscht.');
    }

}
