<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->post('/liveRequest', function (Request $request) {
    return $request->user();
});

Route::post('v1/post/live-request', 'b2cApiController@postLiveRequest')->middleware('auth:api');

Route::post('v1/post/live-request-test', 'b2cApiController@testLiveRequest')->middleware('auth:api');

Route::post('v1/post/fico-request', 'b2cApiController@postFicoRequest')->middleware('auth:api');

Route::post('v1/post/icon', 'IconController@icon')->middleware('auth:api');