<?php


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("contact",ApiContactController::class);

Route::post("logout",[App\http\Controllers\ApiAuthController::class,"logout"])->middleware("auth:sanctum");

Route::post("register",[App\http\Controllers\ApiAuthController::class,"register"]);
Route::post("login",[App\http\Controllers\ApiAuthController::class,"login"]);