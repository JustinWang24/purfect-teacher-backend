<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'school_id', 'title', 'detail',
        'first_question_info', 'second_question_info',
        'third_question_info', 'status'
    ];

    public function result()
    {
        return $this->hasMany(QuestionnaireResult::class, 'questionnaire_id', 'id');
    }
}
