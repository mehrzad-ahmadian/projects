<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TakeExam extends Model
{
    protected $table = 'exams';

	protected $fillable = [
        'creator_id',
        'name',
        'category_id',
        'total_score',
        'duration',
        'start_time',
        'end_time',
        'enabled',
	];

    protected $dates = ['start_time', 'end_time'];

	// relations
	public function creator(){
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function questions(){
        return $this->hasMany('App\Question');
    }

    // methods
    public static function getNameList(){
    	return self::lists('name' , 'id');
    }
}
