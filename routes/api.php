<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/preferences', [UserPreferenceController::class, 'index']);
    Route::post('/user/preferences', [UserPreferenceController::class, 'store']);
    Route::get('/feed', [FeedController::class, 'index']);
});
