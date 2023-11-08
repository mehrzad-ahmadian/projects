<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;

use App\BankQuestion as Model;
use App\DifficultyLevel;

class BankQuestions extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        $query = new Model;

        if (isset($_POST['search_keyword'])){
            $searchKeyword = $_POST['search_keyword'];
            $query = $query->where('title' , 'like' , '%' . $searchKeyword . '%');
        }

        $records = $query->get();
        return view('bankquestions.index' , ['records' => $records]);
    }

    public function create(){
        $difficultyLevels = DifficultyLevel::getNameList();
        return view('bankquestions.form' , ['difficultyLevels' => $difficultyLevels]);
    }

    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'type' => 'required',
        ];

        switch ($request->type) {
            case 'multiple_choice':
                $rules['choice_one'] = 'required';
                $rules['choice_two'] = 'required';
                $rules['choice_three'] = 'required';
                $rules['choice_four'] = 'required';
                $rules['multiple_choice_correct_answer'] = 'required';
                break;

            case 'true_false':
                $rules['true_false_correct_answer'] = 'required';
                break;

            case 'text':
                $rules['text_correct_answer'] = 'required';
                break;
        }

        $this->validate($request, $rules);

        $record = Model::create([
            'creator_id' => $request->user()->id,
            'type' => $request->type,
            'title' => $request->title,
            'difficulty_level_id' => $request->difficulty_level_id,
            'choice_one' => $request->choice_one,
            'choice_two' => $request->choice_two,
            'choice_three' => $request->choice_three,
            'choice_four' => $request->choice_four,
            'multiple_choice_correct_answer' => $request->multiple_choice_correct_answer,
            'true_false_correct_answer' => $request->true_false_correct_answer,
            'text_correct_answer' => $request->text_correct_answer,
        ]);

        return redirect('/bankquestions/edit/' . $record->id);
    }

    // public function show($id){
    //     //
    // }

    public function edit($id){
        $record = Model::find($id);
        $difficultyLevels = DifficultyLevel::getNameList();
        return view('bankquestions.form' , ['record' => $record, 'difficultyLevels' => $difficultyLevels]);
    }

    public function update(Request $request, $id){
        $record = Model::find($id);

        $rules = [
            'title' => 'required',
            'type' => 'required',
        ];

        switch ($request->type) {
            case 'multiple_choice':
                $rules['choice_one'] = 'required';
                $rules['choice_two'] = 'required';
                $rules['choice_three'] = 'required';
                $rules['choice_four'] = 'required';
                $rules['multiple_choice_correct_answer'] = 'required';
                break;

            case 'true_false':
                $rules['true_false_correct_answer'] = 'required';
                break;

            case 'text':
                $rules['text_correct_answer'] = 'required';
                break;
        }

        $this->validate($request, $rules);

        $record->type = $request->type;
        $record->title = $request->title;
        $record->difficulty_level_id = $request->difficulty_level_id;
        $record->choice_one = $request->choice_one;
        $record->choice_two = $request->choice_two;
        $record->choice_three = $request->choice_three;
        $record->choice_four = $request->choice_four;
        $record->multiple_choice_correct_answer = $request->multiple_choice_correct_answer;
        $record->true_false_correct_answer = $request->true_false_correct_answer;
        $record->text_correct_answer = $request->text_correct_answer;
        $record->save();

        return Redirect::refresh();
    }

    public function destroy($id){
        $record = Model::find($id);
        $record->delete();

        return redirect('/bankquestions');
    }
}
