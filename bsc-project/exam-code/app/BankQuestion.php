<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankQuestion extends Model
{
    protected $table = 'bank_questions';

	protected $fillable = [
        'creator_id',
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

    // methods
    public static function getTitleList(){
    	return self::lists('title' , 'id');
    }

    public static function getAllowedBankQuestions(){
        return self::get();
    }
}
