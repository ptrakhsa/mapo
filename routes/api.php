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
Route::get('/admin/eo/events/{id}', [ApiEventController::class, 'getEventsByOrganizer']);
Route::get('/admin/place-boundaries', [ApiAdminController::class, 'placeBoundaries']);



// for end user / guest
Route::get('/locations', [ApiEventController::class, 'index']);
Route::get('/location/{id}', [ApiEventController::class, 'eventDetail']);
Route::get('/events', [ApiEventController::class, 'findEvents']);
Route::get('/event/detail/id={id}', [ApiEventController::class, 'findEventDetail']);


// for organizers
Route::post('/event/validate', [ApiEventController::class, 'validateEvent']);
Route::get('/organizer/event/{id}/submission-history', [ApiEventController::class, 'submissionHistory']);
Route::get('/organizer/event/{id}/detail', [ApiOrganizerController::class, 'getEventDetailById']);
Route::post('/organizer/event/upload-content-image', [ApiEventController::class, 'uploadContentImage']);

// for general (all scope)
Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/popular-places/all', [ApiPopularPlaceController::class, 'index']);
Route::get('/geojson/yogyakarta-province', [ApiEventController::class, 'yogyakartaGeoJSON']);
