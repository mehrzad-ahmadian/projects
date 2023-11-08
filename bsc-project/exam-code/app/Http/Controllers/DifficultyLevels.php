<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;

use App\DifficultyLevel as Model;

class DifficultyLevels extends Controller
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
        return view('difficultylevels.index' , ['records' => $records]);
    }

    public function create(){
        return view('difficultylevels.form');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
        ]);

        $record = Model::create([
            'creator_id' => $request->user()->id,
            'name' => $request->name,
        ]);

        return redirect('/difficultylevels/edit/' . $record->id);
    }

    // public function show($id){
    //     //
    // }

    public function edit($id){
        $record = Model::find($id);
        return view('difficultylevels.form' , ['record' => $record]);
    }

    public function update(Request $request, $id){
        $record = Model::find($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $record->name = $request->name;
        $record->save();

        return Redirect::refresh();
    }

    public function destroy($id){
        $record = Model::find($id);
        $record->delete();

        return redirect('/difficultylevels');
    }
}
