<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Interest;
use App\Models\Participation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('email', 'LIKE', "%{$value}%");
            });
        });

        $users = QueryBuilder::for(User::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'email'])
            ->allowedFilters(['name', 'email', $globalSearch])
            ->with('currentTeam')
            ->paginate()
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'name' => 'Name',
                'email' => 'Email address',
            ])->addColumns([
                'email' => 'Email address',
                'current_team' => 'Familie'
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        Gate::authorize('update', $user);

        return Inertia::render('Users/Edit', [
            'edit_user' => User::with('interests')->find($id),
            'all_interests' => Interest::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', new User());

        return Inertia::render('Users/Create', [
            'all_interests' => Interest::all(),
            'all_teams' => Team::all(),
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
        $input['password'] = bcrypt('password');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (User::whereEmail($value)->count() > 0) {
                        $fail('Email Adresse ist bereits vergeben');
                    }
                },
            ]
        ])->validateWithBag('createUser');

        if ($input['team']) {
            $input['current_team_id'] = $input['team'];
        }

        $user = User::create($input);
        Gate::authorize('create', $user);

        if (!$input['team']) {
            $user->switchTeam($user->ownedTeams()->create([
                'name' => 'Familie ' . $input['name'],
                'personal_team' => true,
            ]));
        }

        return redirect()->route('users.index')->with('success', 'Neuer Benutzer wurde erstellt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        Gate::authorize('update', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Benutzer wurde gelöscht.');
    }
}
