<?php

use App\Http\Controllers\HadithController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MasalaController;
use App\Http\Controllers\TasbihController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/permanent-calendar", "App\Http\Controllers\PermanentCalendarController@index");
Route::get("/permanent-calendar/{month_id}", "App\Http\Controllers\PermanentCalendarController@byMonth");
Route::get("/today-prayer", "App\Http\Controllers\PermanentCalendarController@today");
Route::get("/ramazan-calendar", "App\Http\Controllers\PermanentCalendarController@ramazanCalendar");
Route::get('/mazhabs', 'App\Http\Controllers\MazhabController@index');

// location pickers for the settings screen
Route::get('/divisions', [LocationController::class, 'divisions']);
Route::get('/districts', [LocationController::class, 'districts']);

// tasbih routes — {userId} is the owning user, not the tasbih row id
Route::get('/tasbih', [TasbihController::class, 'index']);
Route::post('/tasbih', [TasbihController::class, 'store']);
Route::get('/tasbih/{userId}', [TasbihController::class, 'show'])->whereNumber('userId');
Route::put('/tasbih/{userId}', [TasbihController::class, 'update'])->whereNumber('userId');
Route::delete('/tasbih/{userId}', [TasbihController::class, 'destroy'])->whereNumber('userId');
// write doa category routes here
Route::get('/doa-category', 'App\Http\Controllers\DoaCategoryController@index');
// write doa routes here
Route::get('/doa/{id}', 'App\Http\Controllers\DoaController@index');
// route to show
// Route::get('/doa', 'App\Http\Controllers\DoaController@show');
// write sura routes here
Route::get('/sura', 'App\Http\Controllers\SuraController@index');

// write ayat routes here
Route::get('/ayat/{id}', 'App\Http\Controllers\AyatController@index');

// asmaul husna
Route::get('/asmaul-husna', 'App\Http\Controllers\AsmaulHusnaController@index');

// hadith routes
Route::get('/hadith-books', [HadithController::class, 'books']);
Route::get('/hadith-books/{bookId}/chapters', [HadithController::class, 'chapters'])->whereNumber('bookId');
Route::get('/hadith-random', [HadithController::class, 'random']);
Route::get('/hadith', [HadithController::class, 'index']);
Route::get('/hadith/{id}', [HadithController::class, 'show'])->whereNumber('id');

// masala routes
Route::get('/masala', [MasalaController::class, 'index']);
Route::get('/masala/{id}', [MasalaController::class, 'show']);
