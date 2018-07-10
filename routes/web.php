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

    Route::resource('departments', 'DepartmentController');

    Route::group(['middleware'=>'isadmin', 'prefix'=>'admin'], function(){
        Route::get('/', 'Admin\\IndexController@index');
        Route::get('departments/{department_id}/files', 'Admin\\DepartmentFilesController@index');
        Route::get('departments/{department_id}/users', 'Admin\\DepartmentUsersController@index');
        Route::resource('departments', 'Admin\\DepartmentController');
    });

    Route::group(['prefix' => 'api/v1'], function(){
        Route::group(['prefix' => 'get'], function(){
            Route::get('department/{id?}', 'Api\\DepartmentController@department');
            Route::get('permissions', 'Api\\PermissionsController@permissions');
        });

        Route::group(['prefix'=>'post'], function(){
            Route::post('update-user-permission', 'Api\\DepartmentController@update_user_permission');
            Route::post('delete-user-permission', 'Api\\DepartmentController@delete_user_permission');
            Route::post('add-new-user', 'Api\\DepartmentController@add_new_user');
            Route::post('check-current-user', 'Api\\DepartmentController@check_current_user');
            Route::post('delete-file', 'Api\\DepartmentController@delete_file');
            Route::post('upload-new-files/{departmentid}', 'Api\\DepartmentController@upload_new_files');
            Route::post('get-file-url/{department_id}', 'Api\\DepartmentController@get_file_url');
        });
    });
});
