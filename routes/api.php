<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('news')->group(function (){
    Route::get('search', [NewsController::class,'search']);
    Route::get('db/search', [NewsController::class,'dbSearch']);
});

Route::prefix('news/Guardian')->group(function (){
    Route::get('search', [NewsController::class,'search']);
    Route::get('db/search', [NewsController::class,'dbSearch']);
});

Route::prefix('news/NyTimes')->group(function (){
    Route::get('search', [NewsController::class,'search']);
    Route::get('db/search', [NewsController::class,'dbSearch']);
});

