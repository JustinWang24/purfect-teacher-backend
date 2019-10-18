<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 6:47 PM
 */

namespace Tests\Feature;
use Tests\TestCase;
use App\Dao\Users\UserDao;
use App\Dao\Schools\SchoolDao;
use App\User;

class BasicPageTestCase extends TestCase
{
    protected $userDao;
    protected $superAdmin;
    protected $operator;
    protected $school;
    protected $schoolSessionData;

    public function setUp(): void
    {
        parent::setUp();
        // 初始化所需要的 dao
        $this->userDao = new UserDao();
        $this->superAdmin = $this->userDao->getUserByMobile('18601216091');
        $this->operator = $this->userDao->getUserByMobile('18510209803');
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
     * 以给定的用户来加载测试的学校
     * @param User $user
     * @param int $schoolId
     * @return $this
     */
    protected function setSchoolAsUser($user, $schoolId = 1){
        $schoolDao = new SchoolDao($user);
        $this->school = $schoolDao->getSchoolById($schoolId);
        $this->schoolSessionData = [
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
}