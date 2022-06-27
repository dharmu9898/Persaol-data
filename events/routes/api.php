<?php

use Classiebit\Eventmie\Facades\Eventmie;

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
Route::group(['prefix'=>'v1/j', 'middleware' => 'client_credentials'
			], function(){
                $namespace = !empty(config('eventmie.controllers.namespace')) ? '\\'.config('eventmie.controllers.namespace') : '\Classiebit\Eventmie\Http\Controllers';
                Route::get('login', 'Auth\LoginController@showLoginForm');
                Route::post('clientlogin', $namespace.'\Auth\LoginController@login')->name('eventmie.login_post');

			});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
