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

// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
// header('Access-Control-Allow-Origin: *');

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([ 'prefix' => 'product'], function () use($router) {
    $router->get('/', 'ProductController@list');
    $router->get('/{id}', 'ProductController@getById');
    $router->get('/detail/{slug}', 'ProductController@getBySlug');
    $router->post('/create', 'ProductController@create');
    $router->post('/delete', 'ProductController@delete');
    $router->post('/update', 'ProductController@update');
    $router->get('/get/assets', 'ProductController@productAssets');
    $router->post('/image-update', 'ProductController@imageUpdate');
    $router->post('/image-remove', 'ProductController@imageRemove');
    $router->post('/variant-image-update', 'ProductController@variantImageUpdate');
    $router->post('/variant-image-remove', 'ProductController@imageRemove');
});

$router->group([ 'prefix' => 'category'], function () use($router) {
    $router->get('/', 'CategoryController@list');
    $router->get('/{id}', 'CategoryController@getById');
    $router->get('/detail/{slug}', 'CategoryController@getBySlug');
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

$router->group([ 'prefix' => 'address'], function () use($router) {
    $router->get('/', 'AddressController@list');
    $router->get('/{id}', 'AddressController@getById');
    $router->post('/create', 'AddressController@create');
    $router->post('/delete', 'AddressController@delete');
    $router->post('/update', 'AddressController@update');
    $router->post('/default', 'AddressController@setDefault');
});

$router->group([ 'prefix' => 'product-variant-ref'], function () use($router) {
    $router->get('/', 'ProductVariantRefController@list');
    $router->get('/{id}', 'ProductVariantRefController@getById');
    $router->post('/create', 'ProductVariantRefController@create');
    $router->post('/update', 'ProductVariantRefController@update');
});

$router->group([ 'prefix' => 'collection'], function () use($router) {
    $router->get('/', 'CollectionController@list');
    $router->get('/{id}', 'CollectionController@getById');
    $router->get('/get-by-slug/{slug}', 'CollectionController@getBySlug');
    $router->post('/update', 'CollectionController@update');
    $router->post('/delete', 'CollectionController@delete');
});

$router->group([ 'prefix' => 'product-review'], function () use($router) {
    $router->post('/create', 'ProductReviewController@create');
    $router->post('/by-user', 'ProductReviewController@listByUser');
    $router->post('/by-product', 'ProductReviewController@listByProduct');
    $router->post('/delete', 'ProductReviewController@delete');
});

$router->group([ 'prefix' => 'gallery'], function () use($router) {
    $router->post('/list', 'GalleryController@list');
});

$router->group([ 'prefix' => 'wishlist'], function () use($router) {
    $router->post('/toggle', 'WishlistController@toggle');
    $router->post('/list', 'WishlistController@list');
});


$router->group(['prefix' => 'auth'], function () use($router) { 
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');
    $router->post('/change-password', 'AuthController@changePassword');
    $router->post('/forgot-password', 'AuthController@forgotPassword');
    $router->post('/reset-password', 'AuthController@resetPassword');
    $router->post('/validate-token', 'AuthController@validateToken');
});

$router->group(['middleware' => ['auth:api'], 'prefix' => 'auth'], function () use($router) { 
    $router->post('logout', 'AuthController@logout');
    $router->get('/me', 'AuthController@me');
});
