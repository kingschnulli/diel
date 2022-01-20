<?php

namespace App\Http\Controllers;

use App\Models\Event;
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

        return Inertia::render('Dashboard', [
            'events' => $events,
        ]);
    }
}
