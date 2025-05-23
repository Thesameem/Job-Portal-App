<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request){
        $Validate=Validator::make($request->all(),[
            'fullname'     => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'password'     =>  'required|string|',
            'phone_number' => 'required|string|min:10|unique:users,phone_number',
        ]);

        //validation
        if($Validate->fails()){
            $Errors=$Validate->errors();

            $ErrorMessage="";

            if($Errors->has('email')) $ErrorMessage='This  email  has already been taken.';
            elseif($Errors->has('phone_number')) $ErrorMessage='The phone number has already been taken.';
            else $ErrorMessage ='validation error';

            return response()->json([
                'error'  =>true,
                'reason'  => $ErrorMessage,
                'response'  =>null
            ]);
        }
        //register new user
        $User=User::create($request->all());

        //return the new user
        return response()->json([
            'error'   =>false,
            'reason' =>'listed',
            'response'   =>$User
        ]);
    }

    //Login
    public function login(Request $request){
        $Validate=Validator::make($request->all(),[
            'email'   => 'required|email',
            'password'=> 'required|string|min:8'
        ]);

        //validate
        if ($Validate->fails()){
            return response()->json([
                'error'   =>true,
                'reason'  =>'validation error',
                'response' =>null
            ]);
        }


            $User=User::where('email',$request->email)->first();
            if(!$User || !Hash::check($request->password,$User->password)){
                return response()->json([
                    'error'   =>true,
                    'reason'  =>"Email and password didnt match",
                    'response'  =>null
                ]);
            }
        //create token and return


        $token =$User->createToken('user')->plainTextToken;

        return response()->json([
            'error'   =>false,
            'reason'   =>'success',
            'response'  =>array(
                'user'    =>$User,
                'token'   =>$token
            )
        ]);
    }

    //logout

    public function logout (Request $request,$id){
        $request->user()->tokens()->delete();
        return response()->json([
            'error'  =>false,
            'reason'   =>'success',
            'response'   =>null
        ]);
    }
}
