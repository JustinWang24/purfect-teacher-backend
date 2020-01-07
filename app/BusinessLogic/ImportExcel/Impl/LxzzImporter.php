<?php


namespace App\BusinessLogic\ImportExcel\Impl;


use App\Dao\Importer\ImporterDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Models\Acl\Role;
use App\Models\Importer\ImoprtLog;
use App\Models\Students\StudentProfile;
use App\User;
use App\Utils\ReturnData\MessageBag;
use Exception;
use Ramsey\Uuid\Uuid;

class LxzzImporter extends AbstractImporter
{
    public function handle()
    {
        $this->loadExcelFile();
        //检测配置文件
        $dao = new ImporterDao();
        $config = $this->getConfig();
        $taskObj = $dao->getTaskById($config['task_id'], $field="*");
        $schoolId = $taskObj->school_id;
        echo '学校《'.$taskObj->school->name.'》资料获取成功\n';
        $this->info('学校《'.$taskObj->school->name.'》资料获取成功\n');
        $data = $this->getData();
        $sheetIndexArray = $this->getSheetIndexArray();

        foreach($sheetIndexArray as $sheetIndex)
        {
            //获取当前sheet的配置
            $sheetConfig = $config['school']['sheet'][$sheetIndex];
            if (empty($sheetConfig)) {
                echo "\033[38;33;1;101m配置为空，跳过第".$sheetIndex."个sheet\033[0m\n";
                $this->info("\033[38;33;1;101m配置为空，跳过第".$sheetIndex."个sheet\033[0m\n");
                continue;
            }

            $sheetData = $this->getSheetData($sheetIndex);
            echo '获取到第'.$sheetIndex.'个sheet数据开始循环<br>';
            $this->info('获取到第'.$sheetIndex.'个sheet数据开始循环');
            foreach ($sheetData as $key => $row)
            {
                if ($key<$sheetConfig['dataRow'])
                    continue;

                $rowData = $this->getRowData($sheetIndex, $row);
                //echo '获取到一行资料'.json_encode($rowData,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES).'\n';
                $this->info('获取到一行资料'.json_encode($rowData,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
                //echo '获取到一行资料'.json_encode($this->getHeader($sheetIndex),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES).'\n';
                //手机号不能为空
                if (empty($rowData['mobile']) || strlen($rowData['mobile'])!=11) {
                    $this->log($schoolId, $row,'手机号格式错误','', '', 1, ImoprtLog::FAIL_STATUS);
                    echo "\033[38;39;1;101m".$rowData['mobile']."手机号为空或者位数不对跳过\033[0m\n";
                    $this->info($rowData['mobile']."手机号为空或者位数不对跳过");
                    continue;
                }
                if (empty($rowData['idNumber']) || strlen($rowData['idNumber'])!=18) {
                    $this->log($schoolId, $row,'身份证号格式错误','', '', 1, ImoprtLog::FAIL_STATUS);
                    echo "\033[38;39;1;101m".$rowData['idNumber']."||".$sheetIndex."身份证号为空或者位数不对跳过\033[0m\n";
                    $this->info($rowData['idNumber']."||".$sheetIndex."身份证号为空或者位数不对跳过");
                    continue;
                }
                $rowData['year'] = substr($rowData['year'],0,4);

                $passwordInPlainText = substr($rowData['idNumber'],-6);
                $importUser = $this->saveUser($schoolId, $rowData['mobile'], $rowData['userName'], $passwordInPlainText,$row);
                if ($importUser) {
                    $gradeUser = $this->saveGradeUser($importUser, $rowData,$schoolId, $row);
                    $this->saveStudentProfile($schoolId, $importUser, $rowData,$row);
                }else{
                    echo "\033[102m班级用户《".$rowData['userName']."》创建失败跳过\033[0m\n";
                    $this->info("班级用户《".$rowData['userName']."》创建失败跳过");
                }

            }
        }
    }

    /**
     * @param $row
     * @param string $result
     * @param string $tableName
     * @param string $target
     * @param int $type
     * @param int $status
     * @return MessageBag
     */
    public function log($schoolId, $row, $result='', $tableName='', $target='', $type=3, $status=ImoprtLog::FAIL_STATUS)
    {
        if (!$this->importDao->getLog(md5(json_encode($row)))) {
            return $this->importDao->writeLog([
                'type' => $type,
                'source' => json_encode($row),
                'target' => $target?json_encode($target):'',
                'table_name'=> $tableName,
                'result'=> $result?json_encode($result):'',
                'task_id' => $this->config['task_id'],
                'task_status' => $status,
                'school_id' => $schoolId,
                'only_flag' => md5(json_encode($row))
            ]);
        }

    }

    public function saveStudentProfile($schoolId, $user, $rowData,$row)
    {
        //$student = new StudentProfile();
        $studentDao = new StudentProfileDao();
        $student = $studentDao->getStudentInfoByUserId($user->id);
        if (empty($student)) {
            $student = new StudentProfile();
        }
        $student->user_id = $user->id;
        $student->uuid = Uuid::uuid4()->toString();
        $student->year = $rowData['year'];
        $student->serial_number = "-";
        if ($rowData['gender'] == '男'){
            $gender = 1;
        }else {
            $gender = 2;
        }
        $student->gender = $gender;
        $student->country = $rowData['country'];
        $student->state = $rowData['state'];
        $student->city = $rowData['city'];
        $student->postcode = $rowData['postCode'];
        $student->area = $rowData['area'];
        $student->address_line = $rowData['addressLine'];
        $student->id_number = $rowData['idNumber'];
        $student->birthday = strtotime(substr($rowData['idNumber'],6,8));
        $student->political_code = isset($rowData['politicalName'])?$rowData['politicalName']:'';
        $student->nation_name = $rowData['nation'];
        $student->parent_name = "-";
        $student->parent_mobile = "-";
        $student->avatar = "";
        $result = $student->save();
        if ($result) {
            $studentDao = new StudentProfileDao();
            $student = $studentDao->getStudentInfoByUserId($user->id);
            $this->log($schoolId,$row, json_encode($student), 'student_profile', '', 1, ImoprtLog::SUCCESS_STATUS);

            return $student;
        } else {
            $this->log($schoolId, $row, '', 'student_profile', '', 1, ImoprtLog::FAIL_STATUS);
            return false;
        }

    }

    public function saveGradeUser($user, $rowData,$schoolId, $row)
    {
        $gradeUserDao = new GradeUserDao();
        $gradeUserObj =  $gradeUserDao->getUserInfoByUserId($user->id);
        if ($gradeUserObj) {
//            $gradeUserObj->user_type = $user->type;
//            $gradeUserObj->save();
            return $gradeUserObj;
        } else {
            $schoolObj = $this->getSchool($schoolId);
            $campus = $schoolObj->campuses()->first();
            $gradeUser = $gradeUserDao->create([
                'user_id' => $user->id,
                'user_type' => $user->type,
                'name' => $rowData['userName'],
                'school_id' => $schoolId,
                'campus_id' => $campus->id,
                'institute_id' => 0,
                'department_id' => 0,
                'major_id' => 0,
                'grade_id' => 0,
                'last_updated_by' => 0,
            ]);
            if ($gradeUser) {
                $gradeUser = $gradeUserDao->getUserInfoByUserId($user->id);
                $this->log($schoolId, $row, $gradeUser, 'grade_users', '', 1, ImoprtLog::SUCCESS_STATUS);

                return $gradeUser;
            } else {
                $this->log($schoolId, $row, '', 'grade_users', '', 1, ImoprtLog::FAIL_STATUS);
                return false;
            }
        }
    }

    /**
     * 教育局每年会将所有当年毕业的学生数据发给职专，这些学生导入时还没有选专业，所以类型定为已注册未验证
     * @param $schoolId
     * @param $mobile
     * @param $name
     * @param $passwdTxt
     * @param $row
     * @return User|bool|mixed
     * @throws Exception
     */
    public function saveUser($schoolId, $mobile, $name, $passwdTxt,$row)
    {
        $userDao = new UserDao();
        $importUser =  $userDao->getUserByMobile($mobile);
        if ($importUser) {
            return $importUser;
        } else {
            $importUser = $userDao->importUser($mobile,$name,$passwdTxt,Role::REGISTERED_USER,User::STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED);
            if ($importUser) {
                return $importUser;
            } else {
                $this->log($schoolId, $row, '', 'users', '', 1, ImoprtLog::FAIL_STATUS);
                return false;
            }
        }
    }





}
