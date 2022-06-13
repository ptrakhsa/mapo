<?php


use App\Http\Controllers\EventController;
use App\Http\Controllers\EventOrganizerController;

use App\Http\Controllers\Api\ApiPopularPlaceController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiEventController;

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


// for end user / guest
Route::get('/locations', [ApiEventController::class, 'index']);
Route::get('/location/{id}', [ApiEventController::class, 'eventDetail']);


// for organizers
Route::post('/event/validate', [ApiEventController::class, 'validateEvent']);
Route::get('/organizer/event/{id}/submission-history', [ApiEventController::class, 'submissionHistory']);

// for general (all scope)
Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/popular-places/all', [ApiPopularPlaceController::class, 'index']);
Route::get('/geojson/yogyakarta-province', [ApiEventController::class, 'yogyakartaGeoJSON']);
