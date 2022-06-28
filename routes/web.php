<?php


use App\Http\Controllers\EventController;

use App\Http\Controllers\Admin\AdminOrganizerController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\MobileAppController;
// organizer
use App\Http\Controllers\Organizer\OrganizerAuthController;
use App\Http\Controllers\Organizer\OrganizerDashboardController;
use App\Http\Controllers\Organizer\OrganizerEventController;

use App\Http\Controllers\TestController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

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
    return view('index');
});

Route::get('/mobile/find-events', function () {
    return view('mobile.find-events');
});

Route::get('/mobile/event/detail/id={id}', [MobileAppController::class, 'detail']);



Route::prefix('admin')->group(function () {
    // unauthenticated pages
    Route::get('/login', [AdminAuthController::class, 'loginPage'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::get('/', [AdminAuthController::class, 'authCheck']);

    // authenticated pages
    Route::middleware(['admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // admin manage organizer
        Route::get('/eo', [AdminOrganizerController::class, 'index'])->name('admin.eo');

        // admin manage event routes
        Route::get('/events', [AdminEventController::class, 'index'])->name('admin.events');
        Route::get('/event/detail/{id}', [AdminEventController::class, 'show']);
        Route::post('/event/accept/{id}', [AdminEventController::class, 'acceptEvent']);
        Route::post('/event/reject/{id}', [AdminEventController::class, 'rejectEvent']);
        Route::post('/event/takedown/{id}', [AdminEventController::class, 'takedownEvent']);


        Route::get('/event/show/detail/{id}', [AdminEventController::class, 'showEventDetail']);
    });
});

Route::prefix('organizer')->group(function () {

    Route::get('/', [OrganizerAuthController::class, 'authCheck']);

    // unauthenticated pages
    Route::get('/login', [OrganizerAuthController::class, 'loginPage'])->name('organizer.login');
    Route::post('/login', [OrganizerAuthController::class, 'login']);
    Route::get('/register', [OrganizerAuthController::class, 'registerPage'])->name('organizer.register');
    Route::post('/register', [OrganizerAuthController::class, 'register']);

    // authenticated pages
    Route::middleware(['organizer'])->group(function () {

        Route::post('/logout', [OrganizerAuthController::class, 'logout']);

        // dashboard 
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('organizer.dashboard')->middleware('organizer');


        // organizer manage events routes
        Route::get('/create', function () {
            return view('organizer.create-event');
        });
        Route::post('/event/store', [OrganizerEventController::class, 'store']);
        Route::get('/event/edit', function () {
            return view('organizer.event-edit');
        });
        Route::post('/event/delete/{id}', [OrganizerEventController::class, 'organizerEventDelete']);
        Route::get('/event/detail/{id}', [OrganizerEventController::class, 'organizerEventDetail']);
        Route::get('/profile', function () {
            return view('organizer.profile');
        });
        Route::get('/activity', [OrganizerEventController::class, 'organizerActivity'])->name('organizer.activity');

        // toc pages
        Route::get('/toc', function () {
            return view('organizer.toc');
        });
    });
});
