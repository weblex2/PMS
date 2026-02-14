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

// Reservierungen CRUD
Route::resource('reservations', App\Http\Controllers\ReservationController::class);

// PMS Original Routes
Route::get("/users", function () { return view("users.index"); });
Route::get("/invoices", function () { return view("invoices.index"); });
Route::get("/reports", function () { return view("reports.index"); });

require __DIR__ . "/jetstream.php";
