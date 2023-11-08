<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';

	protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'submissions',
        'automatic_correction_done',
        'manual_correction_done',
	];

    protected $jsonable = [
        'submissions',
    ];

    protected $dates = ['start_time', 'end_time'];

	// relations
	public function user(){
        return $this->belongsTo('App\User');
    }

    public function exam(){
        return $this->belongsTo('App\Exam');
    }

    public function correction(){
        return $this->hasOne('App\Correction');
    }

    // methods
    
}
