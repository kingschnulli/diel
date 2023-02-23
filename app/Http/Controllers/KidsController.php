<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\Kid;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KidsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', new Kid());
        $teamId = request()->get('team');

        return Inertia::render('Kids/Create', [
            'team' => Team::find($teamId),
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
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createKid');

        $kid = Kid::create($input);
        Gate::authorize('create', $kid);

        return redirect()->route('teams.show', ['team' => $kid->team_id])->with('success', 'Kind wurde hinzugefügt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kid = Kid::find($id);
        Gate::authorize('delete', $kid);

        $kid->delete();

        return redirect()->route('teams.show', ['team' => $kid->team_id])->with('success', 'Kind wurde entfernt.');
    }

}
