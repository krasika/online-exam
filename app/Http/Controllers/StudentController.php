<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Student;
use DB, Hash;

class StudentController extends Controller
{
    /**
     * Create Student
     * @return Response
    **/
    public function createStudent(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'first_name' => 'required',
    		'last_name'  => 'required',
    		'password'   => 'required',
    		'email'      => 'required | email | unique:students,email',
    		'phone'      => 'nullable | numeric | unique:students,phone',
    	]);

    	if ($validator->fails()) {
    		return response()->json(['status' => 'failed', 'validation_errors' => $validator->errors()]);
    	}
    	$studentId      =       $request->id;
        $studentArray    =       array(
            "first_name" =>      $request->first_name,
            "last_name"  =>      $request->last_name,
            "email"      =>      $request->email,
            "phone"      =>      $request->phone,
            "password"   =>      Hash::make($request->password),
        );

        DB::beginTransaction();

        //In case if updating student details
        if (!is_null($studentId)) {
        	$student = Student::find($studentId);

        	//if student data is found
        	if (!is_null($student)) {
        		$updated_status = Student::where("id", $studentId)->update($studentArray);
                if ($updated_status == 1) {
                	DB::commit();
                    return response()->json(["status" => 200, "success" => true, "message" => "student detail updated successfully"]);
                } else {
                	DB::rollBack();
                    return response()->json(["status" => "failed", "message" => "Something went wrong while updating student. Please try again."]);
                } 
    	    } 
        } else {
    	    $student = Student::create($studentArray);
    	    if(!is_null($student)) {
    	    	DB::commit();
                return response()->json(["status" => 200, "success" => true, "message" => "Student record created successfully", "data" => $student]);
            } else {
            	DB::rollBack();
                return response()->json(["status" => "failed", "success" => false, "message" => "Something went wrong while creating student. Please try again."]);
            }
    	}
    }

    /**
     * List Student
     * @return Response
    **/
    public function studentsListing() {
        $students = Student::all();
        if(count($students) > 0) {
            return response()->json(["status" => 200, "success" => true, "count" => count($students), "data" => $students]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "No record found"]);
        }
    }

    /**
     * Get Student Details
     * @return Response
    **/
    public function studentDetail($id) {
        $student = Student::find($id);
        if(!is_null($student)) {
            return response()->json(["status" => 200, "success" => true, "data" => $student]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "No student found"]);
        }
    }
}
