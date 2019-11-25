<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireResult extends Model
{
    protected $fillable = [
        'school_id', 'questionnaire_id', 'user_id',
        'result',
    ];
}
