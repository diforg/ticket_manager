<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

$dashboardRouteFor = fn (?string $role): string => match ($role) {
    'attendant' => 'attendant.dashboard',
    default => 'client.dashboard',
};

Route::get('/', function (Request $request) use ($dashboardRouteFor) {
    if ($request->user() !== null) {
        return redirect()->route($dashboardRouteFor($request->user()->role));
    }

    return Inertia::render('Welcome');
});

Route::middleware(['auth', 'verified'])->group(function () use ($dashboardRouteFor) {
    Route::get('/dashboard', function (Request $request) use ($dashboardRouteFor) {
        return redirect()->route($dashboardRouteFor($request->user()->role));
    })->name('dashboard');

    Route::get('/dashboard/client', [TicketController::class, 'clientIndex'])
        ->middleware('role:client')
        ->name('client.dashboard');

    Route::get('/dashboard/attendant', [TicketController::class, 'attendantIndex'])
        ->middleware('role:attendant')
        ->name('attendant.dashboard');

    Route::get('/tickets/create', [TicketController::class, 'create'])
        ->middleware('role:client')
        ->name('tickets.create');

    Route::post('/tickets', [TicketController::class, 'store'])
        ->middleware('role:client')
        ->name('tickets.store');

    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show');

    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->middleware('role:attendant')
        ->name('tickets.status.update');

    Route::post('/tickets/{ticket}/messages', [TicketController::class, 'storeMessage'])
        ->name('tickets.messages.store');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';