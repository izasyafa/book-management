<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function(){
    Route::get('/books', [BookApiController::class,'index']);
    Route::post('/books', [BookApiController::class,'store']);
    Route::get('/books/{id}', [BookApiController::class,'show']);
    Route::put('/books/{id}', [BookApiController::class,'update']);
    Route::delete('/books/{id}', [BookApiController::class,'destroy']);
    Route::get('/getByCategory', [BookApiController::class,'getByCategory']);
    Route::get('/getByPublisher', [BookApiController::class,'getByPublisher']);
});
