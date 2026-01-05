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
use App\Http\Controllers\ManageEventController;
use App\Http\Controllers\VisitFacilityController;
use App\Mail\TrainingAssignmentCreated;

# Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

# Roster
Route::get('/roster', [RosterController::class, 'index'])->name('roster.index');

# Visit
Route::get('/visit', [VisitFacilityController::class, 'index'])->name('visit.index');
Route::get('/visit/create', [VisitFacilityController::class, 'create'])->middleware('auth')->name('visit.create');
Route::post('/visit', [VisitFacilityController::class, 'store'])->middleware('auth')->name('visit.store');

# Oauth
Route::get('/auth/redirect', [VatsimOauthController::class, 'redirect'])->name('auth.redirect');
Route::get('/login', function() {
    return redirect('auth.redirect', 301);
})->name('login');
Route::get('/auth/callback', [VatsimOauthController::class, 'callback'])->name('auth.callback');
Route::get('/auth/logout', [VatsimOauthController::class, 'logout'])->name('auth.logout');

# Users
Route::resource('users', UserController::class, ['only' => ['show', 'edit', 'update']]);
Route::prefix('users/{user}')->group(function() {
    Route::get('/', [UserController::class, 'show'])->name('users.show');
    Route::get('training-tickets', [UserController::class, 'trainingTickets'])->name('users.show.training-tickets');
    Route::get('training-assignments', [UserController::class, 'trainingAssignments'])->name('users.show.training-assignments');
    Route::get('solo-certs', [UserController::class, 'soloCerts'])->name('users.show.solo-certs');
});

# Staff Directory
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

# Training Assignment Creation; TODO: make store
Route::post('training-assignment/create', [TrainingAssignmentController::class, 'create'])->middleware('auth')->name('training-assignment.create');

# Admin Routes
Route::prefix('admin')->middleware('permission:view dashboard')->group(function() {
    # Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

    # User Management
    Route::get('users', [UserManagementController::class, 'index'])->name('manage-users.index');
    Route::middleware('permission:manage visiting controllers')->group(function() {
        Route::get('visit-requests/{visitRequest}', [VisitFacilityController::class, 'show'])->name('visit.show');
        Route::get('visit-requests', [VisitFacilityController::class, 'manage'])->name('visit.manage');
        Route::put('visit-requests/{visitRequest}', [VisitFacilityController::class, 'update'])->name('visit.update');
        Route::put('visit-requests/{visitRequest}/approve', [VisitFacilityController::class, 'approve'])->name('visit.approve');
        Route::put('visit-requests/{visitRequest}/deny', [VisitFacilityController::class, 'deny'])->name('visit.deny');
    });

    # Facilities Dept.
    Route::middleware('permission:manage statistics prefixes')->group(function() {
        Route::resource('statistics-prefixes', StatisticsPrefixesController::class);
    });

    # Logs
    Route::middleware('permission:view audit logs')->group(function() {
        Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
    });

    # Training Dept.
    Route::prefix('/training')->middleware('role:training')->group(function() {
        Route::resource('tickets', TrainingTicketController::class)->names('training-tickets');
        Route::resource('assignments', TrainingAssignmentController::class, ['only' => ['update', 'edit', 'index']])->names('training-assignments');
        Route::resource('solo-certs', SoloCertController::class, ['only' => ['index', 'create' ,'update', 'destroy', 'store']])->names('solo-certs');
        Route::put('assignments/claim/{assignment}', [TrainingAssignmentController::class, 'claim'])->name('training-assignments.claim');
        Route::put('assignments/drop/{assignment}', [TrainingAssignmentController::class, 'drop'])->name('training-assignments.drop');
        Route::delete('assignments', [TrainingAssignmentController::class, 'destroy'])->name('training-assignments.destroy'); //id sent in payload
    });

    # Events Dept.
    Route::middleware('permission:manage events')->group(function () {
        Route::resource('event-fields', EventFieldController::class)->names('event-fields');
        Route::resource('position-presets', EventPositionPresetController::class)->names('position-presets');
        Route::resource('events', ManageEventController::class)->names('manage-events');
    });
});

# Dev Only Routes
if (App::environment('development', 'local')) {
    # Test auth as any user
    Route::get('/auth/{id}', function() {
        Auth::loginUsingId(request()->route('id'));
        return redirect()->route('home');
    });

    Route::get('/sync', function() {
        Log::info('balls');
        SyncRoster::dispatch();
        UpdateOnlineControllers::dispatch();
        return 'scheduled';
    });

    Route::get('/sync-training', function() {
        SyncTrainingTickets::dispatch();
        return 'scheduled';
    });

    Route::get('/test-email', function() {
        Mail::to('chrisjm66@gmail.com')->send(new Welcome(User::find(1697197)));
        return new TrainingAssignmentCreated(TrainingAssignment::find(1));
    });
}
