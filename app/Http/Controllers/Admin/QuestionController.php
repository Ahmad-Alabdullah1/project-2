<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
     {
         try{
             $questions = Question::all();
             return view('admin.questions.index', compact('questions'));
         }catch(\Exception $ex){
             flash()->error("There Is Something Wrong , Please Contact Technical Support");
             return back();
         }
     }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         try{
             return view('admin.questions.create');
         }catch(\Exception $ex){
             flash()->error("There Is Something Wrong , Please Contact Technical Support");
             return back();
         }
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         try {
             // Validate the incoming request data
             $validatedData = $request->validate([
                 'lesson_id' => 'required|integer',
                 'question' => 'required|string',
                 'option_1' => 'required|string',
                 'option_2' => 'required|string',
                 'option_3' => 'required|string',
                 'option_4' => 'required|string',
                 'correct_answer' => 'required|in:option_1,option_2,option_3,option_4',
             ]);

             // Create a new question instance
             $question = new Question();
             $question->lesson_id = $validatedData['lesson_id'];
             $question->question = $validatedData['question'];
             $question->option_1 = $validatedData['option_1'];
             $question->option_2 = $validatedData['option_2'];
             $question->option_3 = $validatedData['option_3'];
             $question->option_4 = $validatedData['option_4'];
             $question->correct_answer = $validatedData['correct_answer'];

             // Count the total number of questions for the lesson
             $totalQuestions = Question::where('lesson_id', $question->lesson_id)->count();
             $question->total_questions = $totalQuestions + 1;

             // Count the answered questions and update the total score if chosen_answer is correct
             $answeredQuestions = Question::where('lesson_id', $question->lesson_id)
                 ->whereNotNull('chosen_answer')->count();
             $question->answered_questions = $answeredQuestions;

             if ($question->chosen_answer === $question->correct_answer) {
                 $question->total_score = $answeredQuestions + 1;
             } else {
                 $question->total_score = $answeredQuestions;
             }

             // Save the question
             $question->save();

             // Optionally, you can return a response or redirect to a desired page
             flash()->success("Added Has Been Done");
             return back();
         } catch (\Exception $ex) {
             flash()->error("There is something wrong. Please contact technical support.");
             return back();
         }
     }


     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         //
     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         try{
             if((int)$id > 0){
                 $question = Question::findOrFail($id);
                 return view('admin.question.edit', compact('question'));
             }else{
                 flash()->error("There Is Something Wrong , Please Contact Technical Support");
                 return back();
             }
         }catch(\Exception $ex){
             return $ex;
             flash()->error("There Is Something Wrong , Please Contact Technical Support");
             return back();
         }
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {

         try {
             $validatedData = $request->validate([
                 'lesson_id' => 'required|integer',
                 'question' => 'required|string',
                 'option_1' => 'required|string',
                 'option_2' => 'required|string',
                 'option_3' => 'required|string',
                 'option_4' => 'required|string',
                 'correct_answer' => 'required|in:option_1,option_2,option_3,option_4',
             ]);

             // Find the question with the given ID
             $question = Question::findOrFail($id);

             // Update the question's properties based on the validated data
             $question->lesson_id = $validatedData['lesson_id'];
             $question->question = $validatedData['question'];
             $question->option_1 = $validatedData['option_1'];
             $question->option_2 = $validatedData['option_2'];
             $question->option_3 = $validatedData['option_3'];
             $question->option_4 = $validatedData['option_4'];
             $question->correct_answer = $validatedData['correct_answer'];

             // Count the total number of questions for the lesson
             $totalQuestions = Question::where('lesson_id', $question->lesson_id)
                 ->where('id', '!=', $question->id)->count();
             $question->total_questions = $totalQuestions + 1;

             // Count the answered questions and update the total score if chosen_answer is correct
             $answeredQuestions = Question::where('lesson_id', $question->lesson_id)
                 ->whereNotNull('chosen_answer')->count();
             $question->answered_questions = $answeredQuestions;

             if ($question->chosen_answer === $question->correct_answer) {
                 $question->total_score = $answeredQuestions + 1;
             } else {
                 $question->total_score = $answeredQuestions;
             }

             // Save the updated question
             $question->save();

             // Return a JSON response indicating the success of the question update
             flash()->success("Edited Has Been Done");
             return back();
         }catch(\Exception $ex){
             flash()->error("There Is Something Wrong , Please Contact Technical Support");
             return back();
         }
         // Validate the incoming request data

     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         try {
             $question=Question::destroy($id);
             flash()->success("Deleted Has Been Done");
             return back();
         }catch(\Exception $ex){
             flash()->error("There Is Somrthing Wrong , Please Contact Technical Support");
             return back();
         }
     }
}
