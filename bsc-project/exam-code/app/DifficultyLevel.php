<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{
    protected $table = 'difficulty_levels';

	protected $fillable = [
        'creator_id',
        'name',
	];

	// relations
	public function creator(){
        return $this->belongsTo('App\User');
    }

    // methods
    public static function getNameList(){
    	return self::lists('name' , 'id');
    }
}
