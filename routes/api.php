<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Users
//Route::group(['middleware' => [ 'json.response']], function () {
    Route::post('/login', [UserController::class,'login'])->name('login');
    Route::post('/register',[UserController::class,'register']);
    Route::post('/logout', [UserController::class,'logout']);
//});
Route::middleware('auth:api')->group(function () {
    Route::post('/todo', [TodoController::class,'create']);
    Route::get('/todo', [TodoController::class,'read']);
    Route::delete('/todo/{id}', [TodoController::class,'remove']);
	Route::put('/todo/{id}', [TodoController::class,'edit']);
});    