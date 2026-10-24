<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/healthz', function () {
    $dbStatus = 'ok';
    try {
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'healthy',
        'app' => 'EduTour CSE703073',
        'database' => $dbStatus,
        'timestamp' => now()->toIso8601String(),
    ], str_contains($dbStatus, 'error') ? 500 : 200);
});