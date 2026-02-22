<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\InvitationController;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('reports', function(){
    if(auth()->user()->role === 'coordinator'){
        return response()->json(['message' => 'Coordinator can access the report page'], 200);
    }
    else{
        return response()->json(['message' => 'You are not authorized to access this page'], 403);
    }
})->middleware('auth');

Route::controller(InvitationController::class)->prefix('invitations')->as('invitations.')->middleware('auth')->group(function () {
    Route::post('/invite', 'invite')->name('store');
    Route::post('/accept/{invitation:code}', 'acceptInvite')->name('accept');
});

require __DIR__.'/settings.php';
