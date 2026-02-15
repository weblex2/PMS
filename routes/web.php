<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get("/", function () {
    return view("dashboard");
})->name("dashboard");

Route::get("/gaeste", function () {
    return view("gaeste");
})->name("gaeste");

Route::get("/zimmer", function () {
    return view("zimmer");
})->name("zimmer");

Route::get("/checkin", function () {
    return view("checkin");
})->name("checkin");

Route::get("/umsatz", function () {
    return view("umsatz");
})->name("umsatz");

// Stammdaten - Zimmer CRUD
Route::resource('rooms', App\Http\Controllers\RoomController::class);

// Stammdaten - Gaeste CRUD
Route::resource('guests', App\Http\Controllers\GuestController::class);

// Stammdaten - Artikel CRUD
Route::resource('articles', App\Http\Controllers\ArticleController::class);

// Stammdaten - Preise CRUD
Route::resource('prices', App\Http\Controllers\PriceController::class);

// Stammdaten - Zimmerarten CRUD
Route::resource('room-types', App\Http\Controllers\RoomTypeController::class);

// Reservierungen CRUD
Route::resource('reservations', App\Http\Controllers\ReservationController::class);

// PMS Original Routes
Route::get("/users", function () { return view("users.index"); });
Route::get("/invoices", function () { return view("invoices.index"); });
Route::get("/reports", function () { return view("reports.index"); });

// Firms CRUD
Route::resource('firms', App\Http\Controllers\FirmController::class);
Route::post('/firms/{id}/switch', [App\Http\Controllers\FirmController::class, 'switch'])->name('firms.switch');

// Settings CRUD
Route::resource('settings', App\Http\Controllers\SettingController::class)->only(['index', 'store', 'destroy']);

require __DIR__ . "/jetstream.php";
