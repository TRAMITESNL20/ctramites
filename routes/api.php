<?php

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

Route::post('/reference-payment/{reference}', "ReferenceController@paid");
Route::delete('/reference-payment/{reference}', "ReferenceController@cancel");
Route::delete('/reference-payment', "ReferenceController@cancel");
