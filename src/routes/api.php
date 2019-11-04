<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'api/v1',
    'middleware' => ['auth']
], function () use ($router) {
    $router->get('profile', 'ProfileController@read');
    $router->put('profile', 'ProfileController@update');

    $router->post('profile/followers/{id}', 'ProfileController@addFollower');
    $router->delete('profile/followers/{id}', 'ProfileController@deleteFollower');
});

$router->group([
    'prefix' => 'api/v1',
    'middleware' => ['auth', 'admin']
], function () use ($router) {
    /**
     * CRUD Routes
     */
    foreach ([
        'users' => 'UserController',
    ] as $path => $controller) {
        $router->post($path, $controller . '@create');
        $router->put($path . '/{id}', $controller . '@update');
        $router->delete($path . '/{id}', $controller . '@delete');
    }
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('version', function () use ($router) {
        $versionPath = base_path() . '/public/version.txt';
        if (!file_exists($versionPath)) {
            return '';
        }

        return file_get_contents($versionPath);
    });

    /**
     * Auth Routes
     */
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    /**
     * CRUD Routes
     */
    foreach ([
        'users' => 'UserController',
    ] as $path => $controller) {
        $router->get($path, $controller . '@index');
        $router->get($path . '/{id}', $controller . '@read');
    }

});
