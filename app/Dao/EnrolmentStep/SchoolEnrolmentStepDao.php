<?php

namespace App\Dao\EnrolmentStep;


use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;
use App\Models\EnrolmentStep\SchoolEnrolmentStep;
use App\Models\EnrolmentStep\SchoolEnrolmentStepTask;
use App\Models\EnrolmentStep\SchoolEnrolmentStepMedia;
use App\Models\EnrolmentStep\SchoolEnrolmentStepAssist;

class SchoolEnrolmentStepDao{


    /**
     * @param $id
     * @return SchoolEnrolmentStep
     */
    public function getEnrolmentStepById($id) {
        return SchoolEnrolmentStep::where('id',$id)->first();
    }


    /**
     * 获取当前步的下一步
     * @param int $schoolId  学校ID
     * @param int $campusId  校区ID
     * @param null $sort 当前第几步
     * @return mixed
     */
    public function getEnrolmentStepAfterFirst($schoolId, $campusId, $sort=null) {
        $map = ['school_id'=>$schoolId, 'campus_id'=>$campusId];
        $where = [];
        if(!is_null($sort)) {
            $where = [['sort','>',$sort]];
        }
        return SchoolEnrolmentStep::where($map)->where($where)->orderBy('sort')->first();
    }


    /**
     * 获取当前步的上一步
     * @param int $schoolId  学校ID
     * @param int $campusId  校区ID
     * @param null $sort 当前第几步
     * @return mixed
     */
    public function getEnrolmentStepBeforeFirst($schoolId, $campusId, $sort=null) {
        $map = ['school_id'=>$schoolId, 'campus_id'=>$campusId];
        $where = [];
        if(!is_null($sort)) {
            $where = [['sort','<',$sort]];
        }
        return SchoolEnrolmentStep::where($map)->where($where)->orderBy('sort', 'desc')->first();
    }


    /**
     * 创建迎新方法
     * @param $data
     * @return MessageBag
     */
    public function createSchoolEnrolmentStep($data) {

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        $assists = null;
        if(!empty($data['assists'])) {
             $assists = $data['assists'];
             unset($data['assists']);
        }
        $medias = null;
        if(!empty($data['medias'])) {
            $medias = $data['medias'];
            unset($data['medias']);
        }

        $tasks = null;
        if(!empty($data['tasks'])) {
            $tasks = $data['tasks'];
            unset($data['tasks']);
        }
        $map = ['school_id'=>$data['school_id'], 'campus_id'=>$data['campus_id']];
        $sort = SchoolEnrolmentStep::where($map)->count();
        $data['sort'] = $sort + 1;

        DB::beginTransaction();
        try {
            // 创建学校迎新流程
            $step = SchoolEnrolmentStep::create($data);
            // 创建学校迎新协助人
            if(!is_null($assists)) {
                foreach ($assists as $val) {
                    $insert = [
                        'school_enrolment_step_id' => $step->id,
                        'user_id' => $val,
                    ];
                    SchoolEnrolmentStepAssist::create($insert);

                }
            }

            // 创建迎新媒体
            if(!is_null($medias)) {
                foreach ($medias as $val) {
                    $insert = [
                        'school_enrolment_step_id' => $step->id,
                        'media_id' => $val,
                    ];
                    SchoolEnrolmentStepMedia::create($insert);

                }
            }

            // 创新迎新子类
            if(!is_null($tasks)) {
                foreach ($tasks as $val) {
                    $val['school_enrolment_step_id'] = $step->id;
                    SchoolEnrolmentStepTask::create($val);
                }
            }

            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData($step);
            $messageBag->setMessage('创建成功');
        } catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setMessage($e->getMessage());
        }
        return $messageBag;
    }


    /**
     * 更新迎新流程
     * @param $id
     * @param $data
     * @return MessageBag
     */
    public function updateSchoolEnrolmentStep($id, $data) {

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        $assists = null;
        if(!empty($data['assists'])) {
             $assists = $data['assists'];
             unset($data['assists']);
        }
        $medias = null;
        if(!empty($data['medias'])) {
            $medias = $data['medias'];
            unset($data['medias']);
        }

        $tasks = null;
        if(!empty($data['tasks'])) {
            $tasks = $data['tasks'];
            unset($data['tasks']);
        }


        DB::beginTransaction();
        try{

            // 先更新学校迎新步骤表
            SchoolEnrolmentStep::where('id',$id)->update($data);

            // 学校迎新协助人
            if(!empty($assists)) {
                // 删除协助人
                SchoolEnrolmentStepAssist::where('school_enrolment_step_id',$id)->delete();
                foreach ($assists as $val) {
                    $insert = [
                        'school_enrolment_step_id' => $id,
                        'user_id' => $val,
                    ];
                    SchoolEnrolmentStepAssist::create($insert);
                }
            }

            // 迎新文件
            if(!empty($medias)) {

                // 删除文件关联信息
                SchoolEnrolmentStepMedia::where('school_enrolment_step_id',$id)->delete();

                foreach ($medias as $val) {

                    $insert = [
                        'school_enrolment_step_id' => $id,
                        'media_id' => $val,
                    ];
                    SchoolEnrolmentStepMedia::create($insert);

                }
            }

            // 迎新步骤子类
            if(!empty($tasks)) {
                // 删除
                SchoolEnrolmentStepTask::where('school_enrolment_step_id',$id)->delete();
                foreach ($tasks as $val) {
                    $val['school_enrolment_step_id'] = $id;

                    SchoolEnrolmentStepTask::create($val);
                }
            }

            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setMessage('更新成功');
            $messageBag->setData([]);
        } catch (\Exception $e) {

            DB::rollBack();
            $messageBag->setMessage($e->getMessage());
        }

        return $messageBag;
    }


    /**
     * 删除迎新流程
     * @param $id
     * @return MessageBag
     */
    public function deleteSchoolEnrolmentStep($id) {

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try{
            // 删除学校迎新步骤
            SchoolEnrolmentStep::where('id',$id)->delete();
            // 删除迎新协助人
            SchoolEnrolmentStepAssist::where('school_enrolment_step_id',$id)->delete();

            // 删除迎新文件
            SchoolEnrolmentStepMedia::where('school_enrolment_step_id',$id)->delete();

            // 删除迎新子类
            SchoolEnrolmentStepTask::where('school_enrolment_step_id',$id)->delete();

            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setMessage('删除成功');
            $messageBag->setData([]);

        }catch (\Exception $e) {

            $messageBag->setMessage($e->getMessage());
            DB::rollBack();
        }

        return $messageBag;
    }
}
