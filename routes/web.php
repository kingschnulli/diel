<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Reverse proxy fix
$app_url = config("app.url");
if (app()->environment('prod') && !empty($app_url)) {
    URL::forceRootUrl($app_url);
    $schema = explode(':', $app_url)[0];
    URL::forceScheme($schema);
}

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->name('dashboard');

Route::group(['middleware' => ['auth', 'verified']], function () {
    // User & Profile...
    Route::get('/users/index', [\App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');

    Route::get('/families/index', [\App\Http\Controllers\FamilyController::class, 'index'])
        ->name('families.index');

    // Calendar
    Route::resource('calendar', \App\Http\Controllers\CalendarController::class);

    // Events
    Route::post('/events/{id}/participate', [\App\Http\Controllers\EventController::class, 'participate'])->name('events.participate');
    Route::delete('/events/{id}/deleteimage', [\App\Http\Controllers\EventController::class, 'destroyImage'])->name('events.destroyimage');
    Route::resource('events', \App\Http\Controllers\EventController::class);

    // EventGroups
    Route::post('/eventgroups/{id}/participate', [\App\Http\Controllers\EventGroupController::class, 'participate'])->name('eventgroups.participate');
    Route::resource('eventgroups', \App\Http\Controllers\EventGroupController::class);

    // EventGroups
    Route::resource('interests', \App\Http\Controllers\InterestController::class);

    //Participations
    Route::resource('participations', \App\Http\Controllers\ParticipationController::class);

    //Kids
    Route::resource('kids', \App\Http\Controllers\KidsController::class);

});
