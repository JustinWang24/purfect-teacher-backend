<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 6:47 PM
 */

namespace Tests\Feature;
use App\Dao\Users\GradeUserDao;
use Tests\TestCase;
use App\Dao\Users\UserDao;
use App\Dao\Schools\SchoolDao;
use App\User;

class BasicPageTestCase extends TestCase
{
    protected $userDao;
    protected $gradeUserDao;
    protected $superAdmin;
    protected $operator;
    protected $schoolManager;
    protected $getTeacher;
    protected $school;
    protected $teacher;
    protected $student;
    protected $schoolSessionData = [];

    public function setUp(): void
    {
        parent::setUp();
        // 初始化所需要的 dao
        $this->userDao       = new UserDao();
        $this->gradeUserDao  = new GradeUserDao();
        $this->superAdmin    = $this->userDao->getUserByMobile('18601216091');
        $this->operator      = $this->userDao->getUserByMobile('18510209803');
        $this->schoolManager = $this->userDao->getUserByMobile('1000006');
        $this->teacher       = $this->gradeUserDao->getAnyTeacher(1);
        $this->student       = $this->gradeUserDao->getStudentBySchoolId(1);
    }

    /**
     * @return User
     */
    protected function getSuperAdmin(){
        return $this->superAdmin;
    }

    /**
     * @return User
     */
    protected function getOperator(){
        return $this->operator;
    }


    /**
     * @return User
     */
    protected function getTeacher(){
        return $this->teacher;
    }

    /**
     * @return User
     */
    protected function getSchoolManager()
    {
        return $this->schoolManager;
    }

    protected function getStudent()
    {
        return $this->student;
    }

    /**
     * 以给定的用户来加载测试的学校
     * @param User $user
     * @param int $schoolId
     * @return $this
     */
    protected function setSchoolAsUser($user, $schoolId = 1){
        $schoolDao = new SchoolDao($user);
        $this->school = $schoolDao->getSchoolById($schoolId);
        $this->schoolSessionData['school'] = [
            'id'=>$this->school->id,
            'uuid'=>$this->school->uuid,
            'name'=>$this->school->name
        ];

        return $this;
    }

    /**
     * @return UserDao
     */
    protected function getUserDao(){
        return $this->userDao;
    }


    public function getHeaderWithApiToken(){
        $token = $this->getStudent()['user']['api_token'];
        return  ['Authorization'=>"Bearer ".$token];
    }


    /**
     * 获取教师的header信息
     */
    public function getHeaderWithApiTokenForTeacher() {
        $token = $this->getTeacher()->user->api_token;
        return  ['Authorization'=>"Bearer ".$token];
    }


    /**
     * 随机生成汉字
     * @param $num
     * @return string
     */
    public function randomCreateChinese($num)  // $num为生成汉字的数量
    {
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }
}
