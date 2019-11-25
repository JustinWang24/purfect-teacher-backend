<?php


namespace App\Dao\Questionnaire;


use App\Dao\BuildFillableData;
use App\Models\Questionnaire\Questionnaire;
use App\Models\Questionnaire\QuestionnaireResult;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use http\QueryString;
use Illuminate\Support\Facades\DB;

class QuestionnaireDao
{
    use BuildFillableData;
    public function __construct()
    {
    }

    public function create($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            unset($data['id']);
        }
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new Questionnaire(), $data);
            $questionnaire = Questionnaire::create($fillableData);
            if ($questionnaire) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($questionnaire);
            } else {
                DB::rollBack();
                $messageBag->setMessage('保存问卷调查失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function update($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new Questionnaire(), $data);
            $questionnaire = Questionnaire::where('id', $id)->update($fillableData);
            if ($questionnaire) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($questionnaire);
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新问卷调查失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function insertResult($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            unset($data['id']);
        }
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new QuestionnaireResult(), $data);
            $questionnaireResult = QuestionnaireResult::create($fillableData);
            if ($questionnaireResult) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($questionnaireResult);
            } else {
                DB::rollBack();
                $messageBag->setMessage('保存结果失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }
}
