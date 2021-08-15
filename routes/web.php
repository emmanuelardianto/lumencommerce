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
    $router->get('/update/assets', 'ProductController@productAssets');
});

$router->group([ 'prefix' => 'category'], function () use($router) {
    $router->get('/', 'CategoryController@list');
    $router->get('/{id}', 'CategoryController@getById');
    $router->post('/create', 'CategoryController@create');
    $router->post('/delete', 'CategoryController@delete');
    $router->post('/update', 'CategoryController@update');
    $router->post('/get-with-product', 'CategoryController@getCategoryWithProduct');
});

$router->group([ 'prefix' => 'user'], function () use($router) {
    $router->get('/', 'UserController@list');
    $router->get('/{id}', 'UserController@getById');
    $router->post('/create', 'UserController@create');
    $router->post('/delete', 'UserController@delete');
    $router->post('/update', 'UserController@update');
});

$router->group([ 'prefix' => 'product-variant-ref'], function () use($router) {
    $router->get('/', 'ProductVariantRefController@list');
    $router->get('/{id}', 'ProductVariantRefController@getById');
    $router->post('/create', 'ProductVariantRefController@create');
    $router->post('/update', 'ProductVariantRefController@update');
});