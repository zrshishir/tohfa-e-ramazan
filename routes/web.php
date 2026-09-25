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

// Route::view rather than a closure: Laravel cannot serialise closures, so a single
// closure route makes `php artisan route:cache` fail for the whole application with
// "Unable to prepare route for serialization". That meant production deploys had to
// skip route caching entirely.
//
// Worth keeping working — Google Play requires a reachable privacy policy URL.
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');

// feedback form routes
Route::get('/feedback-form', 'App\Http\Controllers\FeedbackController@index')->name('feedback.form');
Route::post('/feedback', 'App\Http\Controllers\FeedbackController@store')->name('feedback.store');
