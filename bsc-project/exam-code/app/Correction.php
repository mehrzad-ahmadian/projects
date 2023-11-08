<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correction extends Model
{
    protected $table = 'corrections';

	protected $fillable = [
        'creator_id',
        'submission_id',
        'automatic_total_correct',
        'automatic_total_uncorrect',
        'automatic_total_unanswered',
        'automatic_score',
        'automatic_score_by_percentage',
        'manual_correction',
        'manual_score',
        'manual_score_by_percentage',
        'total_score',
        'total_score_by_percentage',
        'correction_done',
	];

    protected $jsonable = [
        'manual_correction',
    ];

	// relations
	public function creator(){
        return $this->belongsTo('App\User');
    }

    public function submission(){
        return $this->belongsTo('App\Submission');
    }

    // methods
}
