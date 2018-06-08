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

Route::get('/', 'HomeController@index')->name('home.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home.home');

Route::group(['prefix' => 'invites'], function () {
    Route::get('/', 'InviteController@index')->name('invites.index');
    Route::get('/create', 'InviteController@create')->name('invites.create');
    Route::get('/accept/{token}', 'InviteController@accept')->name('invites.accept');
    Route::post('/accept/create-account', 'InviteController@createAccount')->name('invites.create-account');
});

Route::group(['prefix' => 'chat'], function() {
    Route::get('/messages', 'ChatController@messages');
    Route::get('/timer', 'ChatController@timer');
    Route::get('/messages/private/{user}', 'ChatController@privateMessage');
    Route::get('/smileys', 'ChatController@smileys');

    Route::patch('/message/{shout}', 'ChatController@edit');

    Route::delete('/message/{shout}', 'ChatController@delete');
    
    Route::post('/submit', 'ChatController@submit');
});
