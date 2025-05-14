<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//user register
Route::post('user/register',[AuthController::class,'register']);

//user login
Route::post('user/login',[AuthController::class,'login']);

//user logout

Route::group(['middleware'=>['auth:sanctum']],function(){

    //user logout
    Route::get('user/logout/{id}',[AuthController::class,'logout']);
    Route::post('/my-jobs',[JobListController::class,'AddJob']);

});