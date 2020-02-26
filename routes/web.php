<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mainpage');
});

Route::get('/sov-radar/add-watcher', function () {
    return view('add-watcher');
});

Route::post('/sov-radar/add-watcher', 'AddWatcher@index');
Route::get(config('eve.callbackURL'), 'AddWatcher@handleCode');
