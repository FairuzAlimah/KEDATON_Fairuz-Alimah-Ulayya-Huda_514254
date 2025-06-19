<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\KedatonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PolylinesController;

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('points', PointsController::class);
Route::resource('polylines', PolylinesController::class);
Route::resource('polygons', PolygonsController::class);

Route::get('/map', [PointsController::class, 'index'])->middleware(['auth', 'verified'])->name('map');
//Route::get('/map', [PolylinesController::class, 'index'])->middleware(['auth', 'verified'])->name('map');
//Route::get('/map', [PolygonsController::class, 'index'])->middleware(['auth', 'verified'])->name('map');
Route::get('/table', [TableController::class, 'index'])->name('table');
Route::get('/kedaton', [KedatonController::class, 'index'])->name('kedaton');



Route::get('/proxy-geoserver', function () {
    $response = Http::withHeaders([
        'Accept' => 'application/json'
    ])->get('http://localhost:8080/geoserver/responsi_pgwl/ows', [
        'service' => 'WFS',
        'version' => '1.0.0',
        'request' => 'GetFeature',
        'typeName' => 'responsi_pgwl:area_balam',
        'outputFormat' => 'application/json'
    ]);

    return response($response->body())->header('Content-Type', 'application/json');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');




require __DIR__.'/auth.php';
