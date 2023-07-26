<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(["middleware" => "auth:sanctum"], function() {
    Route::get('parent', [ToDoController::class, "getAllParentToDo"]);
    Route::get('parent/by-token', [ToDoController::class, "getAllParentToDoByToken"]);
    Route::post('parent', [ToDoController::class, "createParentToDo"]);
    Route::get('parent/{id}', [ToDoController::class, "getParentToDoById"]);
    Route::patch('parent/{id}', [ToDoController::class, "updateParentToDo"]);
    Route::delete('parent/{id}', [ToDoController::class, "deleteParentToDo"]);

    Route::post('todo', [ToDoController::class, "createToDo"]);
    Route::get('todo', [ToDoController::class, "getAllToDo"]);
    Route::get('todo/by-token', [ToDoController::class, "getAllToDoByToken"]);
    Route::get('todo/{parentId}/all', [ToDoController::class, "getAllToDoByParentId"]);
    Route::get('todo/{id}', [ToDoController::class, "getToDoById"]);
    Route::patch("todo/{id}", [ToDoController::class, "updateToDo"]);
    Route::delete("todo/{id}", [ToDoController::class, "deleteToDo"]);

    Route::group(["prefix" => "user"], function() {
        Route::get("/", [UserController::class, "getUserByToken"]);
        Route::get("{id}", [UserController::class, "getUserById"]);
        Route::patch("/", [UserController::class, "updateUser"]);
    });
});

Route::post('register', [AuthController::class, 'createUser']);
Route::post('login', [AuthController::class, 'login']);
