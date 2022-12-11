<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\Participation;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {

        $events = Event::whereDate('start_date', '>=', date('Y-m-d'))
                    ->with('interests')
                    ->with('users')
                    ->orderBy('start_date', 'desc')
                    ->take(3)
                    ->get();

        $eventUsers = EventUser::with('user')->with('event')->orderBy('created_at', 'desc')->take(10)->get();

        return Inertia::render('Dashboard', [
            'events' => $events,
            'eventUsers' => $eventUsers
        ]);
    }
}
