<?php
namespace App\Dao\Textbook;

use App\Dao\Courses\CourseDao;
use App\Dao\Courses\CourseMajorDao;
use App\Dao\Courses\CourseTextbookDao;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\GradeUserDao;
use App\Dao\Schools\MajorDao;
use App\Models\Courses\CourseTextbook;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Models\Schools\RecruitmentPlan;
use App\Models\Schools\Textbook;
use App\Models\Students\StudentTextbook;
use App\Models\Users\GradeUser;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Schools\TextbookImage;

class TextbookDao
{
    /**
     * 删除教材接口
     * @param $id
     * @param $schoolId
     * @return bool
     */
    public function delete($id, $schoolId){
        DB::beginTransaction();
        try{
            // 删除自己
            $deleted = Textbook::where('id',$id)->where('school_id',$schoolId)->delete();
            if($deleted){
                // 删除关联的课程
                $dao = new CourseTextbookDao();
                $dao->deleteByTextbook($id);
                // 删除关联的图片
                TextbookImage::where('textbook_id',$id)->delete();
                DB::commit();
                return true;
            }
            else{
                DB::rollBack();
                return false;
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $query
     * @param $schoolId
     * @param $scope
     * @return \Illuminate\Support\Collection
     */
    public function searchByName($query, $schoolId, $scope){
        $builder = Textbook::where('school_id',$schoolId)
            ->where('name','like','%'.$query.'%')
            ->with('medias')
            ->with('courses');
        if($scope){
            $builder->where('type',$scope);
        }
        return $builder->take(ConfigurationTool::DEFAULT_PAGE_SIZE_QUICK_SEARCH)->get();
    }

    /**
     * 更新某教材所关联的所有课程
     * @param $bookId
     * @param $courses
     * @param $schoolId
     * @return bool
     */
    public function updateRelatedCourses($bookId, $courses, $schoolId){
        DB::beginTransaction();
        try{
            CourseTextbook::where('textbook_id',$bookId)->delete();
            $data = [
                'textbook_id'=>$bookId,
                'course_id'=>null,
                'school_id'=>$schoolId,
            ];
            foreach ($courses as $course) {
                $data['course_id'] = $course;
                CourseTextbook::create($data);
            }
            DB::commit();
            return true;
        }catch (\Exception $exception){
            return false;
        }

    }

    /**
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $info = $this->getTextbookByName($data['name']);
        if(!empty($info)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'该教材已添加,请勿重复添加');
        }
        DB::beginTransaction();
        $book = Textbook::create($data);
        if($book){
            try{
                $medias = $data['medias'];
                $imgs = [];
                foreach ($medias as $key => $media) {
                    $imgs[] = TextbookImage::create(
                        [
                            'textbook_id'=>$book->id,
                            'media_id'=>$media['id'],
                            'url'=>$media['url'],
                            'position'=>$key+1
                        ]
                    );
                }
                $book->medias = $imgs;
                DB::commit();
                return new MessageBag(
                    JsonBuilder::CODE_SUCCESS,
                    '创建成功',
                    $book
                );
            }catch (\Exception $exception){
                DB::rollBack();
                return new MessageBag(JsonBuilder::CODE_ERROR,'创建失败: 无法关联图片');
            }
        }else{
            return new MessageBag(JsonBuilder::CODE_ERROR,'创建失败: 数据库错误');
        }
    }

    /**
     * 根据ID修改
     * @param $data
     * @return mixed
     */
    public function editById($data) {
        $id = $data['id'];
        unset($data['id']);

        $medias = $data['medias'];
        unset($data['medias']);
        unset($data['courses']);

        DB::beginTransaction();
        $updated = Textbook::where('id',$id)->update($data);
        if($updated){
            try{
                TextbookImage::where('textbook_id',$id)->delete();

                foreach ($medias as $key => $media) {
                    TextbookImage::create(
                        [
                            'textbook_id'=>$id,
                            'media_id'=>$media['id'],
                            'url'=>$media['url'],
                            'position'=>$key+1
                        ]
                    );
                }

                DB::commit();
                return new MessageBag(
                    JsonBuilder::CODE_SUCCESS,
                    '更新成功',
                    $this->getTextbookById($id)
                );
            }catch (\Exception $exception){
                DB::rollBack();
                return new MessageBag(JsonBuilder::CODE_ERROR,'更新失败: 无法关联图片');
            }
        }else{
            DB::rollBack();
            return new MessageBag(JsonBuilder::CODE_ERROR,'更新失败: 无法关联图片');
        }
    }

    /**
     * 根据名称获取教材
     * @param $name
     * @return mixed
     */
    public function getTextbookByName($name) {
        $field = ['id', 'name'];
        return Textbook::where('name',$name)->select($field)->first();
    }

    /**
     * 根据ID获取详情
     * @param $id
     * @return mixed
     */
    public function getTextbookById($id) {
        return Textbook::where('id',$id)->with('medias')->with('courses')->first();
    }

    /**
     * 通过专业ID获取教材采购数
     * @param $majorId
     * @param $schoolId
     * @return array
     */
    public function getTextbooksByMajor($majorId,$schoolId) {
        $courseMajorDao = new CourseMajorDao();
        $list = $courseMajorDao->getCoursesByMajor($majorId)->toArray();
        $courseIdArr = array_column($list,'id','id');

        //查询所有课程的详情
        $courseDao = new CourseDao();
        $courses = $courseDao->getCoursesByIdArr($courseIdArr)->toArray();

        $thisYear = Carbon::now()->year;  // 今年
        $nextYear = Carbon::parse('+ 1year')->year; // 明年

        foreach ($courses as $key => $val) {
            $year = $nextYear - $val['year'];
            if($year == $thisYear) {
                // 去查招生计划和已招学生
                $num = $this->getNewlyBornNumByMajor($majorId,$nextYear,$schoolId);
                $courses[$key]['type'] = 1;   // 即将入学新生
                $courses[$key]['textbook_num'] = $num;
            } else {
                // 通过专业ID和课程的年级查询学生数量
                $courses[$key]['type'] = 0;   // 老生
                $num = $this->getStudentNumByMajorAndYear($majorId,$year);
                $courses[$key]['textbook_num'] = $num;
            }
        }
        return $courses;
    }


    /**
     * 通过该专业和学年获取班级学生的总数
     * @param $majorId
     * @param $year
     * @return int|mixed
     */
    public function getStudentNumByMajorAndYear($majorId, $year) {
        $gradeDao = new GradeDao();

        $gradeList = $gradeDao->getGradesByMajorAndYear($majorId, $year);
        if(empty($gradeList)) {
            return 0;
        }
        $gradeIdArr = array_column($gradeList->toArray(),'id');
        $gradeUserDao = new GradeUserDao();

        $count = $gradeUserDao->getCountByGradeId($gradeIdArr);
        return $count;
    }


    /**
     * 通过专业获取下年招生计划人数和已招人数
     * @param int $majorId 专业ID
     * @param int $year  年
     * @param int $schoolId  学校ID
     * @return array
     */
    public function getNewlyBornNumByMajor($majorId, $year, $schoolId) {
        //查询招生计划
        $recruitmentPlanDao = new RecruitmentPlanDao($schoolId);
        //统招计划
        $generalPlan = $recruitmentPlanDao->getRecruitmentPlanByMajorAndYear($majorId,$year,RecruitmentPlan::TYPE_GENERAL)->toArray();
        $generalPlanSeat = array_sum(array_column($generalPlan,'seats'));

        //自招计划
        $selfPlan = $recruitmentPlanDao->getRecruitmentPlanByMajorAndYear($majorId,$year,RecruitmentPlan::TYPE_SELF)->toArray();
        $selfPlanSeat = array_sum(array_column($selfPlan,'seats'));
        $totalPlanSeat = $generalPlanSeat + $selfPlanSeat;

        // 大于或等于该状态 表示已被录取
        $status = RegistrationInformatics::APPROVED;

        //统招报名人数
        $generalPlanIdArr = array_column($generalPlan,'id');
        $registrationInformaticsDao = new RegistrationInformaticsDao();
        $generalInformaticsSeat = $registrationInformaticsDao->getCountByStatusAndPlanIdArr($status, $generalPlanIdArr);


        //自招报名人数
        $selfPlanIdArr = array_column($selfPlan,'id');
        $selfInformaticsSeat = $registrationInformaticsDao->getCountByStatusAndPlanIdArr($status, $selfPlanIdArr);
        $totalInformaticsSeat = $generalInformaticsSeat + $selfInformaticsSeat;

        return [
            'general_plan_seat' => $generalPlanSeat,       // 计划统招人数
            'self_plan_seat'    => $selfPlanSeat,          // 计划自招人数
            'total_plan_seat'   => $totalPlanSeat,         // 计划总共招生人数
            'general_informatics_seat'  => $generalInformaticsSeat,  // 统招报名人数
            'self_informatics_seat'     => $selfInformaticsSeat,     // 自招报名人数
            'total_informatics_seat'    => $totalInformaticsSeat,    // 总共报名人数
            ];
    }


    /**
     * 获取课程列表
     * @param $schoolId
     * @return mixed
     */
    public function getTextbookListBySchoolId($schoolId) {
        return Textbook::where('school_id',$schoolId)->get();
    }

    /**
     * 以分页的方式获取课程列表
     * @param $schoolId
     * @param $pageNumber
     * @param $pageSize
     * @return array
     */
    public function getTextbookListPaginateBySchoolId(
        $schoolId,
        $pageNumber = 0,
        $pageSize = ConfigurationTool::DEFAULT_PAGE_SIZE
    ) {
        $books =  Textbook::where('school_id',$schoolId)
            ->with('medias')
            ->with('courses')
            ->orderBy('updated_at','desc')
            ->skip($pageSize * $pageNumber)
            ->take($pageSize)
            ->get();

        $total = Textbook::where('school_id',$schoolId)->count();
        return [
            'books'=>$books,
            'total'=>$total,
            'p'=>$pageNumber,
            's'=>$pageSize
        ];
    }

    /**
     * 查询当前班级所学的教材
     * @param $gradeId
     * @return MessageBag
     */
    public function getTextbooksByGradeId($gradeId) {

        $gradeUserDao = new GradeUserDao();
        $gradeDao = new GradeDao();
        $courseDao = new CourseDao();

        // 查询当前班级学生的总数
        $courseMajorDao = new CourseMajorDao();
        $studentCount = $gradeUserDao->getCountByGradeId($gradeId);
        $gradeInfo = $gradeDao->getGradeById($gradeId);   //班级详情

        $nextYear = Carbon::parse('+ 1year')->year;
        $year = $nextYear - $gradeInfo['year'] + 1 ;  // 计算班级的下一年年级

        // 通过专业和年级查询该班上的课程
        $result = $courseMajorDao->getCoursesByMajorAndYear($gradeInfo['major_id'],$year);
        if(empty($result)) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'当前专业所处年级没有课程',[]);
        }
        $courseIdArr = array_column($result,'id');

        // 通过课程查询该班所用的教材
        $list = $courseDao->getCoursesByIdArr($courseIdArr);
        foreach ($list as $key => $val) {
            $list[$key]['textbook_num'] = $studentCount;
        }

        return new MessageBag(JsonBuilder::CODE_SUCCESS,'请求成功',$list);
    }


    /**
     * 获取校区下的教材
     * @param $campusId
     * @return MessageBag
     */
    public function getCampusTextbook($campusId) {

        // 通过校区ID获取专业
        $majorDao = new MajorDao();
        $majorList = $majorDao->getMajorsByCampusId($campusId);
        if(empty($majorList)) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'该校区下没有专业',[]);
        }

