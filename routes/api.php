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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User routes

Route::post('/user/create', 'App\Http\Controllers\RegisterationController@create');

Route::get('/user/login', 'App\Http\Controllers\RegisterationController@login');

Route::post('/services/status/update', 'App\Http\Controllers\ServicesUsedController@update');

Route::post('/user/move/out', 'App\Http\Controllers\MoveOutController@move_out_request');

// Admin routes

Route::get('/user/data', 'App\Http\Controllers\UserController@details');

Route::get('/user/report', 'App\Http\Controllers\UserController@user_report');

Route::get('/user/residents', 'App\Http\Controllers\UserController@residents');

Route::get('/service/report', 'App\Http\Controllers\UserController@services_report');

Route::post('/make/inspector', 'App\Http\Controllers\UserController@make_inspector');

Route::post('/assign/inspector/service', 'App\Http\Controllers\InspectorServicesController@create');

Route::post('/contact/store', 'App\Http\Controllers\ContactController@store');

// 
// Service routes

Route::get('/resident/services/all', 'App\Http\Controllers\ResidentServicesController@get_all');

Route::get('/resident/services/one', 'App\Http\Controllers\ResidentServicesController@get_one');

Route::post('/resident/services/create', 'App\Http\Controllers\ResidentServicesController@create');

Route::post('/resident/services/update', 'App\Http\Controllers\ResidentServicesController@edit');

Route::post('/resident/services/delete', 'App\Http\Controllers\ResidentServicesController@delete');

Route::get('/test/call', 'App\Http\Controllers\ChatController@find_all_chats');

Route::post('/send/message', 'App\Http\Controllers\ChatController@send_message');

Route::get('/fetch/chats', 'App\Http\Controllers\ChatController@find_all_chats');
// find_all_chats


// Extra services

Route::post('/add/business', 'App\Http\Controllers\ExtraServicesController@add_business');

Route::post('/add/counties', 'App\Http\Controllers\ExtraServicesController@add_counties');

Route::post('/add/education', 'App\Http\Controllers\ExtraServicesController@add_education');

Route::post('/add/transport', 'App\Http\Controllers\ExtraServicesController@add_transport');

Route::get('/extra/services', 'App\Http\Controllers\ExtraServicesController@get_extra_services');