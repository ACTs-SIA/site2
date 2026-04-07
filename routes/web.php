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

// --- PROTECTED ROUTES (JWT Token Required) ---
$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->get('/profile', function () {
        return response()->json(auth()->user());
    });
 
    $router->get('/movie', 'MovieController@index');
    $router->post('/movie', 'MovieController@add');
    $router->get('/movie/{id}', 'MovieController@show'); 
    $router->put('/movie/{id}', 'MovieController@update');
    $router->delete('/movie/{id}', 'MovieController@delete');

});