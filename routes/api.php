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

Route::group(['middleware'=>['auth:api'], 'namespace'=>'Api'],function(){
    Route::resource('roles', 'RoleController');
    Route::resource('blogs', 'BlogController');
    Route::resource('contacts', 'ContactController');
    Route::post('roles/delete', 'RoleController@deleteAll');
    Route::get('/userVarify', 'UserController@userVarification');
    Route::post('roles/delete', 'RoleController@deleteAll');
    
});

Route::post('login', 'Api\UserController@login')->name('login');
Route::post('userPermissions', 'Api\UserController@userPermissions')->name('userPermissions');

