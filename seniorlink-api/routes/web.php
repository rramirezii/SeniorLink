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


$router->get('/', 'LoginController@index');

$router->post('/login', 'LoginController@login');

$router->post('/validate', 'LoginController@validateLogin');

$router->post('/validate-birthday', 'LoginController@validateBirthday');

// admin routes;
$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->post('/create', 'SuperAdminController@create'); // create

    $router->group(['prefix' => 'show'], function () use ($router) {
        $router->get('/{client}[/{username}]', 'SuperAdminController@read'); // read
        $router->get('/town/{town_username}/barangay', 'SuperAdminController@readBarangay'); // read
        $router->get('/town/{town_username}/barangay/{barangay_username}/senior', 'SuperAdminController@readSeniorBarangay'); // read
    });

    $router->post('/update', 'SuperAdminController@update'); // update
    $router->post('/delete', 'SuperAdminController@delete'); // delete
});

// establishment routes
$router->group(['prefix' => 'establishment'], function () use ($router) {
    $router->post('/create/transaction', 'EstablishmentController@create'); // create

    $router->group(['prefix' => '{establishment_username}/show'], function () use ($router) {
        $router->get('/{client}', 'EstablishmentController@read'); // read
    });

    $router->get('/show/transaction/{senior_username}', 'EstablishmentController@readTransaction'); // read
    $router->get('/showById/transaction/{transaction_id}', 'EstablishmentController@readTransactionById'); // read
    
    $router->post('/update', 'EstablishmentController@update'); // update

    $router->post('/delete', 'EstablishmentController@deleteTransaction'); // delete
});

// town routes
$router->group(['prefix' => 'town'], function () use ($router) {
    $router->post('/create', 'TownController@create'); // create

    $router->group(['prefix' => '{town_username}/show'], function () use ($router) {
        $router->get('/{client}[/{username}]', 'TownController@read'); // read
        $router->get('/barangay/{barangay_username}/senior', 'TownController@readSenior'); // read
    });

    $router->get('/{id}', 'TownController@getSelf'); // read self
    $router->post('/update', 'TownController@update'); // update
    $router->post('/delete', 'TownController@delete'); // delete
});

// barangay routes
$router->group(['prefix' => 'barangay'], function () use ($router) {
    $router->post('/create', 'BarangayController@create'); // create

    $router->get('/{barangay_username}/show/{client}[/{username}]', 'BarangayController@read'); // read
    $router->get('/{barangay_username}/show/senior/{senior_username}/transaction', 'BarangayController@readTransaction'); // read

    $router-> get('/{id}', 'BarangayController@getSelf'); // read self
    $router->post('/update', 'BarangayController@update'); // update
    $router->post('/delete', 'BarangayController@delete'); // delete
});

// senior routes
$router->group(['prefix' => 'senior'], function () use ($router) {
    $router->get('/{senior_username}/show/{client}', 'SeniorController@read'); // read

    $router->get('/qr/{id}', 'SeniorController@getQR'); // read

    $router->get('/{id}', 'SeniorController@getSelf'); // read

    $router->get('/username/ret/{username}', 'SeniorController@getSelfFromUsername'); // read

    $router->post('/retrieve', 'SeniorController@getDetailFromQR');

    $router->post('/update', 'SeniorController@update'); // update
});