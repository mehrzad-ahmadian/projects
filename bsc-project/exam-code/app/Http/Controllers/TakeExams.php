<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
use Auth;

use App\Exam as Model;
use App\Category;
use App\BankQuestion;
use App\Question;
use App\Submission;

use App\JDate;
use Carbon\Carbon;

class TakeExams extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        $query = new Model;

        if (isset($_POST['search_keyword'])){
            $searchKeyword = $_POST['search_keyword'];
            $query = $query->where('name' , 'like' , '%' . $searchKeyword . '%');
        }

        $records = $query->where('start_time', '<=', Carbon::now())->where('end_time', '>=', Carbon::now())->get();
        return view('takeexams.index' , ['records' => $records]);
    }

    public function generateSubmission($id) {
        $exam = Model::find($id);
        if (is_null($exam)) {
            echo "آزمون یافت نشد.";
            die();
        }

        $submission = Submission::where('exam_id', $id)->where('user_id', Auth::user()->id)->first();
        if (is_null($submission)) {
            $submission = new Submission();
            $submission->user_id = Auth::user()->id;
            $submission->exam_id = $id;
            $submission->start_time = Carbon::now();
            $submission->end_time = Carbon::now()->addMinutes($exam->duration);
            $submission->save();
        }

        return redirect('/takeexams/take/' . $submission->id);
    }

    public function take($submissionId){
        $submission = Submission::find($submissionId);
        if (is_null($submission)) {
            echo "ثبت پاسخ‌ها یافت نشد.";
        }

        $exam = $submission->exam;
        if (is_null($exam)) {
            echo "آزمون یافت نشد.";
        }

        $remainingTime = Carbon::now()->diffInSeconds($submission->end_time, false);

        $questions = $exam->questions;
        return view('takeexams.take' , ['submission' => $submission, 'submissions' => json_decode($submission->submissions, true), 'remainingTime' => $remainingTime, 'exam' => $exam, 'questions' => $questions, 'questionCounter' => 1]);
    }

    public function saveSubmission(Request $request, $submissionId){
        // dd($request->all());
        // echo $request->exam_question[12];
        // die();

        $submission = Submission::find($submissionId);
        if (is_null($submission)) {
            echo "ثبت پاسخ‌ها یافت نشد.";
        }

        $exam = $submission->exam;
        if (is_null($exam)) {
            echo "آزمون یافت نشد.";
        }

        $questions = $exam->questions;

        // Proccessing the submissions
        $submissions = [];
        foreach ($questions as $question) {
            if (array_key_exists($question->id, $request->exam_question)) {
                $userAnswer = $request->exam_question[$question->id];
            } else {
                $userAnswer = null;
            }

            if (empty($userAnswer)) {
                $userAnswer = null;
            }
            $submissions[$question->id] = $userAnswer;
        }

        $submission->submissions = json_encode($submissions);

        if ($submission->save()) {
            return redirect('/takeexams/take/' . $submissionId);
        }
    }

    // public function show($id){
    //     //
    // }

    public function edit($id){
        $record = Model::find($id);
        $record->start_time = JDate::parse($record->start_time)->toDateTimeString();
        $record->end_time = JDate::parse($record->end_time)->toDateTimeString();

        $categories = Category::getNameList();
        return view('takeexams.form' , ['record' => $record, 'categories' => $categories]);
    }

    public function update(Request $request, $id){
        var_dump($request);

        $record = Model::find($id);

        $this->validate($request, [
            'name' => 'required',
            'total_score' => 'required',
            'duration' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $record->name = $request->name;
        $record->category_id = $request->category_id;
        $record->total_score = $request->total_score;
        $record->start_time = JDate::j2g($request->start_time, 'datetime');
        $record->end_time = JDate::j2g($request->end_time, 'datetime');
        $record->duration = $request->duration;
        $record->enabled = 0;
        $record->save();

        return Redirect::refresh();
    }

    public function destroy($id){
        $record = Model::find($id);
        $record->delete();

        return redirect('/exams');
    }




    public function import($id){
        $bankQuestions = BankQuestion::getAllowedBankQuestions();
        return view('takeexams.import' , ['bankQuestions' => $bankQuestions]);
    }

    public function saveImport(Request $request, $id){
        $bankQuestions = BankQuestion::whereIn('id', $request->only(['bankquestionIds']))->get();
        foreach ($bankQuestions as $bankQuestion) {
            $question = new Question();
            $question->creator_id = $request->user()->id;
            $question->exam_id = $id;
            $question->type = $bankQuestion->type;
            $question->title = $bankQuestion->title;
            $question->difficulty_level_id = $bankQuestion->difficulty_level_id;
            $question->choice_one = $bankQuestion->choice_one;
            $question->choice_two = $bankQuestion->choice_two;
            $question->choice_three = $bankQuestion->choice_three;
            $question->choice_four = $bankQuestion->choice_four;
            $question->multiple_choice_correct_answer = $bankQuestion->multiple_choice_correct_answer;
            $question->true_false_correct_answer = $bankQuestion->true_false_correct_answer;
            $question->text_correct_answer = $bankQuestion->text_correct_answer;
            $question->save();
        }

        return redirect('/exams');
    }
}
