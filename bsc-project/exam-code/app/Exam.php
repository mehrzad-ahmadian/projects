<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Correction;

class Exam extends Model
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

    // protected $dates = ['start_time', 'end_time'];

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

    public function submissions(){
        return $this->hasMany('App\Submission');
    }

    public function corrections(){
        return $this->hasManyThrough('App\Correction', 'App\Submission');
    }

    // methods
    public static function getNameList(){
    	return self::lists('name' , 'id');
    }

    public function getQuestionPoints($questionId) {
        $question = $this->questions()->where('questions.id', $questionId)->first();
        return $this->total_score / $this->questions()->count();
    }

    public static function hasAnyQuestionByType($id, array $types = []) {
        $exam = self::find($id);
        if (! is_null($exam)) {
            $questions = $exam->questions()->whereIn('type', $types)->first();
            if (! is_null($questions)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function hasAutomaticUncorrectedSubmission($id) {
        $exam = self::find($id);
        if (! is_null($exam->submissions()->where('automatic_correction_done', 0)->first())) {
            return true;
        } else {
            return false;
        }
    }

    public static function hasManualUncorrectedSubmission($id) {
        $exam = self::find($id);
        if (! is_null($exam->submissions()->where('manual_correction_done', 0)->first())) {
            return true;
        } else {
            return false;
        }
    }

    public static function calculateTotalScore($id){
        $exam = self::find($id);
        $correctedSubmissionIds = $exam->submissions()->select('id')->get()->toArray();
        $corrections = Correction::whereIn('submission_id', $correctedSubmissionIds)->get();
        foreach ($corrections as $correction) {
            $correction->total_score_by_percentage = $correction->automatic_score_by_percentage + $correction->manual_score_by_percentage;
            $correction->total_score = $correction->automatic_score + $correction->manual_score;
            $correction->correction_done = 1;
            $correction->save();
        }
    }

    public static function hasAnySubmission($id) {
        $exam = self::find($id);
        if (! is_null($exam->submissions()->first())) {
            return true;
        } else {
            return false;
        }
    }
}
