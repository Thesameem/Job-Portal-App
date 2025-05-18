<?php

namespace App\Http\Controllers;

use App\Models\JobList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class JobListController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Get all jobs with optional search and sorting
    public function getAllJobs(Request $request)
    {
        try {
            $query = JobList::where('status', 'posted');

            // Handle search query
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('company_name', 'like', "%{$searchTerm}%")
                      ->orWhere('required_skills', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('location', 'like', "%{$searchTerm}%");
                });
            }

            // Handle sorting
            if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'salary':
                        $query->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(salary_range, "-", -1), "k", 1) AS UNSIGNED) DESC');
                        break;
                    case 'relevant':
                    default:
                        // For relevance sorting, we'll use created_at as default
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                // Default sort by newest
                $query->orderBy('created_at', 'desc');
            }

            // Get the jobs
            $jobs = $query->get();

            return response()->json([
                'error' => false,
                'reason' => 'Jobs fetched successfully',
                'response' => $jobs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to fetch jobs: ' . $e->getMessage(),
            ]);
        }
    }

    // Get details of a specific job
    public function getJobDetails($id)
    {
        try {
            $job = JobList::find($id);

            if (!$job) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Job not found'
                ]);
            }

            return response()->json([
                'error' => false,
                'reason' => 'Job details fetched successfully',
                'response' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to fetch job details: ' . $e->getMessage(),
            ]);
        }
    }

    // Get jobs posted by the authenticated user
    public function getUserJobs(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $jobs = JobList::where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();

            return response()->json([
                'error' => false,
                'reason' => 'User jobs fetched successfully',
                'response' => $jobs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to fetch user jobs: ' . $e->getMessage(),
            ]);
        }
    }

    // Get a specific job posted by the authenticated user
    public function getUserJob(Request $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $job = JobList::where('id', $id)
                         ->where('user_id', $userId)
                         ->first();

            if (!$job) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Job not found or you do not have permission to access it'
                ], 404);
            }

            return response()->json([
                'error' => false,
                'reason' => 'Job details fetched successfully',
                'response' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to fetch job details: ' . $e->getMessage(),
            ]);
        }
    }

    // Update an existing job
    public function updateJob(Request $request, $id)
    {
        try {
            $job = JobList::find($id);

            if (!$job) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Job not found'
                ]);
            }

            // Check if the user owns this job
            if ($job->user_id !== $request->user()->id) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Unauthorized. You do not own this job.'
                ], 403);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                // Company details
                'company_name' => 'sometimes|string|max:255',
                'company_logo' => 'sometimes|image|max:2048',
                'company_website' => 'sometimes|nullable|url',
                'company_size' => 'sometimes|string',

                // Job details
                'title' => 'sometimes|string|max:255',
                'type' => 'sometimes|in:full-time,part-time,contract,freelancer',
                'location' => 'sometimes|string|max:255',
                'salary_range' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'responsibilities' => 'sometimes|string',

                // Requirements
                'experience_level' => 'sometimes|in:entry,intermediate,senior,lead',
                'education_level' => 'sometimes|in:high-school,associate,bachelor,master,phd',
                'required_skills' => 'sometimes|string',
                'preferred_skills' => 'sometimes|nullable|string',

                // Additional info
                'benefits' => 'sometimes|nullable|string',
                'application_deadline' => 'sometimes|nullable|date',
                'contact_email' => 'sometimes|email',
                'status' => 'sometimes|in:draft,posted,closed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Invalid data input',
                    'response' => $validator->errors()
                ]);
            }

            // Handle logo update if provided
            if ($request->hasFile('company_logo')) {
                $logo = $request->file('company_logo');
                $logopath = time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('images'), $logopath);
                $job->company_logo = $logopath;
            }

            // Update the job with the validated data
            $job->fill($request->except('company_logo'));
            $job->save();

            return response()->json([
                'error' => false,
                'reason' => 'Job updated successfully',
                'response' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to update job: ' . $e->getMessage(),
            ]);
        }
    }

    // Delete a job
    public function deleteJob(Request $request, $id)
    {
        try {
            $job = JobList::find($id);

            if (!$job) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Job not found'
                ]);
            }

            // Check if the user owns this job
            if ($job->user_id !== $request->user()->id) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Unauthorized. You do not own this job.'
                ], 403);
            }

            // Delete the job
            $job->delete();

            return response()->json([
                'error' => false,
                'reason' => 'Job deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to delete job: ' . $e->getMessage(),
            ]);
        }
    }

     //add job in the list
     public function AddJob(Request $request){

        $UserID=$request->user()->id;
         //validation
         $Validate = Validator::make($request->all(),[
             //company details
            'company_name'   =>  'required|string|max:255',
            'company_logo'   =>  'nullable|file|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'company_website'=>  'nullable|url',
            'company_size'   =>  'required|string',

            //job details

            'title'      =>   'required|string|max:255',
            'type'       =>   'required|in:full-time,part-time,contract,freelancer',
            'location'   =>   'required|string|max:255',
            'salary_range'  => 'required|string|max:255',
            'description'   => 'required|string',
            'responsibilities'  => 'required|string',

            //requirements

            'experience_level'   => 'required|in:entry,intermediate,senior,lead',
            'education_level'    => 'required|in:high-school,associate,bachelor,master,phd',
            'required_skills'    => 'required|string',
            'preferred_skills'  =>  'nullable|string',

            //additional info

            'benefits'                  =>      'nullable|string',
            'application_deadline'      =>      'nullable|date',
            'contact_email'             =>      'required|email',
             // 'user_id' => 'required|integer|exists:users,id',
        ]);
       
         //validation
        if ($Validate->fails()){
            $Errors = $Validate->errors();
            
            // Log specific details about the company_logo validation issue
            if (isset($Errors['company_logo'])) {
                \Log::error('Company logo validation failed: ', [
                    'errors' => $Errors['company_logo'],
                    'has_file' => $request->hasFile('company_logo'),
                    'file_details' => $request->hasFile('company_logo') ? [
                        'name' => $request->file('company_logo')->getClientOriginalName(),
                        'size' => $request->file('company_logo')->getSize(),
                        'mime' => $request->file('company_logo')->getMimeType(),
                        'extension' => $request->file('company_logo')->getClientOriginalExtension(),
                    ] : 'No file uploaded'
                ]);
            }
            
            return response()->json([
                'error' => true,
                'reason'  =>'Invalid data input',
                'response'  =>$Errors,
            ]);

        }
        //logo handle if provided

        if ($request->hasFile('company_logo')) {
            try {
                $logo = $request->file('company_logo');
                $logopath = time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('images'), $logopath);
            } catch (\Exception $e) {
                \Log::error('Error saving company logo: ' . $e->getMessage());
                return response()->json([
                    'error' => true,
                    'reason' => 'Could not save company logo: ' . $e->getMessage(),
                ]);
            }
        } else {
            $logopath = null;
        }


        $JobList = JobList::create([
            'user_id'       => $UserID,
            'company_name'   => $request->company_name,
            'company_logo'   => $logopath,
            'company_website' => $request->company_website,
            'title'      => $request->title,
            'type'       => $request->type,
            'location'   => $request->location,
            'salary_range'  => $request->salary_range,
            'description'   => $request->description,
            'responsibilities'  => $request->responsibilities,
            'company_size'   => $request->company_size,
            'experience_level'   => $request->experience_level,
            'education_level'    => $request->education_level,
            'required_skills'    => $request->required_skills,
            'preferred_skills'  => $request->preferred_skills,
            'benefits'                  =>      $request->benefits,
            'application_deadline'      =>      $request->application_deadline,
            'contact_email'             =>      $request->contact_email,
            'status'                  =>      'posted', // Changed from 'draft' to 'posted' for immediate visibility
        ]);
               //return the new added job
        return response()->json([
            'error'   => false,
            'reason'  => 'Job Listed',
            'response' => $JobList
        ]);
     }
   
}
