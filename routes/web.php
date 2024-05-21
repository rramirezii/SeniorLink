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

use Illuminate\Support\Facades\Route;

$router->get('/', 'LoginController@index');

$router->post('/login', 'LoginController@login');

$router->post('/validate', 'LoginController@validateLogin');

// admin routes;
Route::prefix('admin')->group(function () {
    Route::post('/create', 'SuperAdminController@create'); // create

    Route::prefix('{superadmin_username}/show')->group(function () {
        Route::get('/{client}', 'SuperAdminController@read'); // read

        Route::get('/town/{town_username}/{client}', 'SuperAdminController@readBarangay'); // read

        Route::get('/barangay/{barangay_username}/{client}', 'SuperAdminController@readSenior'); // read

        Route::get('/town/{town_username}/barangay/{barangay_username}/{client}', 'SuperAdminController@readSeniorBarangay'); // read
    });

    Route::post('/update', 'SuperAdminController@update'); // update

    Route::post('/delete', 'SuperAdminController@delete'); // delete
});

// establishment routes
Route::prefix('establishment')->group(function () {
    Route::post('/create', 'EstablishmentController@create'); // create
    Route::post('/create/product', 'EstablishmentController@createProduct'); // create

    Route::prefix('{establishment_username}/show')->group(function () {
        Route::get('/{client}', 'EstablishmentController@read'); // read
        Route::get('/senior/{senior_username}/{client}', 'EstablishmentController@readSenior'); // read
    });

    Route::post('/update', 'EstablishmentController@update'); // update
    Route::post('/update/product', 'EstablishmentController@updateProduct'); // update

    Route::post('/delete', 'EstablishmentController@delete'); // delete
    Route::post('/delete/product', 'EstablishmentController@deleteProduct'); // delete
});

// town routes
Route::prefix('town')->group(function () {
    Route::post('/create', 'TownController@create'); // create

    Route::prefix('{town_username}/show')->group(function () {
        Route::get('/{client}', 'TownController@read'); // read
        Route::get('/barangay/{barangay_username}/{client}', 'TownController@readSenior'); // read
    });

    Route::post('/update', 'TownController@update'); // update
    Route::post('/delete', 'TownController@delete'); // delete
});


// barangay routes
Route::prefix('barangay')->group(function () {
    Route::post('/create', 'BarangayController@create'); // create

    Route::get('/{barangay_username}/show/{client}', 'BarangayController@read'); // read

    Route::get('/{barangay_username}/show/senior/{senior_username}/{client}', 'BarangayController@readTransaction'); // read

    Route::post('/update', 'BarangayController@update'); // update

    Route::post('/delete', 'BarangayController@delete'); // delete
});

// senior routes
Route::prefix('senior')->group(function () {
    Route::get('/{senior_username}/show/{client}', 'SeniorController@read'); // read

    Route::post('/update', 'SeniorController@update'); // update
});