<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

    // Events
    Route::post('/events/{id}/participate', [\App\Http\Controllers\EventController::class, 'participate'])->name('events.participate');
    Route::delete('/events/{id}/deleteimage', [\App\Http\Controllers\EventController::class, 'destroyImage'])->name('events.destroyimage');
    Route::resource('events', \App\Http\Controllers\EventController::class);

    // EventGroups
    Route::post('/eventgroups/{id}/participate', [\App\Http\Controllers\EventGroupController::class, 'participate'])->name('eventgroups.participate');
    Route::resource('eventgroups', \App\Http\Controllers\EventGroupController::class);

    // EventGroups
    Route::resource('interests', \App\Http\Controllers\InterestController::class);

});
