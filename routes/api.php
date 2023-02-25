<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/signup', [LoginController::class, 'signUp']);
Route::get('/tremendo', [LoginController::class, 'tremendo']);
Route::middleware('auth:api')->get('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:api')->get('/me', [LoginController::class, 'whoAmI']);
