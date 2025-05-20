<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    // Submit a new job application
    public function apply(Request $request)
    {
        try {
            // Log the incoming request data
            \Log::info('Job Application Request:', [
                'all_data' => $request->all(),
                'files' => $request->allFiles(),
                'has_resume' => $request->hasFile('resume'),
                'has_cover_letter' => $request->hasFile('coverLetter'),
                'cover_letter_mime' => $request->hasFile('coverLetter') ? $request->file('coverLetter')->getMimeType() : null,
                'cover_letter_extension' => $request->hasFile('coverLetter') ? $request->file('coverLetter')->getClientOriginalExtension() : null
            ]);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'job_id' => 'required|exists:job_lists,id',
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'location' => 'required|string|max:255',
                'summary' => 'required|string',
                'skills' => 'required|string',
                'portfolio' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'github' => 'nullable|url|max:255',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'coverLetter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ], [
                'resume.required' => 'Please upload your resume',
                'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file',
                'resume.max' => 'Resume file size must be less than 5MB',
                'coverLetter.mimes' => 'Cover letter must be a PDF, DOC, or DOCX file',
                'coverLetter.max' => 'Cover letter file size must be less than 5MB'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', [
                    'errors' => $validator->errors()->toArray(),
                    'cover_letter_mime' => $request->hasFile('coverLetter') ? $request->file('coverLetter')->getMimeType() : null,
                    'cover_letter_extension' => $request->hasFile('coverLetter') ? $request->file('coverLetter')->getClientOriginalExtension() : null
                ]);
                
                return response()->json([
                    'error' => true,
                    'reason' => 'Validation failed',
                    'response' => $validator->errors(),
                    'debug_info' => [
                        'has_resume' => $request->hasFile('resume'),
                        'has_cover_letter' => $request->hasFile('coverLetter'),
                        'all_files' => $request->allFiles(),
                        'cover_letter_mime' => $request->hasFile('coverLetter') ? $request->file('coverLetter')->getMimeType() : null,
                        'cover_letter_extension' => $request->hasFile('coverLetter') ? $request->file('coverLetter')->getClientOriginalExtension() : null
                    ]
                ], 422);
            }

            // Check if user has already applied
            $existingApplication = JobApplication::where('job_id', $request->job_id)
                ->where('user_id', $request->user()->id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'error' => true,
                    'reason' => 'You have already applied for this job'
                ], 400);
            }

            // Handle resume upload
            $resumeFile = $request->file('resume');
            $resumeFileName = time() . '_' . $resumeFile->getClientOriginalName();
            $resumePath = 'resumes/' . $resumeFileName;
            
            // Ensure directory exists with proper permissions
            $resumeDir = base_path('public/resumes');
            if (!file_exists($resumeDir)) {
                mkdir($resumeDir, 0755, true);
            }
            
            // Move file to public directory
            $resumeFile->move($resumeDir, $resumeFileName);
            
            \Log::info('Resume uploaded:', [
                'filename' => $resumeFileName,
                'path' => $resumePath,
                'absolute_path' => base_path('public/' . $resumePath),
                'directory_exists' => file_exists($resumeDir),
                'directory_writable' => is_writable($resumeDir),
                'file_exists' => file_exists(base_path('public/' . $resumePath)),
                'file_writable' => is_writable(base_path('public/' . $resumePath)),
                'base_path' => base_path(),
                'public_path' => public_path()
            ]);

            // Handle cover letter upload if provided
            $coverLetterPath = null;
            if ($request->hasFile('coverLetter')) {
                $coverLetterFile = $request->file('coverLetter');
                $coverLetterFileName = time() . '_' . $coverLetterFile->getClientOriginalName();
                $coverLetterPath = 'cover-letters/' . $coverLetterFileName;
                
                // Ensure directory exists with proper permissions
                $coverLetterDir = base_path('public/cover-letters');
                if (!file_exists($coverLetterDir)) {
                    mkdir($coverLetterDir, 0755, true);
                }
                
                // Move file to public directory
                $coverLetterFile->move($coverLetterDir, $coverLetterFileName);
                
                \Log::info('Cover letter uploaded:', [
                    'filename' => $coverLetterFileName,
                    'path' => $coverLetterPath,
                    'absolute_path' => base_path('public/' . $coverLetterPath),
                    'directory_exists' => file_exists($coverLetterDir),
                    'directory_writable' => is_writable($coverLetterDir),
                    'file_exists' => file_exists(base_path('public/' . $coverLetterPath)),
                    'file_writable' => is_writable(base_path('public/' . $coverLetterPath)),
                    'base_path' => base_path(),
                    'public_path' => public_path()
                ]);
            }

            // Create the application
            $application = JobApplication::create([
                'job_id' => $request->job_id,
                'user_id' => $request->user()->id,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'location' => $request->location,
                'summary' => $request->summary,
                'skills' => $request->skills,
                'portfolio' => $request->portfolio,
                'linkedin' => $request->linkedin,
                'github' => $request->github,
                'resume' => $resumePath,
                'cover_letter' => $coverLetterPath,
                'status' => 'pending'
            ]);

            return response()->json([
                'error' => false,
                'reason' => 'Application submitted successfully',
                'response' => $application
            ]);

        } catch (\Exception $e) {
            \Log::error('Application submission error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => true,
                'reason' => 'Failed to submit application: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get applications for a specific job (for job poster)
    public function getJobApplications($jobId)
    {
        try {
            $job = JobList::findOrFail($jobId);

            // Check if the authenticated user is the job poster
            if ($job->user_id !== auth()->id()) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Unauthorized. You do not own this job.'
                ], 403);
            }

            $applications = JobApplication::with('user')
                ->where('job_id', $jobId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'error' => false,
                'reason' => 'Applications fetched successfully',
                'response' => $applications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to fetch applications: ' . $e->getMessage()
            ]);
        }
    }

    // Get user's submitted applications
    public function getMyApplications(Request $request)
    {
        try {
            $applications = JobApplication::with('job')
                ->where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'error' => false,
                'reason' => 'Applications fetched successfully',
                'response' => $applications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to fetch applications: ' . $e->getMessage()
            ]);
        }
    }

    // Update application status
    public function updateStatus(Request $request, $applicationId)
    {
        try {
            $application = JobApplication::findOrFail($applicationId);
            $job = JobList::findOrFail($application->job_id);

            // Check if the authenticated user is the job poster
            if ($job->user_id !== auth()->id()) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Unauthorized. You do not own this job.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,reviewing,interview,accepted,rejected',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Validation failed',
                    'response' => $validator->errors()
                ]);
            }

            $application->update([
                'status' => $request->status,
                'notes' => $request->notes
            ]);

            return response()->json([
                'error' => false,
                'reason' => 'Application status updated successfully',
                'response' => $application
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to update application status: ' . $e->getMessage()
            ]);
        }
    }

    // Download resume or cover letter
    public function downloadFile($applicationId, $fileType)
    {
        try {
            $application = JobApplication::findOrFail($applicationId);
            $job = JobList::findOrFail($application->job_id);

            // Check if the authenticated user is either the applicant or the job poster
            if ($application->user_id !== auth()->id() && $job->user_id !== auth()->id()) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Unauthorized access'
                ], 403);
            }

            $filePath = $fileType === 'resume' ? $application->resume : $application->cover_letter;
            
            if (!$filePath) {
                return response()->json([
                    'error' => true,
                    'reason' => 'File not found'
                ], 404);
            }

            // Get the absolute path and check if file exists
            $absolutePath = base_path('public/' . $filePath);
            
            \Log::info('Attempting to access file:', [
                'file_path' => $filePath,
                'absolute_path' => $absolutePath,
                'file_exists' => file_exists($absolutePath),
                'is_readable' => is_readable($absolutePath),
                'base_path' => base_path(),
                'public_path' => public_path(),
                'storage_path' => storage_path(),
                'app_url' => config('app.url')
            ]);

            if (!file_exists($absolutePath)) {
                \Log::error('File not found at path:', [
                    'path' => $absolutePath,
                    'relative_path' => $filePath,
                    'application_id' => $applicationId,
                    'file_type' => $fileType,
                    'base_path' => base_path(),
                    'public_path' => public_path()
                ]);
                
                return response()->json([
                    'error' => true,
                    'reason' => 'File not found on server'
                ], 404);
            }

            // Get the full URL for the file from public directory
            $url = asset($filePath);

            \Log::info('File URL generated:', [
                'url' => $url,
                'path' => $filePath,
                'absolute_path' => $absolutePath,
                'app_url' => config('app.url')
            ]);

            return response()->json([
                'error' => false,
                'url' => $url
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in downloadFile:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'base_path' => base_path(),
                'public_path' => public_path()
            ]);
            
            return response()->json([
                'error' => true,
                'reason' => 'Failed to get file: ' . $e->getMessage()
            ]);
        }
    }
} 