        // 通过专业ID集合获取相关课程并关联到教材
        $majorIdArr = array_column($majorList->toArray(),'id');
        $courseMajorDao = new CourseMajorDao();
        $courseList = $courseMajorDao->getCourseIdByMajorIdArr($majorIdArr)->toArray();
        if(empty($courseList)) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'该专业下没有课程',[]);
        }

        $thisYear = Carbon::now()->year;
        $nextYear = Carbon::parse('+ 1year')->year; // 明年
        // 查询课程关联的学生
        foreach ($courseList as $key => $val) {
            $year = $nextYear - $val['course']['year'];
            if($year == $thisYear) {

                // 去查招生计划和已招学生
                $num = $this->getNewlyBornNumByMajor($val['major_id'], $nextYear, $val['school_id']);
                $courseList[$key]['type'] = 1;   // 即将入学新生
                $courseList[$key]['textbook_num'] = $num;

            } else {
                // 通过专业ID和课程的年级查询学生数量
                $courseList[$key]['type'] = 0;   // 老生
                $num = $this->getStudentNumByMajorAndYear($val['major_id'],$year);
                $courseList[$key]['textbook_num'] = $num;
            }

        }
        return new MessageBag(JsonBuilder::CODE_SUCCESS,'请求成功',$courseList);
    }


    /**
     * 获取当前时间用户所使用的教材
     * @param GradeUser $gradeUser
     * @param $year
     * @return array
     */
    public function userTextbook(GradeUser $gradeUser, $year) {
        // 专业下的课程
        $coursesMajor = $gradeUser->major->courseMajors;
        // 入学年份
        $timeOfEnrollment = $gradeUser->grade->year;
        // 当前所在的年级
        $year = $year - $timeOfEnrollment + 1;

        $coursesMajorIds = array_column($coursesMajor->toArray(),'course_id');
        $courseDao = new CourseDao();
        // 该专业当前学年所上的课程
        $courses = $courseDao->getCourseByIdsAndYear($coursesMajorIds, $year);
        // 教材
        foreach ($courses as $key => $val) {

            $courseTextbooks = $val->courseTextbooks;

            foreach ($courseTextbooks as $k => $v) {
                $textbooks[$k] = $v->textbook;
                $map = ['user_id'=>$gradeUser->user->id,'textbook_id'=>$v->textbook_id];
                $get = StudentTextbook::where($map)->first();
                $textbooks[$k]['status'] = $get ? '已领取' : '未领取';
                $textbooks[$k]['getTime'] = $get ? $get->created_at : '';
            }
        }

        return $textbooks;
    }


    /**
     * 领取教材
     * @param $data
     * @return mixed
     */
    public function getTextbook($data) {
        return StudentTextbook::create($data);
    }
}
