<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/customers', 'CustomerController@index');
$router->get('/customers/{id}', 'CustomerController@show');
$router->get('/rentals', 'RentalController@index');
$router->get('/rentals/{id}', 'RentalController@show');

$router->group(['middleware' => 'basic.auth'], function () use ($router) {
    $router->post('/customers', 'CustomerController@store');
    $router->put('/customers/{id}', 'CustomerController@update');
    $router->delete('/customers/{id}', 'CustomerController@destroy');

    $router->post('/rentals', 'RentalController@store');
    $router->put('/rentals/{id}', 'RentalController@update');
    $router->delete('/rentals/{id}', 'RentalController@destroy');
});