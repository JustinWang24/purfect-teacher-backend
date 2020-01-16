<?php


namespace App\Models\Evaluate;


use App\Models\Schools\Grade;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EvaluateTeacherRecord extends Model
{

    protected $fillable = [
        'evaluate_student_id', 'evaluate_id', 'user_id', 'score',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

}
