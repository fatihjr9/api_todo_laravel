<?php

use App\Http\Controllers\ChecklistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});
// Auth
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"])->middleware("web");
// App
Route::middleware("web")->group(function () {
    Route::get("checklist", [ChecklistController::class, "index"]);
    Route::post("checklist/", [ChecklistController::class, "addChecklist"]);
    Route::delete("checklist/{id}", [
        ChecklistController::class,
        "rmChecklist",
    ]);
    // detail
    Route::get("/checklist/{checklistId}/item", [
        ChecklistController::class,
        "getAllItems",
    ]);
    Route::post("/checklist/{checklistId}/item", [
        ChecklistController::class,
        "createItem",
    ]);
    Route::get("/checklist/{checklistId}/item/{itemId}", [
        ChecklistController::class,
        "getItem",
    ]);
    Route::put("/checklist/{checklistId}/item/{itemId}", [
        ChecklistController::class,
        "updateStatus",
    ]);
    Route::delete("/checklist/{checklistId}/item/{itemId}", [
        ChecklistController::class,
        "deleteItem",
    ]);
    Route::put("/checklist/{checklistId}/item/rename/{itemId}", [
        ChecklistController::class,
        "renameItem",
    ]);
});
