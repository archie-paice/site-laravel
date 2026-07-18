<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\VatsimOauthController;
use App\Http\Controllers\Admin\ManualContributorController;
use App\Http\Controllers\ContributorsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Training\SoloCertController;
use App\Http\Controllers\Training\TrainingAssignmentController;
use App\Http\Controllers\Training\TrainingTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VisitFacilityController;
use App\Jobs\SyncRoster;
use App\Jobs\SyncTrainingTickets;
use App\Jobs\UpdateOnlineControllers;
use App\Livewire\EventRegistration;
use App\Mail\TrainingAssignmentCreated;
use App\Mail\Welcome;
use App\Models\TrainingAssignment;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventPositionPresetController;
use App\Http\Controllers\EventFieldController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CertificationFacilityController;
use App\Http\Controllers\CertificationLevelController;
use App\Http\Controllers\PublicationsController;
use App\Http\Controllers\AdminPublicationsController;
use App\Http\Controllers\AdminPublicationCategoriesController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

# Contributors
Route::get('/contributors', [ContributorsController::class, 'index'])->name('contributors.index');

# Roster
Route::get('/roster', [RosterController::class, 'index'])->name('roster.index');

// Visit
Route::get('/visit', [VisitFacilityController::class, 'index'])->name('visit.index');
Route::get('/visit/create', [VisitFacilityController::class, 'create'])->middleware('auth')->name('visit.create');
Route::post('/visit', [VisitFacilityController::class, 'store'])->middleware('auth')->name('visit.store');

// Oauth
Route::get('/auth/redirect', [VatsimOauthController::class, 'redirect'])->name('auth.redirect');
Route::get('/login', function () {
    return redirect()->route('auth.redirect', [], 301);
})->name('login');
Route::get('/auth/callback', [VatsimOauthController::class, 'callback'])->name('auth.callback');
Route::get('/auth/logout', [VatsimOauthController::class, 'logout'])->name('auth.logout');

// Users
Route::resource('users', UserController::class, ['only' => ['show', 'edit', 'update']]);
Route::prefix('users/{user}')->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('users.show');
    Route::get('training-tickets', [UserController::class, 'trainingTickets'])->middleware('auth')->name('users.show.training-tickets');
    Route::get('training-assignments', [UserController::class, 'trainingAssignments'])->middleware('auth')->name('users.show.training-assignments');
    Route::get('solo-certs', [UserController::class, 'soloCerts'])->middleware('auth')->name('users.show.solo-certs');
});

// Staff Directory
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

# Controller Statistics
Route::get('controllers/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

# Publications & Downloads
Route::get('/publications/downloads', [PublicationsController::class, 'index'])->name('publications.index');
Route::get('/publications/{publication}/file', [PublicationsController::class, 'file'])->name('publications.file');

# Training Assignment Creation; TODO: make store
Route::post('training-assignment/create', [TrainingAssignmentController::class, 'create'])->middleware('auth')->name('training-assignment.create');

# Training ticket view (own or training staff)
Route::get('training-tickets/{ticket}', [TrainingTicketController::class, 'show'])->middleware('auth')->name('training-tickets.show');
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('{event}', [EventController::class, 'show'])->name('show');
});
Route::post('/events/{event}/request-position', [EventRegistration::class, 'store'])->middleware('auth')->name('events.request-position.store');

