<?php

use App\Http\Controllers\Api\ApiAdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventOrganizerController;

use App\Http\Controllers\Api\ApiPopularPlaceController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiEventController;
use App\Http\Controllers\Api\ApiOrganizerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// for admin
Route::get('/admin/organizer/events/{id}', [ApiAdminController::class, 'getEventsByOrganizer']);
Route::get('/admin/place-boundaries', [ApiAdminController::class, 'placeBoundaries']);



// for end user / guest
Route::get('/events', [ApiEventController::class, 'findEvents']);
Route::get('/event/detail/id={id}', [ApiEventController::class, 'findEventDetail']);


// for organizers
Route::post('/organizer/event/validate', [ApiOrganizerController::class, 'validateEvent']);
Route::get('/organizer/event/{id}/submission-history', [ApiOrganizerController::class, 'submissionHistory']);
Route::get('/organizer/event/{id}/detail', [ApiOrganizerController::class, 'getEventDetailById']);
Route::post('/organizer/event/upload-content-image', [ApiOrganizerController::class, 'uploadContentImage']);

// for general (all scope)
Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/popular-places', [ApiPopularPlaceController::class, 'index']);
Route::get('/place-boundaries', [ApiEventController::class, 'placeBoundaries']);
