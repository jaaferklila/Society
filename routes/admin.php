<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::group(['prefix'=>'admin','namespace'=>'admin','middleware'=>'auth:admin'],function (){
    ############################### begin dashborad ###########################################################
    Route::get('/', 'auth\AdminLoginController@dashborad')->name('admin.dashborad');
    ############################### end dashborad ###########################################################
    ############################### begin paysons ###########################################################
    Route::get('/paysons', 'UserController@index')->name('admin.listePaysons');
    Route::get('/paysons/create', 'UserController@create')->name('admin.addUser');
    Route::post('/paysons', 'UserController@store')->name('admin.store');
    Route::get('/paysons/{id}/edit', 'UserController@edit')->name('admin.edit');
    Route::post('/paysons/{id}/update', 'UserController@update')->name('admin.update');
    Route::get('/paysons/{id}/delete', 'UserController@destroy')->name('admin.destroy');
    Route::get('/paysons/archive', 'UserController@archive')->name('admin.archive');
    Route::get('/paysons/{id}/archive', 'UserController@makeActive')->name('admin.makeActive');
    Route::get('/paysons/{id}/information', 'UserController@information')->name('admin.information');
    Route::get('/paysons/{id}/createFacture', 'UserController@createFacture')->name('admin.createFacture1');
    Route::get('/paysons/{id}/createFactureVanne/vanneId/{vanneId}/vanneNum/{vanneNum}', 'UserController@createFactureToVanne')->name('admin.createFactureToVanne');
    Route::post('/paysons', 'UserController@storeFacture')->name('facture.store');




    ############################### end paysons ###########################################################
    ############################### begin vanne ###########################################################
    Route::get('/vannes/{id}', 'VanneController@create')->name('vanne.create');
    Route::post('/vannes', 'VanneController@store')->name('vanne.store');
    Route::get('/vannes/getvanne/{user_id}', 'VanneController@getVanne');

    ############################### end vanne ###########################################################

############################### begin facture ###########################################################
    Route::get('/factures/user/{idUser}/vanne/1', 'FactureController@factureUser')->name('admin.facture');
    Route::get('/factures/all', 'FactureController@index')->name('admin.index');
    Route::get('/factures/create', 'FactureController@create')->name('admin.createFacture');


############################### end facture ###########################################################
});

Route::group(['middleware'=>'guest:admin'], function (){
    Route::get('/admin/login', 'admin\auth\AdminLoginController@adminLogin')->name('adminLogin');
    Route::post('/admin/login', 'admin\auth\AdminLoginController@checkLogin')->name('admin.login');

});





