<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuperAdminController;

Route::get('/', [LoginController::class, 'index'])->name('index');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/testlogin', [LoginController::class, 'test_login'])->name('test_login');

Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');