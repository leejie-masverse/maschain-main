<?php

use Dingo\Api\Routing\Router as ApiRouter;

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

$api = app(ApiRouter::class);

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\v1',
    'middleware' => [ 'api' ],
], function(ApiRouter $router) {
    // token
    $router->post('auth/token', 'Auth\TokenController@login');
    $router->patch('auth/token', 'Auth\TokenController@refresh');
    $router->delete('auth/token', 'Auth\TokenController@expire');

    // reset password
    $router->post('auth/forgot', 'Auth\ForgotPasswordController@forgot');
    $router->post('auth/reset/{tokenid}', 'Auth\ForgotPasswordController@validateToken' );

    // authed user
    $router->get('auth/user', 'Auth\UserController@show');
    $router->patch('auth/user', 'Auth\UserController@update');
    $router->patch('auth/user/password', 'Auth\UserController@updatePassword');

    // system users
    $router->get('people/users', 'People\SystemUsersController@index');
    $router->post('people/users', 'People\SystemUsersController@store');
    $router->get('people/users/{id}', 'People\SystemUsersController@show');
    $router->patch('people/users/{id}', 'People\SystemUsersController@update');
    $router->patch('people/users/{id}/password', 'People\SystemUsersController@updatePassword');
    $router->post('people/users/banned/{id}', 'People\SystemUsersController@ban');
    $router->delete('people/users/banned/{id}', 'People\SystemUsersController@unban');
    $router->delete('people/users/{id}', 'People\SystemUsersController@destroy');
});
