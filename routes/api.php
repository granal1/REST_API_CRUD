<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

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

/*
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
*/

Route::middleware(['auth_api'])->group(function () {
    Route::get('item/list', [ApiController::class, 'getAllItems']);
    Route::get('item/{id}', [ApiController::class, 'getItemById']);
    Route::post('item', [ApiController::class, 'addItem']);
    Route::put('item/{id}', [ApiController::class, 'editItem']);
    Route::delete('item/{id}', [ApiController::class, 'deleteItem']);
});
