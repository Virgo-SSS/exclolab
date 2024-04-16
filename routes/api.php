<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [loginController::class, 'logout'])->name("logout");
});

Route::post('login', [LoginController::class, 'index'])->name("login");
Route::post('register', [RegisterController::class, 'index'])->name('register');

