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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware'=>['api']],function(){
    Route::post('signin','auth\authController@signin');
    Route::post('signup','auth\authController@signup');
    Route::group(['prefix'=>'admin', 'middleware'=>['jwt.auth']],function(){
        Route::group(['prefix'=>'barang'],function(){
            Route::get('','BarangController@index');
            Route::post('create','BarangController@create');
            Route::get('edit/{id_barang}','BarangController@edit');
            Route::put('update','BarangController@update');
            Route::delete('delete/{id_barang}','BarangController@delete');
        });
        Route::group(['prefix'=>'peminjaman'],function(){
            Route::get('pengajuan','PeminjamanController@pengajuan');
            Route::post('create','PeminjamanController@create');
            Route::post('aprove-agreement/{id_pinjaman}','PeminjamanController@approved');
        });
    });
    Route::group(['middleware'=>['jwt.auth']],function(){
        Route::get('profile','profileController@index');
        Route::post('profile','PeminjamanController@dataArray');
    });

    
});