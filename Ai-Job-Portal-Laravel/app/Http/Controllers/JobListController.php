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
        ]);        
        
        //validation
        if ($Validate->fails()){
            $Errors = $Validate->errors();
            return response()->json([
                'error' => true,
                'reason'  =>'Invalid data input',
                'response'  =>$Errors,
            ]);


        //logo handle if provided

        if($request->hasFile('company_logo')){
            $logopath = $request->file('company_logo')->store('company_logos','public');
        }


        $JobList=JobList:: create($request->all());

        //return the new added job
        return response()->json([
            'error'   =>false,
            'reason'  =>'Job Listed',
            'response'=>$JobList
        ]);

        }
     }
   
}
