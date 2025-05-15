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

     //add  job in the list
     public function AddJob(Request $request){

        $UserID=$request->user()->id;
         //validation
         $Validate = Validator::make($request->all(),[
             //company details
            'company_name'   =>  'required|string|max:255',
            'company_logo'   =>  'nullable|image|max:2048',
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
            return response()->json([
                'error' => true,
                'reason'  =>'Invalid data input',
                'response'  =>$Errors,
            ]);

        }
        //logo handle if provided

        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logopath = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $logopath);
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
            'status'                  =>      'draft',
        ]);
               //return the new added job
        return response()->json([
            'error'   => false,
            'reason'  => 'Job Listed',
            'response' => $JobList
        ]);


        //edit job
        
        $JobList = JobList::find($id);
        if (!$JobList) {
            return response()->json([
                'error' => true,
                'reason' => 'Job not found',
            ]);
        }
        $JobList->update($request->all());
        return response()->json([
            'error' => false,
            'reason' => 'Job updated successfully',
            'response' => $JobList
        ]);



        
     }
   
}
