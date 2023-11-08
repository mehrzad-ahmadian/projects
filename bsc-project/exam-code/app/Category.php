<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

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
