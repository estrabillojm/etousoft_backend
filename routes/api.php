<?php

use App\Http\Controllers\Controller;
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

Route::middleware('auth:sanctum')->get('/authenticated', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'UserController@login');


Route::group(['middleware' => 'auth:sanctum'], function(){
    // USERS
    Route::post('/register', 'UserController@register');
    Route::get('/users', 'UserController@paginateUser');
    Route::get('/all-users', 'UserController@allUser');
    Route::post('/logout', 'UserController@logout');
    Route::get('/user/{id}', 'UserController@singleUser');
    Route::patch('/user/{id}', 'UserController@updateUser');
    Route::delete('/user/{id}', 'UserController@deleteUser');
});

