<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobListController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\JobApplicationController;
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

// Password reset routes
Route::post('password/request-otp', [PasswordResetController::class, 'requestOtp']);
Route::post('password/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('password/reset', [PasswordResetController::class, 'resetPassword']);

// Public job routes
Route::get('/jobs', [JobListController::class, 'getAllJobs']);
Route::get('/jobs/{id}', [JobListController::class, 'getJobDetails']);
Route::get('/job-stats', [JobListController::class, 'getJobStats']);

// Public profile route - view any user's profile
Route::get('/profile/{id}', [UserProfileController::class, 'getProfile']);



//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //user logout
    Route::get('/user/logout/{id}', [AuthController::class, 'logout']);
    
    // User profile routes
    Route::get('/my-profile', [UserProfileController::class, 'getMyProfile']);
    Route::post('/my-profile', [UserProfileController::class, 'updateProfile']);
    
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

    // Job Application routes
    Route::post('/applications/apply', [JobApplicationController::class, 'apply']);
    Route::get('/jobs/{jobId}/applications', [JobApplicationController::class, 'getJobApplications']);
    Route::get('/my-applications', [JobApplicationController::class, 'getMyApplications']);
    Route::put('/applications/{applicationId}', [JobApplicationController::class, 'updateStatus']);
    Route::get('/applications/{applicationId}/download/{fileType}', [JobApplicationController::class, 'downloadFile']);
});