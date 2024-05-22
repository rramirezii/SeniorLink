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

// admin routes
$router->post('/admin/create', 'SuperAdminController@create'); // create

$router->get('/admin/{superadmin_username}/show/{client}', 'SuperAdminController@read'); // read

$router->get('/admin/{superadmin_username}/show/town/{town_username}/{client}', 'SuperAdminController@readBarangay'); // read

$router->get('/admin/{superadmin_username}/show/barangay/{barangay_username}/{client}', 'SuperAdminController@readSenior'); // read

$router->get('/admin/{superadmin_username}/show/town/{town_username}/barangay/{barangay_username}/{client}', 'SuperAdminController@readSeniorBarangay'); // read

$router->post('/admin/update', 'SuperAdminController@update'); // update

$router->post('/admin/delete', 'SuperAdminController@delete'); // delete

// establishment routes
$router->post('/establishment/create', 'EstablishmentController@create'); // create

$router->post('/establishment/create/product', 'EstablishmentController@createProduct'); // create

$router->get('/establishment/{establishment_username}/show/{client}', 'EstablishmentController@read'); // read

$router->get('/establishment/{establishment_username}/show/senior/{senior_username}/{client}', 'EstablishmentController@readSenior'); // read

$router->post('/establishment/update', 'EstablishmentController@update'); // update

$router->post('/establishment/update/product', 'EstablishmentController@updateProduct'); // update

$router->post('/establishment/delete', 'EstablishmentController@delete'); // delete

$router->post('/establishment/delete/product', 'EstablishmentController@deleteProduct'); // delete

// town routes
$router->post('/town/create', 'TownController@create'); // create

$router->get('/town/{town_username}/show/{client}', 'TownController@read'); // read

$router->get('/town/{town_username}/show/barangay/{barangay_username}/senior', 'TownController@readSenior'); // read

$router->post('/town/update', 'TownController@update'); // update

$router->post('/town/delete', 'TownController@delete'); // delete

// barangay routes
$router->post('/barangay/create', 'BarangayController@create'); //create

$router->get('/barangay/{barangay_username}/show/{client}', 'BarangayController@read'); //read

$router->get('/barangay/{barangay_username}/show/senior/{senior_username}/{client}', 'BarangayController@readTransaction'); //read

$router->post('/barangay/update', 'BarangayController@update'); //udpate

$router->post('/barangay/delete', 'BarangayController@delete'); //delete

// senior routes
$router->get('/senior/{senior_username}/show/{client}', 'SeniorController@read'); //read

$router->post('/senior/update', 'SeniorController@update'); //update