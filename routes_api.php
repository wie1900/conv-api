<?php

use Conv\App\Controllers\ConvController;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/conv')->group(function () {
    Route::post('/words', [ConvController::class, 'words']);


});
