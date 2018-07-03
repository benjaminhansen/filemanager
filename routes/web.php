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

Route::resource('login', 'AuthController');
Route::get('logout', 'AuthController@logout');

Route::group(['middleware'=>'checkauth'], function(){
    Route::get('/', function () {
        return redirect('dashboard');
    });

    Route::resource('dashboard', 'DashboardController');

    Route::resource('department', 'DepartmentController');

    Route::group(['middleware'=>'isadmin', 'prefix'=>'admin'], function(){
        Route::get('/', 'Admin\\IndexController@index');
    });

    Route::group(['prefix' => 'api/v1'], function(){
        Route::group(['prefix' => 'get'], function(){
            Route::get('department/{id?}', 'Api\\DepartmentController@department');
        });

        Route::group(['prefix'=>'post'], function(){

        });
    });
});
