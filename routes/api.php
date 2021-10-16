<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\RentalController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User
Route::group(["prefix" => "user", "as" => "user"], function () {
  Route::get("/list", [UserController::class, 'userList']);
  Route::post("/create", [UserController::class, 'createUser']);
  Route::post("/edit/{id}", [UserController::class, 'userEdit']);
  Route::post("/login", [UserController::class, 'userLogin']);
  Route::get("/detail/{email}", [UserController::class, 'userDetail']);
  Route::delete("/delete/{id}", [UserController::class, 'userDelete']);
});

// Facility
Route::group(["prefix" => "facility", "as" => "facility"], function () {
  Route::post("/create", [FacilitiesController::class, 'create']);
  Route::get("/list", [FacilitiesController::class, 'list']);
  Route::post("/edit/{id}", [FacilitiesController::class, 'edit']);
  Route::delete("/delete/{id}", [FacilitiesController::class, 'delete']);
});

// Rental
Route::group(["prefix" => "rental", "as" => "rental"], function () {
  Route::post("/create", [RentalController::class, 'create']);
  Route::get("/list", [RentalController::class, 'list']);
});