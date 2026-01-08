<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticsPrefixesController;
use App\Http\Controllers\Auth\VatsimOauthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Training\SoloCertController;
use App\Http\Controllers\Training\TrainingAssignmentController;
use App\Http\Controllers\Training\TrainingTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Jobs\SyncRoster;
use App\Jobs\SyncTrainingTickets;
use App\Jobs\UpdateOnlineControllers;
use App\Mail\TrainingAssignmentUpdated;
use App\Mail\Welcome;
use App\Models\TrainingAssignment;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventPositionPresetController;
use App\Http\Controllers\EventFieldController;
use App\Http\Controllers\EventController;
use App\Mail\TrainingAssignmentCreated;
use App\EventPositionAssignmentController;
use App\Http\Controllers\EventPositionController;
use App\Livewire\EventRegistration;

Route::get('/', [HomeController::class, 'index'])->name('home');

# Oauth
Route::get('/auth/redirect', [VatsimOauthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/callback', [VatsimOauthController::class, 'callback'])->name('auth.callback');
Route::get('/auth/logout', [VatsimOauthController::class, 'logout'])->name('auth.logout');

Route::resource('users', UserController::class);

Route::resource('users', UserController::class, ['only' => ['edit', 'update']]);
Route::prefix('users/{user}')->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('users.show');
    Route::get('training-tickets', [UserController::class, 'trainingTickets'])->name('users.show.training-tickets');
    Route::get('training-assignments', [UserController::class, 'trainingAssignments'])->name('users.show.training-assignments');
    Route::get('solo-certs', [UserController::class, 'soloCerts'])->name('users.show.solo-certs');
});

Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

Route::post('training-assignment/create', [TrainingAssignmentController::class, 'create'])->middleware('auth')->name('training-assignment.create');
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('{event}', [EventController::class, 'show'])->name('show');
});
Route::post('/events/{event}/request-position', [EventRegistration::class, 'store'])->middleware('auth')->name('events.request-position.store');

Route::prefix('admin')->middleware('permission:view dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
    Route::get('users', [UserManagementController::class, 'index'])->name('manage-users.index');

    Route::middleware('permission:manage statistics prefixes')->group(function () {
        Route::resource('statistics-prefixes', StatisticsPrefixesController::class);
    });

    Route::middleware('permission:view audit logs')->group(function () {
        Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
    });

    Route::prefix('/training')->middleware('role:training')->group(function () {
        Route::resource('tickets', TrainingTicketController::class)->names('training-tickets');
        Route::resource('assignments', TrainingAssignmentController::class, ['only' => ['update', 'edit', 'index']])->names('training-assignments');
        Route::resource('solo-certs', SoloCertController::class, ['only' => ['index', 'create', 'update', 'destroy', 'store']])->names('solo-certs');
        Route::put('assignments/claim/{assignment}', [TrainingAssignmentController::class, 'claim'])->name('training-assignments.claim');
        Route::put('assignments/drop/{assignment}', [TrainingAssignmentController::class, 'drop'])->name('training-assignments.drop');
        Route::delete('assignments', [TrainingAssignmentController::class, 'destroy'])->name('training-assignments.destroy'); //id sent in payload
    });

    Route::prefix('events')->middleware('permission:manage events')->name('admin.events.')->group(function () {
        Route::resource('event-fields', EventFieldController::class)->names('event-fields');
        Route::resource('position-presets', EventPositionPresetController::class)->names('position-presets');
        Route::get('/', [EventController::class, 'manage'])->name('index');
        Route::get('create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('{event}', [EventController::class, 'update'])->name('update');
        Route::delete('{event}', [EventController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/roster', [RosterController::class, 'index'])->name('roster.index');

if (App::environment('development', 'local')) {
    Route::get('/sync', function () {
        SyncRoster::dispatch();
        UpdateOnlineControllers::dispatch();
        return 'scheduled';
    });

    Route::get('/sync-training', function () {
        SyncTrainingTickets::dispatch();
        return 'scheduled';
    });

    Route::get('/test-email', function () {
        Mail::to('chrisjm66@gmail.com')->send(new Welcome(User::find(1697197)));
        return new TrainingAssignmentCreated(TrainingAssignment::find(1));
    });
}
