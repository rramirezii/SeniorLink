<?php

// use App\Http\Controllers\LoginController;

// Route::get('/', [LoginController::class, 'index'])->name('index');

// Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route::post('/testlogin', [LoginController::class, 'test_login'])->name('test_login');

Route::get('/', function () {
    return view('welcome');
});