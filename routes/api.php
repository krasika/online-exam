<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//login API
Route::post("login","AuthController@login");

//Students Route
Route::post("create-student","StudentController@createStudent");
Route::get("students", "StudentController@studentsListing");
Route::get("student/{id}", "StudentController@studentDetail");

//Questions Route
Route::post("create-question","QuestionController@createQuestion");
Route::get("questions", "QuestionController@questionsListing");
Route::get("question/{id}", "QuestionController@questionDetail");