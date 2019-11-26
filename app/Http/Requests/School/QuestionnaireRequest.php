<?php


namespace App\Http\Requests\School;


use App\Http\Requests\MyStandardRequest;

class QuestionnaireRequest extends MyStandardRequest
{
    public function rules()
    {
        return [
            'questionnaire.title'                 => ['required',  'max:255'],
            'questionnaire.detail'                => ['required',  'min:20'],
            'questionnaire.first_question_info'   => ['required',  'max:255'],
            'questionnaire.second_question_info'  => ['required',  'max:255'],
            'questionnaire.third_question_info'   => ['required',  'max:255'],
        ];
    }
}
