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


Route::group([
    'prefix' => 'manage',
], function () {
    Route::get('/', function () {
        return redirect(route('manage.dashboard'));
    });

    //auth
    Route::get('account/login', 'Manage\Account\LoginController@showLoginForm')->name('manage.account.login');
    Route::post('account/login', 'Manage\Account\LoginController@login');
    Route::post('account/logout', 'Manage\Account\LoginController@logout')->name('manage.account.logout');

    //profile
    Route::get('account/profile', 'Manage\Account\ProfileController@edit')->name('manage.account.profile.edit');
    Route::patch('account/profile', 'Manage\Account\ProfileController@update')->name('manage.account.profile.update');
    Route::get('account/password', 'Manage\Account\ProfileController@editPassword')->name('manage.account.profile.edit-password');
    Route::patch('account/password', 'Manage\Account\ProfileController@updatePassword')->name('manage.account.profile.update-password');

    //dashboard
    Route::get('dashboard', 'Manage\DashboardController@dashboard')->name('manage.dashboard');

    //users
    Route::get('people/users', 'Manage\People\UsersController@index')->name('manage.people.users.list');
    Route::post('people/users', 'Manage\People\UsersController@store')->name('manage.people.users.store');
    Route::get('people/users/{id}/edit', 'Manage\People\UsersController@edit')->name('manage.people.users.edit');
    Route::get('people/users/{id}', 'Manage\People\UsersController@show')->name('manage.people.users.show');
    Route::patch('people/users/{id}', 'Manage\People\UsersController@update')->name('manage.people.users.update');
    Route::get('people/users/{id}/edit-password', 'Manage\People\UsersController@editPassword')->name('manage.people.users.edit-password');
    Route::patch('people/users/{id}/password', 'Manage\People\UsersController@updatePassword')->name('manage.people.users.update-password');
    Route::post('people/users/banned/{id}', 'Manage\People\UsersController@ban')->name('manage.people.users.ban');
    Route::delete('people/users/banned/{id}', 'Manage\People\UsersController@unban')->name('manage.people.users.unban');
    Route::post('people/users/release/{id}', 'Manage\People\UsersController@release')->name('manage.people.users.release');
    Route::delete('people/users/{id}', 'Manage\People\UsersController@destroy')->name('manage.people.users.destroy');
    Route::post('people/users/verify/{id}', 'Manage\People\UsersController@verify')->name('manage.people.users.verify');

    //admins
    Route::get('people/admins', 'Manage\People\AdminsController@index')->name('manage.people.admins.list');
    Route::get('people/admins/create', 'Manage\People\AdminsController@create')->name('manage.people.admins.create');
    Route::post('people/admins', 'Manage\People\AdminsController@store')->name('manage.people.admins.store');
    Route::get('people/admins/{id}/edit', 'Manage\People\AdminsController@edit')->name('manage.people.admins.edit');
    Route::patch('people/admins/{id}', 'Manage\People\AdminsController@update')->name('manage.people.admins.update');
    Route::get('people/admins/{id}/edit-password', 'Manage\People\AdminsController@editPassword')->name('manage.people.admins.edit-password');
    Route::patch('people/admins/{id}/password', 'Manage\People\AdminsController@updatePassword')->name('manage.people.admins.update-password');
    Route::post('people/admins/banned/{id}', 'Manage\People\AdminsController@ban')->name('manage.people.admins.ban');
    Route::delete('people/admins/banned/{id}', 'Manage\People\AdminsController@unban')->name('manage.people.admins.unban');
    Route::delete('people/admins/{id}', 'Manage\People\AdminsController@destroy')->name('manage.people.admins.destroy');
});
