<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'app' => config('app.name'),
            'version' => '0.1.0'
        ]);
    })->name('health');

    Route::post('/login', function () {
        return response()->json([
            'message' => 'Endpoint de login funcionando'
        ]);
    });

});