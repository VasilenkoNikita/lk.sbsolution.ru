<?php

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
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboardjet', function () {
    return redirect('/dashboard');
   // return view('dashboard');
})->name('dashboardjet');
Route::get('/subscribe', 'SubscribeController@index')->name('subscribe');
Route::post('/news-letter', 'NewsLetterController@store')->name('news-letter');
