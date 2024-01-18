<?php

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

Route::get("/permanent-calendar", "App\Http\Controllers\PermanentCalendarController@index");
Route::get('/mazhabs', 'App\Http\Controllers\MazhabController@index');

// write tasbih routes here
Route::get('/tasbih', 'App\Http\Controllers\TasbihController@index');
Route::post('/tasbih', 'App\Http\Controllers\TasbihController@store');
Route::put('/tasbih', 'App\Http\Controllers\TasbihController@update');
Route::delete('/tasbih', 'App\Http\Controllers\TasbihController@destroy');
// route to show
Route::get('/tasbih/{id}', 'App\Http\Controllers\TasbihController@show');
// write doa category routes here
Route::get('/doa-category', 'App\Http\Controllers\DoaCategoryController@index');
// write doa routes here
Route::get('/doa/{category_id}', 'App\Http\Controllers\DoaController@index');
// route to show
Route::get('/doa/{id}', 'App\Http\Controllers\DoaController@show');
