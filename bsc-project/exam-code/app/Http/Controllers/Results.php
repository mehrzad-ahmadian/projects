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
use App\Correction;

use Carbon\Carbon;

class Results extends Controller
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

        $records = $query->where('start_time', '<', Carbon::now())->where('end_time', '<', Carbon::now())->has('questions')->get();
        return view('results.index' , ['records' => $records]);
    }

    public function automaticcorrection($id) {
        $exam = Model::find($id);
        if (is_null($exam)) {
            echo "آزمون یافت نشد.";
            die();
        }

        $submissions = $exam->submissions;

        foreach ($submissions as $submission) {
            $correction = Correction::where('submission_id', $submission->id)->first();
            if (is_null($correction)){
                $correction = new Correction();
                $correction->creator_id = Auth::user()->id;
                $correction->submission_id = $submission->id;
            }

            $automaticTotalCorrect = 0;
            $automaticTotalUncorrect = 0;
            $automaticTotalUnanswered = 0;
            $automaticScore = 0;
            $automaticScoreByPercentage = 0;
            $questions = $exam->questions()->whereIn('type', ['multiple_choice', 'true_false'])->get();

            foreach ($questions as $question) {
                $submissions = json_decode($submission->submissions, true);
                $userAnswer = $submissions[$question->id];

                if (empty($userAnswer)) {
                    $automaticTotalUnanswered++;
                } else {
                    switch ($question->type) {
                        case 'multiple_choice':
                            $questionPoints = $exam->getQuestionPoints($question->id);

                            if ($userAnswer == $question->multiple_choice_correct_answer) {
                                $automaticTotalCorrect++;
                                $automaticScore = $automaticScore + $questionPoints;
                            } else {
                                $automaticTotalUncorrect++;
                            }
                            break;

                        case 'true_false':
                            $questionPoints = $exam->getQuestionPoints($question->id);

                            if ($userAnswer == $question->true_false_correct_answer) {
                                $automaticTotalCorrect++;
                                $automaticScore = $automaticScore + $questionPoints;
                            } else {
                                $automaticTotalUncorrect++;
                            }
                            break;
                    }
                }
            }

            if (! empty($automaticScore)) {
                $automaticScoreByPercentage = ($automaticScore * 100) / $exam->total_score;
            }

            $correction->automatic_total_correct = $automaticTotalCorrect;
            $correction->automatic_total_uncorrect = $automaticTotalUncorrect;
            $correction->automatic_total_unanswered = $automaticTotalUnanswered;
            $correction->automatic_score = $automaticScore;
            $correction->automatic_score_by_percentage = $automaticScoreByPercentage;
            if ($correction->save()) {
                $submission->automatic_correction_done = 1;
                $submission->save();
            }
        }
        if (( Model::hasAnyQuestionByType($exam->id, ['multiple_choice', 'true_false']) && (! Model::hasAutomaticUncorrectedSubmission($exam->id)) ) || (Model::hasAnyQuestionByType($exam->id, ['text']) && (! Model::hasManualUncorrectedSubmission($exam->id)) )) {
            Model::calculateTotalScore($exam->id);
        }

        return redirect('/results');
    }

    public function manualCorrection($id){
        $exam = Model::find($id);
        $questions = $exam->questions()->where('type', 'text')->get();
        return view('results.manual_correction_form', ['exam' => $exam, 'questions' => $questions]);
    }

    public function saveManualCorrection(Request $request, $id){
        $exam = Model::find($id);
        $submissions = $exam->submissions;

        foreach ($submissions as $submission) {
            $correction = $submission->correction;
            if (is_null($correction)){
                $correction = new Correction();
                $correction->creator_id = Auth::user()->id;
                $correction->submission_id = $submission->id;
            }

            $manualScore = 0;
            $manualScoreByPercentage = 0;
            $negativeAveragePoints = 0;
            $manualCorrectionData = [];
            $questions = $exam->questions()->whereIn('type', ['text'])->get();
            foreach ($questions as $question) {
                $string = 'submission' . $submission->id . '_' . $question->id;
                $userAnswerPoints = $request->{$string};
                $questionPoints = $exam->getQuestionPoints($question->id);

                if (empty($userAnswerPoints)) {
                    $userAnswerPoints = 0;
                } elseif ($userAnswerPoints > $questionPoints){
                    $userAnswerPoints = $questionPoints;
                }
                $manualCorrectionData[$question->id] = $userAnswerPoints;

                $manualScore = $manualScore + $userAnswerPoints;
            }

            if (! empty($manualScore)) {
                $manualScoreByPercentage = ($manualScore * 100) / $exam->total_score;
            }

            $correction->manual_correction = json_encode($manualCorrectionData);
            $correction->manual_score = $manualScore;
            $correction->manual_score_by_percentage = $manualScoreByPercentage;
            if ($correction->save()) {
                $submission->manual_correction_done = 1;
                $submission->save();
            }
        }
        if (( Model::hasAnyQuestionByType($exam->id, ['multiple_choice', 'true_false']) && (! Model::hasAutomaticUncorrectedSubmission($exam->id)) ) || ( Model::hasAnyQuestionByType($exam->id, ['text']) && (! Model::hasManualUncorrectedSubmission($exam->id)) )) {
            Model::calculateTotalScore($exam->id);
        }

        return redirect('/results/manualcorrection/' . $id);
    }

    public function allresults($id){
        $exam = Model::find($id);
        if (\Auth::user()->group == 'learner') {
            $submission = $exam->submissions()->where('user_id', \Auth::user()->id)->first();
            $corrections = $exam->corrections()->where('submission_id', $submission->id)->get();
        } else {
            $corrections = $exam->corrections;
        }
        return view('results.allresults', ['exam' => $exam, 'corrections' => $corrections]);
    }

    public function answersheet($id){
        $exam = Model::find($id);
        if (is_null($exam))
            \App::abort(404);

        $questions = $exam->questions;
        return view('results.answersheet', ['exam' => $exam, 'questions' => $questions, 'questionCounter' => 1]);
    }
}
