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

Route::post('folder/{id}/passcode', 'FolderController@passcode');
Route::post('folder/{id}/upload', 'FolderController@upload');
Route::get('folder/{id}/documents', 'FolderController@documents');
Route::get('folder/{id}/documents/{file}', 'FolderController@folder');
Route::get('folder/{id}/uploads', 'FolderController@uploads');
Route::get('folder/{id}', 'FolderController@show');
Route::get('folder', 'FolderController@index');

Route::post('s3/notification', 'S3Notification@index');
Route::get('s3/notification', 'S3Notification@index');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/admin/folder', 'AdminFolderController');
