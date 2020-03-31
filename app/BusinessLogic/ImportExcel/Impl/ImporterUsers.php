<?php

namespace App\BusinessLogic\ImportExcel\Impl;

use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Models\Acl\Role;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;


class ImporterUsers extends AbstractImporter
{
    protected $task;
    protected $data;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function handle()
    {
        $userDao = new UserDao;
        $profileDao = new StudentProfileDao;
        $gradeUserDao = new GradeUserDao;
        $this->loadExcelFile();
        $sheetIndexArray = $this->getSheetIndexArray();
        $str = "文件数据格式有问题请重新上传, 第";
        foreach($sheetIndexArray as $sheetIndex) {
            echo '已拿到第'. ($sheetIndex+1).' sheet的数据 开始循环.....'.PHP_EOL;
            $sheetData = $this->getSheetData($sheetIndex);
            echo '开始检查文件格式是否正确'.PHP_EOL;
            // 检查文件格式是否正确
            foreach ($sheetData[0] as $key => $val) {
                if ($this->fileFormat()[$key] != $val) {
                    echo  $str. ($key+1) . "列应该是". $this->fileFormat()[$key];die;
                }
            }
            echo '检查文件格式正确 开始循环....'.PHP_EOL;
            $student = [];
            $profile = [];
//            dd($sheetData[0]);
            unset($sheetData[0]); // 去掉文件头
            foreach ($sheetData as $key => $val) {
                if ($val[1] == '男') {
                    $sex = 1;
                } else {
                    $sex = 0;
                }

                // 临时这样写有时间再优化
                if ($val[9] == '是') {
                    $createFile = 1; // 是否建档
                } else {
                    $createFile = 0;
                }

                if ($val[10] == '是') {
                    $specialSupport = 1; // 是否农村低保
                } else {
                    $specialSupport = 0;
                }

                if ($val[11] == '是') {
                    $veryPoor = 1; // 是否农村特困
                } else {
                    $veryPoor = 0;
                }

                if ($val[12] == '是') {
                    $disability = 1; // 是否残疾
                }  else {
                    $disability = 0;
                }

                $student = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'name' => $val[0],
                    'mobile' => $val[8],
                    'password' => Hash::make(substr($val['3'],-6)),
                    'type' => Role::REGISTERED_USER,
                    'status' => User::STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED,
                ];

                $profile = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'user_id' => '',
                    'id_number' => $val[3],
                    'year' => substr($val[7],0,4), // 截取 级
                    'serial_number' => '-',
                    'gender'  => $sex,
                    'area' => $val[5],
                    'address_line' => $val[6],
                    'birthday' => $this->getBirthday($val[3]),
                    'nation_name' => $val[2],
                    'create_file' => $createFile,
                    'special_support' => $specialSupport,
                    'very_poor' => $veryPoor,
                    'disability' => $disability
                ];

                // 手机号不能为空
                if (empty($student['mobile']) || strlen($student['mobile'])!= 11 ) {
                    // $this->errorLog($val,'手机号格式错误');
                    echo $val[0]."手机号为空或者位数不对 跳过".PHP_EOL;
                    continue;
                }
                // 身份证
                if (empty($profile['id_number']) || strlen($profile['id_number'])!= 18) {
                    // $this->errorLog($val,'身份证号格式错误');
                    echo $val[0]."身份证号为空或者位数不对 跳过".PHP_EOL;
                    continue;
                }

                $profileResult = $profileDao->getStudentInfoByIdNumber($val[3]);
                $userResult = $userDao->getUserByMobile($val[8]);
                if ($userResult) {
                    echo $val[0]. "手机号已经被注册了 跳过此人".PHP_EOL;
                    continue;
                }
                if ($profileResult) {
                    echo $val[0]. "身份证已经被注册了 跳过此人".PHP_EOL;
                    continue;
                }

                DB::beginTransaction();

                try{
                    // 创建用户数据
                    // 创建用户班级的关联
                    // 创建用户的档案

                    $user = $userDao->createUser($student);
                    $profile['user_id'] = $user->id;
                    $profileDao->create($profile);
                    $gradeData = [
                        'user_id' => $user->id,
                        'name' => $student['name'],
                        'user_type' => 1, // 学生
                        'school_id' => $this->task['school_id']

                    ];
                    $gradeUserDao->create($gradeData);

                    DB::commit();
                    echo $val[0].'----------创建成功'.PHP_EOL;
                }
                catch (\Exception $exception){
                    DB::rollBack();
                }
            }

        }

    }

    /**
     * 获取 excel 文件
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadExcelFile()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $filePath = config('filesystems.disks.import')['root'].DIRECTORY_SEPARATOR .$this->task['file_path'];
        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load($filePath);
        $worksheet = $objPHPExcel->getAllSheets();
        $this->data = $worksheet;
    }

    /**
     * 错误记录
     * @param $data
     * @param $log
     */
    public function errorLog($data, $log)
    {
        // todo :: 错误记录
    }


    /**
     * 文件格式标准
     */
    public function fileFormat()
    {
        return [
            "姓名",
            "性别",
            "民族",
            "身份证号码",
            "户籍性质",
            "户籍所在乡镇",
            "户籍所在村",
            "年级",
            "学生电话",
            "是否建档立卡",
            "是否农村低保",
            "是否农村特困",
            "是否残疾"
        ];
    }


    /**
     * 根据身份证 获取 出生日期
     * @param $idCard
     * @return string
     */
    public function getBirthday($idCard)
    {
        $bir = substr($idCard, 6, 8);
        $year = (int) substr($bir, 0, 4);
        $month = (int) substr($bir, 4, 2);
        $day = (int) substr($bir, 6, 2);
        return $year . "-" . $month . "-" . $day;
    }

}
