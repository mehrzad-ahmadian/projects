<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

	protected $fillable = [
        'creator_id',
        'exam_id',
        'order',
        'type',
        'title',
        'difficulty_level_id',
        'choice_one',
        'choice_two',
        'choice_three',
        'choice_four',
        'multiple_choice_correct_answer',
        'true_false_correct_answer',
        'text_correct_answer',
	];

	// relations
	public function creator(){
        return $this->belongsTo('App\User');
    }

    public function difficulty_level(){
        return $this->belongsTo('App\DifficultyLevel');
    }

    public function exam(){
        return $this->belongsTo('App\Exam');
    }

    // methods
    public static function getNameList(){
    	return self::lists('name' , 'id');
    }
}
