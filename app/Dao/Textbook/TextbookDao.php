<?php
namespace App\Dao\Textbook;

use App\Dao\Courses\CourseDao;
use App\Dao\Courses\CourseMajorDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\GradeUserDao;
use App\Models\Acl\Role;
use App\Models\Schools\Textbook;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;

class TextbookDao
{

    /**
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {

        $info = $this->getTextbookByName($data['name']);
        if(!empty($info)) {
            return new MessageBag(JsonBuilder::Error(),'该教材已添加,请勿重复添加');
        }

        $re = Textbook::create($data);

        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'创建失败');
        }
    }


    /**
     * 编辑
     * @param $map
     * @param $data
     * @return mixed
     */
    protected function edit($map, $data) {
        return Textbook::where($map)->update($data);
    }


    /**
     * 根据ID修改
     * @param $id
     * @param $data
     * @return mixed
     */
    public function editById($id,$data) {
        $map = ['id'=>$id];
        return $this->edit($map,$data);
    }


    /**
     * 获取教材详情
     * @param $map
     * @param string $field
     * @return mixed
     */
    protected function getTextbookInfo($map, $field='*') {
        return Textbook::where($map)->select($field)->first();
    }



    /**
     * 根据名称获取教材
     * @param $name
     * @return mixed
     */
    public function getTextbookByName($name) {
        $field = ['id', 'name'];
        $map = ['name'=>$name];
        return $this->getTextbookInfo($map, $field);
    }


    /**
     * 根据ID获取详情
     * @param $id
     * @return mixed
     */
    public function getTextbookById($id) {
        $map = ['id'=>$id];
        return $this->getTextbookInfo($map);
    }


    public function getTextbooksByMajor($majorId = '2971') {
        $courseMajorDao = new CourseMajorDao();
        $list = $courseMajorDao->getCoursesByMajor($majorId)->toArray();
        $courseIdArr = array_column($list,'id','id');
        //查询所有课程的详情
        $courseDao = new CourseDao();
        $field = ['id', 'code', 'name', 'year', 'term'];
        $courses = $courseDao->getCoursesByIdArr($courseIdArr, $field)->toArray();

        //下一年时间
        $nextYear = Carbon::parse('+ 1year')->format('Y');

        foreach ($courses as $key => $val) {
            $year = $nextYear - $val['year'];
            if($year == 1) {
                // todo 去查招生计划和已招学生
            } else {
                $num = $this->getStudentNumByMajorAndYear($majorId,$year);
                $courses[$key]['textbook_num'] = $num;
            }

        }
        dd($courses);

        return $list;
    }


    /**
     * 通过该专业和学年获取班级学生的总数
     * @param $majorId
     * @param $year
     * @return int|mixed
     */
    public function getStudentNumByMajorAndYear($majorId, $year) {
        $gradeDao = new GradeDao();
        $gradeList = $gradeDao->getGradesByMajorAndYear($majorId, $year)->toArray();
        if(empty($gradeList)) {
            return 0;
        }
        $gradeIdArr = array_column($gradeList,'id');
        $gradeUserDao = new GradeUserDao();
        $map = ['user_type'=>Role::VERIFIED_USER_STUDENT];
        $count = $gradeUserDao->getCountByGradeIdArr($map,$gradeIdArr);
        return $count;
    }


}
