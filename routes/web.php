<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group([ 'prefix' => 'product'], function () use($router) {
    $router->get('/', 'ProductController@list');
    $router->get('/{id}', 'ProductController@getById');
    $router->post('/create', 'ProductController@create');
    $router->post('/delete', 'ProductController@delete');
    $router->post('/update', 'ProductController@update');
});

$router->group([ 'prefix' => 'category'], function () use($router) {
    $router->get('/', 'CategoryController@list');
    $router->get('/{id}', 'CategoryController@getById');
    $router->post('/create', 'CategoryController@create');
    $router->post('/delete', 'CategoryController@delete');
    $router->post('/update', 'CategoryController@update');
});