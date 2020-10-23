<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Student;
use DB, Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'email'      => 'required | email',
    		'password'   => 'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json(['status' => 'failed', 'validation_errors' => $validator->errors()]);
    	}

    	$student = Student::where('email', $request->email)->first();

    	if (!is_null($student)) {
    		if (Hash::check($request->password, $student['password'])) {
    			return response()->json(['status' => 200,'success' => true,'message' => "Logged in successfully"]);
    		} else {
    			return response()->json(['status' => 'failed', 'message' => 'Username or password invalid']);
    		}
    	} else {
    		return response()->json(['status' => 'failed', 'message' => 'No registeration found with this email.']);
    	}
    }
}
