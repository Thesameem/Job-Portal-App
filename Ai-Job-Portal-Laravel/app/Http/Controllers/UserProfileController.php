<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    /**
     * Format the profile for API response, including proper URLs for media
     */
    private function formatProfileResponse($user, $profile)
    {
        // Clone the profile to avoid modifying the original
        $profileData = $profile->toArray();
        
        // Fix any temporary paths that might be in the database
        if (!empty($profileData['profile_photo']) && 
            (str_contains($profileData['profile_photo'], 'tmp') || 
             str_contains($profileData['profile_photo'], 'php') ||
             str_contains($profileData['profile_photo'], 'C:') ||
             str_contains($profileData['profile_photo'], 'xampp'))) {
            
            // Log the bad path
            \Illuminate\Support\Facades\Log::warning('Found temporary path in profile photo', [
                'bad_path' => $profileData['profile_photo'],
                'user_id' => $user->id
            ]);
            
            // Clear the temporary path in the database
            $profile->profile_photo = null;
            $profile->save();
            
            // Return the corrected data
            $profileData['profile_photo'] = null;
        }
        
        // Include the full URL for profile photo to make it easier for frontend
        if (!empty($profileData['profile_photo'])) {
            // Get just the filename from the path
            $filename = basename($profileData['profile_photo']);
            
            // Add the full URL to the response
            $profileData['profile_photo_url'] = url('storage/profile-photos/' . $filename);
            
            \Illuminate\Support\Facades\Log::info('Profile photo path in response:', [
                'path' => $profileData['profile_photo'],
                'url' => $profileData['profile_photo_url']
            ]);
        }
        
        return [
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
            ],
            'profile' => $profileData,
        ];
    }
    
    /**
     * Get user profile by user id
     */
    public function getProfile($id)
    {
        $user = User::findOrFail($id);
        
        // Get or create profile if it doesn't exist
        $profile = $user->profile ?: $user->profile()->create();
        
        return response()->json([
            'error' => false,
            'response' => $this->formatProfileResponse($user, $profile)
        ]);
    }
    
    /**
     * Get current authenticated user's profile
     */
    public function getMyProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        // Get or create profile if it doesn't exist
        $profile = $user->profile ?: $user->profile()->create();
        
        return response()->json([
            'error' => false,
            'response' => $this->formatProfileResponse($user, $profile)
        ]);
    }
    
    /**
     * Update current authenticated user's profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        // Get or create profile if it doesn't exist
        $profile = $user->profile ?: $user->profile()->create();
        
        // Log the incoming request for debugging - don't log files, just input fields
        Log::info('Profile update request:', [
            'input_fields' => $request->except(['profile_photo']),
            'has_profile_photo' => $request->hasFile('profile_photo')
        ]);
        
        // Process JSON strings into arrays
        $this->processJsonFields($request);
        
        // Basic validation for profile data
        $validator = Validator::make($request->all(), [
            'professional_title' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|min:10',
            'about' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'frontend_skills' => 'nullable|string',
            'backend_skills' => 'nullable|string',
            'other_skills' => 'nullable|string',
            'work_experience' => 'nullable|array',
            'education' => 'nullable|array',
            'availability_status' => 'nullable|string|max:255',
            'preferred_work_type' => 'nullable|string|max:255',
            'notice_period' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'certifications' => 'nullable|array',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
        ]);
        
        if ($validator->fails()) {
            Log::error('Profile validation failed:', ['errors' => $validator->errors()]);
            return response()->json([
                'error' => true,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            try {
                $file = $request->file('profile_photo');
                
                // Log file details for debugging
                Log::info('Processing profile photo upload', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'temp_path' => $file->getPathname()
                ]);
                
                // Generate a clean filename with timestamp
                $extension = $file->getClientOriginalExtension();
                if (empty($extension)) {
                    $extension = 'jpg'; // Default extension
                }
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                // Delete old photo if exists
                if ($profile->profile_photo) {
                    try {
                        Storage::disk('public')->delete('profile-photos/' . $profile->profile_photo);
                    } catch (\Exception $e) {
                        // Just log the error but continue
                        Log::error('Failed to delete old photo: ' . $e->getMessage());
                    }
                }
                
                // Store the file in public storage
                $path = $file->storeAs('profile-photos', $filename, 'public');
                
                // Verify the file was actually stored
                if (Storage::disk('public')->exists('profile-photos/' . $filename)) {
                    // Set the filename directly on the profile model
                    $profile->profile_photo = $filename;
                    $profile->save();
                    
                    // Also update the request for completeness
                    $request->merge(['profile_photo' => $filename]);
                    
                    Log::info('Profile photo stored successfully', [
                        'filename' => $filename,
                        'url' => asset('storage/profile-photos/' . $filename),
                        'path' => $path
                    ]);
                } else {
                    throw new \Exception('File was not stored properly');
                }
            } catch (\Exception $e) {
                Log::error('Profile photo upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Don't let temporary path get saved to database
                $request->merge(['profile_photo' => null]);
            }
        }
        
        // Update user data if provided
        if ($request->has('fullname')) {
            $user->update([
                'fullname' => $request->input('fullname'),
                'phone_number' => $request->input('phone_number'),
            ]);
        }
        
        try {
            // Prepare the data for update - exclude the original file upload
            $updateData = $request->except(['fullname', 'email', 'profile_photo']);
            
            // If we processed a file upload successfully, add the filename to the update data
            if ($request->hasFile('profile_photo') && $profile->profile_photo) {
                $updateData['profile_photo'] = $profile->profile_photo;
            }
            
            // Log what we're trying to save
            Log::info('Attempting to update profile with data:', [
                'user_id' => $user->id,
                'profile_id' => $profile->id,
                'has_profile_photo' => isset($updateData['profile_photo']),
                'profile_photo_value' => $updateData['profile_photo'] ?? null,
                'update_data_keys' => array_keys($updateData)
            ]);
            
            // Update profile with validated data
            $profile->update($updateData);
            
            // Verify the update
            $freshProfile = $profile->fresh();
            Log::info('Profile updated, current profile_photo value:', [
                'profile_photo' => $freshProfile->profile_photo
            ]);
            
            return response()->json([
                'error' => false,
                'message' => 'Profile updated successfully',
                'response' => $this->formatProfileResponse($user, $freshProfile)
            ]);
        } catch (\Exception $e) {
            Log::error('Profile update error:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Process JSON string fields into arrays
     */
    private function processJsonFields(Request $request)
    {
        $jsonFields = ['work_experience', 'education', 'languages', 'certifications'];
        
        foreach ($jsonFields as $field) {
            if ($request->has($field) && is_string($request->input($field))) {
                try {
                    $data = json_decode($request->input($field), true);
                    if (is_array($data)) {
                        $request->merge([$field => $data]);
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing JSON field {$field}", ['exception' => $e->getMessage()]);
                }
            }
        }
    }
    
    /**
     * Test method to verify profile photo access
     */
    public function testProfilePhotos() 
    {
        try {
            // Check if storage is linked
            $storageLinked = file_exists(public_path('storage'));
            
            // List all profile photos
            $profilePhotos = Storage::disk('public')->files('profile-photos');
            
            return response()->json([
                'error' => false,
                'storage_linked' => $storageLinked,
                'photos_found' => count($profilePhotos),
                'photo_paths' => $profilePhotos,
                'storage_path' => storage_path('app/public/profile-photos'),
                'public_path' => public_path('storage/profile-photos'),
                'storage_url' => url('storage')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error testing profile photos: ' . $e->getMessage()
            ], 500);
        }
    }
} 