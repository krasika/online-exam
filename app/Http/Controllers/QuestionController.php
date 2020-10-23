<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
	Question,
	Answer
};
use Illuminate\Support\Facades\Validator;
use DB;

class QuestionController extends Controller
{
    /**
     * Create Question
     * @return Response
    **/
    public function createQuestion(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'question_text'  => 'required',
    		'options'        => 'required | array',
    		'options.*.text' => 'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(['status' => 'failed', 'validation_errors' => $validator->errors()]);
    	}

    	$questionArray = [
    		'question_text' => $request->question_text
    	];

    	$optionText = $request->options;

        DB::beginTransaction();

	    $question = Question::create($questionArray);
	    $insertOption = [];

	    foreach ($optionText as $key => $value) {
	    	$insertArr = [];
	    	$insertArr['question_id'] = $question['id'];
	    	$insertArr['answer_text'] = $value['text'];
	    	array_push($insertOption, $insertArr);
	    }
    	$option = Answer::insert($insertOption);
    	
	    if(!is_null($question)) {
	    	DB::commit();
            return response()->json(["status" => 200, "success" => true, "message" => "Question created successfully"]);
        } else {
        	DB::rollBack();
            return response()->json(["status" => "failed", "success" => false, "message" => "Something went wrong while creating question. Please try again."]);
        }
    }

    /**
     * List Question
     * @return Response
    **/
    public function questionsListing() {
        $questions = Question::with(['options' => function($query) {
        	$query->select('id','question_id','answer_text');
        }])->select('id','question_text')->get();
        if(count($questions) > 0) {
            return response()->json(["status" => 200, "success" => true, "count" => count($questions), "data" => $questions]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "No record found"]);
        }
    }

    /**
     * Get Student Details
     * @return Response
    **/
    public function questionDetail($id) {
        $question = Question::with(['options' => function($query) use($id) {
        	$query->where('question_id',$id)
        	      ->select('id','question_id','answer_text');
        }])->where('questions.id', $id)->select('id','question_text')->get();

        if(!is_null($question)) {
            return response()->json(["status" => 200, "success" => true, "data" => $question]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "No question found"]);
        }
    }
}