// Admin Routes
Route::prefix('admin')->middleware('permission:view dashboard')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

    // User Management
    Route::get('users', [UserManagementController::class, 'index'])->name('manage-users.index');
    Route::middleware('permission:manage visiting controllers')->group(function () {
        Route::get('visit-requests/{visitRequest}', [VisitFacilityController::class, 'show'])->name('visit.show');
        Route::get('visit-requests', [VisitFacilityController::class, 'manage'])->name('visit.manage');
        Route::put('visit-requests/{visitRequest}', [VisitFacilityController::class, 'update'])->name('visit.update');
        Route::put('visit-requests/{visitRequest}/approve', [VisitFacilityController::class, 'approve'])->name('visit.approve');
        Route::put('visit-requests/{visitRequest}/deny', [VisitFacilityController::class, 'deny'])->name('visit.deny');
    });

    # Contributors
    Route::middleware('role:admin')->group(function() {
        Route::get('contributors', [ManualContributorController::class, 'index'])->name('admin.contributors.index');
        Route::post('contributors', [ManualContributorController::class, 'store'])->name('admin.contributors.store');
        Route::delete('contributors/{contributor}', [ManualContributorController::class, 'destroy'])->name('admin.contributors.destroy');
    });

    # Facilities Dept.
    Route::prefix('data')->group(function() {
        Route::middleware('permission:manage statistics prefixes')->resource('statistics-prefixes', StatisticsPrefixesController::class);

        Route::middleware('permission:manage certification facilities')->prefix('certification-facilities')->group(function () {
            Route::get('/', [CertificationFacilityController::class, 'index'])->name('certification-facilities.index');
            Route::post('/', [CertificationFacilityController::class, 'store'])->name('certification-facilities.store');

            Route::prefix('/{facility}')->group(function () {
                Route::get('/', [CertificationFacilityController::class, 'show'])->name('certification-facilities.show');
                Route::delete('/', [CertificationFacilityController::class, 'destroy'])->name('certification-facilities.destroy');
                Route::post('/certification-levels', [CertificationLevelController::class, 'store'])->name('certification-levels.store');
            });
        });
    });

    # Senior Staff / Web Team
    Route::middleware('role:admin')->group(function() {
        Route::post('statistics/sync', [StatisticsController::class, 'sync'])->name('statistics.sync');
    });

    # Publications Management (Facilities Dept.)
    Route::middleware('permission:documents:write')->prefix('publications')->name('admin.publications.')->group(function () {
        Route::get('/',            [AdminPublicationsController::class, 'index'])->name('index');
        Route::get('/create',      [AdminPublicationsController::class, 'create'])->name('create');
        Route::post('/',           [AdminPublicationsController::class, 'store'])->name('store');

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/',            [AdminPublicationCategoriesController::class, 'index'])->name('index');
            Route::get('/create',      [AdminPublicationCategoriesController::class, 'create'])->name('create');
            Route::post('/',           [AdminPublicationCategoriesController::class, 'store'])->name('store');
            Route::get('/{id}/edit',   [AdminPublicationCategoriesController::class, 'edit'])->name('edit');
            Route::put('/{id}',        [AdminPublicationCategoriesController::class, 'update'])->name('update');
            Route::delete('/{id}',     [AdminPublicationCategoriesController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle-nav', [AdminPublicationCategoriesController::class, 'toggleNav'])->name('toggle-nav');
        });

        Route::get('/{id}/edit',   [AdminPublicationsController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [AdminPublicationsController::class, 'update'])->name('update');
        Route::delete('/{id}',     [AdminPublicationsController::class, 'destroy'])->name('destroy');
    });

    # Logs
    Route::middleware('permission:view audit logs')->group(function() {
        Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
        Route::get('logs/export', [AuditLogController::class, 'export'])->name('logs.export');
    });

    # Training Dept.
    Route::prefix('/training')->middleware('role:training')->group(function() {
        Route::resource('tickets', TrainingTicketController::class, ['except' => ['show']])->names('training-tickets');
        Route::resource('assignments', TrainingAssignmentController::class, ['only' => ['update', 'edit', 'index']])->names('training-assignments');
        Route::resource('solo-certs', SoloCertController::class, ['only' => ['index', 'create', 'destroy', 'store']])->names('solo-certs');
        Route::put('assignments/claim/{assignment}', [TrainingAssignmentController::class, 'claim'])->name('training-assignments.claim');
        Route::put('assignments/drop/{assignment}', [TrainingAssignmentController::class, 'drop'])->name('training-assignments.drop');
        Route::delete('assignments', [TrainingAssignmentController::class, 'destroy'])->name('training-assignments.destroy'); // id sent in payload
    });

    // Events Dept.
    Route::prefix('/events')->middleware('permission:manage events')->group(function () {
        Route::resource('event-fields', EventFieldController::class)->names('admin.events.event-fields');
        Route::resource('position-presets', EventPositionPresetController::class)->names('admin.events.position-presets');
        Route::get('/', [EventController::class, 'manage'])->name('admin.events.index');
        Route::get('create', [EventController::class, 'create'])->name('admin.events.create');
        Route::post('/', [EventController::class, 'store'])->name('admin.events.store');
        Route::get('{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
        Route::put('{event}', [EventController::class, 'update'])->name('admin.events.update');
        Route::delete('{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    });
});

// Dev Only Routes
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
