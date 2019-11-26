<?php


namespace App\Http\Controllers\Api\Questionnaire;


use App\Dao\Questionnaire\QuestionnaireDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

class QuestionnaireController
{

    public function index(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $questionnaireDao = new QuestionnaireDao();
        $questionnaires = $questionnaireDao->getQuestionnaireBySchoolId($schoolId);
        if (!empty($questionnaires))
            return JsonBuilder::Success($questionnaires);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \App\Utils\ReturnData\MessageBag|string
     */
    public function vote(Request $request, $id)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $questionnaireDao = new QuestionnaireDao();
        $questionnaire = $questionnaireDao->getQuestionnaireById($id);
        if ($questionnaire->school_id !== $schoolId)
            return JsonBuilder::Error('您没有权限回答此问卷');
        $option = $request->option;
        if (!in_array($option, [1,2,3]))
            return JsonBuilder::Error('您的回答无此选项');
        $data['school_id']          = $schoolId;
        $data['questionnaire_id']   = $id;
        $data['user_id']            = $user->id;
        $data['result']             = $request->option;

        $result = $questionnaireDao->insertResult($data);
        if ($result->getCode() == 1000)
        {
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }

}
