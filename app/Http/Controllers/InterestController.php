<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InterestController extends Controller
{
    public function index()
    {
        Gate::authorize('list', new Interest());

        $interests = QueryBuilder::for(Interest::class)
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters(['name'])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Interests/Index', [
            'interests' => $interests,
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
        Gate::authorize('create', new Interest());

        return Inertia::render('Interests/Create');
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
        ])->validateWithBag('createInterest');

        $interest = Interest::create($input);
        Gate::authorize('create', $interest);

        return redirect()->route('interests.index')->with('success', 'Neue Interesse wurde erstellt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inertia::render('Interests/Show', [
            'interest' => Interest::with('users')->find($id)
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
        $interest = Interest::find($id);
        Gate::authorize('update', $interest);
        return Inertia::render('Interests/Edit', [
            'interest' => Interest::find($id)
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
        $interest = Interest::find($id);
        Gate::authorize('update', $interest);

        $input = $request->request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255']
        ])->validateWithBag('updatedInterest');

        $interest->update($input);

        return redirect()->route('interests.index')->with('success', 'Interesse wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interest = Interest::find($id);
        Gate::authorize('update', $interest);

        $interest->delete();

        return redirect()->route('interests.index')->with('success', 'Interesse wurde gelöscht.');
    }
}
