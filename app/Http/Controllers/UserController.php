<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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
        return Inertia::render('Users/Edit', [
            'edit_user' => User::with('interests')->find($id),
            'all_interests' => Interest::all()
        ]);
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
