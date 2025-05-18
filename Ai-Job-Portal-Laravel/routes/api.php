<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Public routes
//user register
Route::post('user/register',[AuthController::class,'register']);

//user login
Route::post('user/login',[AuthController::class,'login']);

// Public job routes
Route::get('/jobs', [JobListController::class, 'getAllJobs']);
Route::get('/jobs/{id}', [JobListController::class, 'getJobDetails']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //user logout
    Route::get('/user/logout/{id}', [AuthController::class, 'logout']);
    //add job
    Route::post('/my-jobs', [JobListController::class, 'AddJob']);
    //get all jobs
    Route::get('/my-jobs', [JobListController::class, 'getUserJobs']);
    //get job by id
    Route::get('/my-jobs/{id}', [JobListController::class, 'getUserJob']);
    //update job    
    Route::put('/my-jobs/{id}', [JobListController::class, 'updateJob']);
    //delete job
    Route::delete('/my-jobs/{id}', [JobListController::class, 'deleteJob']);
});