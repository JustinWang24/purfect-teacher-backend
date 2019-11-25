<?php


namespace App\Http\Requests\School;


use App\Http\Requests\MyStandardRequest;

class QuestionnaireRequest extends MyStandardRequest
{
    public function rules()
    {
        return [
            'title'                 => ['required',  'max:255'],
            'detail'                => ['required',  'min:20'],
            'cfirst_question_info'  => ['required',  'max:255'],
            'first_question_info'   => ['required',  'max:255'],
            'first_question_info'   => ['required',  'max:255'],
        ];
    }
}
