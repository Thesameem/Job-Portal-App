<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobListController;
use App\Http\Controllers\UserProfileController;
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

// Public profile route - view any user's profile
Route::get('/profile/{id}', [UserProfileController::class, 'getProfile']);

// Profile photo diagnostic and fixing routes
Route::get('sync-profile-photos', function() {
    // Get all files in the storage directory
    $files = \Illuminate\Support\Facades\Storage::disk('public')->files('profile-photos');
    $syncedFiles = [];
    $errors = [];
    
    // Make sure the public directory exists
    if (!file_exists(public_path('storage/profile-photos'))) {
        mkdir(public_path('storage/profile-photos'), 0755, true);
    }
    
    // Copy each file to the public directory
    foreach ($files as $file) {
        try {
            $filename = basename($file);
            $sourcePath = storage_path('app/public/' . $file);
            $targetPath = public_path('storage/profile-photos/' . $filename);
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $targetPath);
                $syncedFiles[] = [
                    'filename' => $filename,
                    'source' => $sourcePath,
                    'target' => $targetPath,
                    'accessible_url' => url('storage/profile-photos/' . $filename)
                ];
            }
        } catch (\Exception $e) {
            $errors[] = [
                'file' => $file,
                'error' => $e->getMessage()
            ];
        }
    }
    
    return [
        'success' => count($errors) === 0,
        'total_files' => count($files),
        'synced_files' => count($syncedFiles),
        'files' => $syncedFiles,
        'errors' => $errors
    ];
});

// List all profiles with their photos for admin diagnostics
Route::get('list-profile-photos', function() {
    $profiles = \App\Models\UserProfile::with('user')->get();
    $results = [];
    
    foreach ($profiles as $profile) {
        $photoInfo = null;
        
        if ($profile->profile_photo) {
            $filename = $profile->profile_photo;
            $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists('profile-photos/' . $filename);
            
            $photoInfo = [
                'filename' => $filename,
                'exists' => $exists,
                'url' => url('storage/profile-photos/' . $filename),
                'file_path' => storage_path('app/public/profile-photos/' . $filename)
            ];
        }
        
        $results[] = [
            'profile_id' => $profile->id,
            'user_id' => $profile->user_id,
            'user_name' => $profile->user ? $profile->user->fullname : 'Unknown',
            'profile_photo' => $profile->profile_photo,
            'photo_info' => $photoInfo
        ];
    }
    
    return [
        'total_profiles' => count($profiles),
        'profiles_with_photos' => collect($results)->filter(function($item) {
            return !empty($item['profile_photo']);
        })->count(),
        'profiles' => $results
    ];
});

// Fix profiles with temp path
Route::get('fix-profile-temp-paths', function() {
    $profiles = \App\Models\UserProfile::whereNotNull('profile_photo')->get();
    $fixed = [];
    $problems = [];
    
    foreach ($profiles as $profile) {
        $photo = $profile->profile_photo;
        
        // Check if it's a temp path
        if (str_contains($photo, 'tmp') || str_contains($photo, 'php') || str_contains($photo, 'C:')) {
            $problems[] = [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'bad_path' => $photo
            ];
            
            // Clear the invalid path
            $profile->profile_photo = null;
            $profile->save();
        }
    }
    
    return [
        'total_profiles' => $profiles->count(),
        'fixed_profiles' => $fixed,
        'problem_profiles' => $problems
    ];
});

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
});