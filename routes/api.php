<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\TaskController;

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

Route::post('register',[APIController::class,'register']);
Route::post('login',[APIController::class,'login']);


Route::get('tasks',[TaskController::class,'index']);
Route::get('tasks/{id}',[TaskController::class,'show']);


Route::group(['middleware'=>'auth.jwt'],function(){

    Route::post('logout',[APIController::class,'logout']);
    Route::post('tasks',[TaskController::class,'store']);
    Route::delete('tasks/{id}',[TaskController::class,'destroy']);
    Route::put('tasks/{id}',[TaskController::class,'update']);


});