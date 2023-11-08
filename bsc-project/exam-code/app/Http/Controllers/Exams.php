<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;

use App\Exam as Model;
use App\Category;
use App\BankQuestion;
use App\Question;

use App\JDate;

class Exams extends Controller
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

        $records = $query->get();
        return view('exams.index' , ['records' => $records]);
    }

    public function create(){
        $categories = Category::getNameList();
        return view('exams.form' , ['categories' => $categories]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'total_score' => 'required',
            'duration' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $record = Model::create([
            'creator_id' => $request->user()->id,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total_score' => $request->total_score,
            'start_time' => JDate::j2g($request->start_time, 'datetime'),
            'end_time' => JDate::j2g($request->end_time, 'datetime'),
            'duration' => $request->duration,
            'enabled' => 0,
        ]);

        return redirect('/exams/edit/' . $record->id);
    }

    // public function show($id){
    //     //
    // }

    public function edit($id){
        $record = Model::find($id);
        $record->start_time = JDate::parse($record->start_time)->toDateTimeString();
        $record->end_time = JDate::parse($record->end_time)->toDateTimeString();

        $categories = Category::getNameList();
        return view('exams.form' , ['record' => $record, 'categories' => $categories]);
    }

    public function update(Request $request, $id){
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
        return view('exams.import' , ['bankQuestions' => $bankQuestions]);
    }

    public function saveImport(Request $request, $id){
        $bankQuestions = BankQuestion::whereIn('id', $request->bankquestionIds)->get();
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
