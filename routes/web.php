<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

// feedback form routes
Route::get('/feedback-form', 'App\Http\Controllers\FeedbackController@index')->name('feedback.form');
Route::post('/feedback', 'App\Http\Controllers\FeedbackController@store')->name('feedback.store');
