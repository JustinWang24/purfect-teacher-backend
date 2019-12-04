<?php


namespace App\BusinessLogic\ImportExcel\Impl;


use App\BusinessLogic\ImportExcel\Contracts\IImportExcel;
use App\Dao\Importer\ImporterDao;
use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\InstituteDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use League\Flysystem\Config;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class AbstractImporter implements IImportExcel
{
    protected $config;
    protected $data;
    protected $header;
    protected $school;
    protected $importDao;
    protected $skipColoumn = ['startRow', 'dataRow'];
    public function __construct($configArr)
    {
        $this->config = $configArr;
        $this->data   = [];
        $this->importDao = new ImporterDao();
    }

    /**
     *
     */
    public function loadExcelFile()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $filePath = config('filesystems.disks.apk')['root'].DIRECTORY_SEPARATOR .$this->config['file_path'];

        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load($filePath);  //$filename可以是上传的表格，或者是指定的表格
        $worksheet = $objPHPExcel->getAllSheets();
        $this->data = $worksheet;
    }

    public function getSchoolId($user)
    {
        $schoolName = $this->config['school']['schoolName'];

        $dao = new SchoolDao($user);
        $schoolObj = $dao->getSchoolByName($schoolName);
        if (!$schoolObj) {
            $schoolObj = $dao->createSchool(['name'=>$schoolName]);
            if ($schoolObj) {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => $schoolName,
                    'table_name'=> 'schools',
                    'result' => json_encode($schoolObj),
                    'task_id' => $this->config['task_id'],
                    'task_status' => 1,
                ]);
            }
        }
        return $schoolObj;
    }
    protected function setSchool($school)
    {
        $this->school = $school;
    }
    protected function getSchool($schoolId)
    {
        if ($this->school) {
            return $this->school;
        } else {
            $schoolDao = new SchoolDao();
            $schoolObj = $schoolDao->getSchoolById($schoolId);
            $this->setSchool($schoolObj);
            return $schoolObj;
        }
    }
    public function getConfig()
    {
        return $this->config;
    }
    public function getData()
    {
        return $this->data;
    }
    public function getSheetIndexArray()
    {
        return array_keys($this->data);
    }
    public function getSheetData($sheetIndex)
    {
        return $this->data[$sheetIndex]->toArray();
    }


    public function getColoumn($row, $coloumn, $sheetId)
    {
        $header = $this->getHeader($sheetId);
        $defaultValueArr = [];
        $data = $this->getColoumnIndex($coloumn, $header, $sheetId);
        if (empty($data) || empty($data[0])) {
            return $this->getDefaultValue($coloumn, $header, $sheetId);
        } else {
            foreach ($data as $index) {
                $defaultValueArr[] = $row[$index];
            }
            $config = $this->config['school']['sheet'][$sheetId][$coloumn];
            return implode($defaultValueArr, $config['joinSymbol']);
        }
    }

    public function getColoumnIndex($coloumn, $header, $sheetId)
    {
        $config = $this->config['school']['sheet'][$sheetId][$coloumn];
        $data = [];
        if (!empty($config['coloumnName']))
        {
            foreach ($config['coloumnName'] as $item){
                $data[] = $this->getIndex($item, $header);
            }
            return $data;
        }
    }

    public function getDefaultValue($name, $header, $sheetId)
    {
        $config = $this->config['school']['sheet'][$sheetId][$name];
        if ($config['isEmpty']=='false')
        {
            return $config['defaultValue'];
        } else {
            return '';
        }
    }

    public function getIndex($name, $array)
    {
        foreach ($array as $key=>$value){
            if ($name == $value)
                return $key;
        }
    }

    public function getHeader($sheetId)
    {
        $header = $this->header;
        if (empty($header)) {
            $data = $this->getSheetData($sheetId);
            $sheetConfig = $this->config['school']['sheet'][$sheetId];
            $header = $data[$sheetConfig['startRow']];
            if (empty($header)) {
                exit('文件头获取失败，请检查配置');
            }
            $this->setHeader($header);
            return $header;
        } else {
            return $header;
        }
    }
    public function setHeader($header)
    {
        $this->header = $header;
    }


    public function getRowData($sheetId, $row)
    {
        $data = [];
        $sheetConfig = $this->config['school']['sheet'][$sheetId];
        foreach ($sheetConfig as $key=>$value)
        {
            if (in_array($key, $this->skipColoumn))
                continue;
            $data[$key] = $this->getColoumn($row, $key, $sheetId);
        }
        return $data;
    }

    public function getInstitute($user, $name, $schoolId)
    {
        $instituteDao = new InstituteDao($user);
        $instituteObj =  $instituteDao->getByName($name, $schoolId);
        if ($instituteObj) {
            return $instituteObj;
        } else {
            $schoolObj = $this->getSchool($schoolId);
            $campus = $schoolObj->campuses()->first();

            $institute = $instituteDao->createInstitute([
                'school_id' => $schoolId,
                'campus_id' => $campus->id,
                'name'      => $name,
                'description'      => $name,
            ]);
            if ($institute) {
                $this->importDao->writeLog([
                        'type' =>1,
                        'source' => $name,
                        'table_name'=> 'institutes',
                        'result' => json_encode($institute),
                        'task_id' => $this->config['task_id'],
                        'task_status' => 1,
                    ]);
                return $institute;
            } else {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => $name,
                    'table_name'=> 'institutes',
                    'task_id' => $this->config['task_id'],
                    'task_status' => -1,
                ]);
                return false;
            }
        }
    }
    public function getDepartment($user, $name, $schoolId, $institute)
    {
        $departmentDao = new DepartmentDao($user);
        $schoolObj = $this->getSchool($schoolId);
        $campus = $schoolObj->campuses()->first();
        $departmentObj =  $departmentDao->getByName($name, $schoolId,$campus->id,$institute->id);
        if ($departmentObj) {
            return $departmentObj;
        } else {
            $department = $departmentDao->createDepartment([
                'school_id' => $schoolId,
                'campus_id' => $campus->id,
                'institute_id' => $institute->id,
                'name'      => $name,
                'description' => $name,
            ]);
            if ($department) {
                $this->importDao->writeLog([
                        'type' =>1,
                        'source' => $name,
                        'table_name'=> 'departments',
                        'result' => json_encode($department),
                        'task_id' => $this->config['task_id'],
                        'task_status' => 1,
                    ]);
                return $department;
            } else {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => $name,
                    'table_name'=> 'departments',
                    'task_id' => $this->config['task_id'],
                    'task_status' => -1,
                ]);
                return false;
            }
        }
    }
    public function getMajor($user, $name, $schoolId, $institute, $department)
    {
        $majorDao = new MajorDao($user);
        $magorObj =  $majorDao->getByName($name, $schoolId, $institute->id, $department->id);
        if ($magorObj) {
            return $magorObj;
        } else {
            $schoolObj = $this->getSchool($schoolId);
            $campus = $schoolObj->campuses()->first();

            $major = $majorDao->createMajor([
                'school_id' => $schoolId,
                'campus_id' => $campus->id,
                'institute_id' => $institute->id,
                'department_id' => $department->id,
                'name'      => $name,
                'description' => $name,
            ]);
            if ($major) {
                $this->importDao->writeLog([
                        'type' =>1,
                        'source' => $name,
                        'table_name'=> 'majors',
                        'result' => json_encode($major),
                        'task_id' => $this->config['task_id'],
                        'task_status' => 1,
                    ]);

                return $major;
            } else {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => $name,
                    'table_name'=> 'majors',
                    'task_id' => $this->config['task_id'],
                    'task_status' => -1,
                ]);
                return false;
            }
        }
    }
    public function getGrade($user, $name, $schoolId, $major, $year)
    {
        $gradeDao = new GradeDao($user);
        $gradeObj =  $gradeDao->getByName($name, $schoolId, $major->id, $year);
        if ($gradeObj) {
            return $gradeObj;
        } else {
            $schoolObj = $this->getSchool($schoolId);
            $grade = $gradeDao->createGrade([
                'school_id' => $schoolId,
                'major_id' => $major->id,
                'year' => $year,
                'name'      => $name,
                'description' => $name,
            ]);
            if ($grade) {
                $this->importDao->writeLog([
                        'type' =>1,
                        'source' => $name,
                        'table_name'=> 'grades',
                        'result' => json_encode($grade),
                        'task_id' => $this->config['task_id'],
                        'task_status' => 1,
                    ]);

                return $grade;
            } else {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => $name,
                    'table_name'=> 'grades',
                    'task_id' => $this->config['task_id'],
                    'task_status' => -1,
                ]);
                return false;
            }
        }
    }
    public function getGradeUser($user, $rowData,$schoolId, $institute, $department, $major, $grade, $row)
    {
        $gradeUserDao = new GradeUserDao();
        $gradeUserObj =  $gradeUserDao->getUserInfoByUserId($user->id);
        if ($gradeUserObj) {
            return $gradeUserObj;
        } else {
            $schoolObj = $this->getSchool($schoolId);
            $campus = $schoolObj->campuses()->first();
            $gradeUser = $gradeUserDao->addGradUser([
                'user_id' => $user->id,
                'name' => $rowData['userName'],
                'school_id' => $schoolId,
                'campus_id' => $campus->id,
                'institute_id' => $institute->id,
                'department_id' => $department->id,
                'major_id' => $major->id,
                'grade_id' => $grade->id,
                'last_updated_by' => 0,
            ]);
            if ($gradeUser) {
                $gradeUser = $gradeUserDao->getUserInfoByUserId($user->id);
                $this->importDao->writeLog([
                        'type' =>1,
                        'source' => json_encode($row),
                        'table_name'=> 'grade_users',
                        'result' => json_encode($gradeUser),
                        'task_id' => $this->config['task_id'],
                        'task_status' => 1,
                    ]);

                return $gradeUser;
            } else {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => json_encode($row),
                    'table_name'=> 'grade_users',
                    'task_id' => $this->config['task_id'],
                    'task_status' => -1,
                ]);
                return false;
            }
        }
    }
    public function getUser($mobile, $name, $passwdTxt,$row)
    {
        $userDao = new UserDao();
        $importUser =  $userDao->getUserByMobile($mobile);
        if ($importUser) {
            return $importUser;
        } else {
            $importUser = $userDao->importUser($mobile,$name,$passwdTxt);
            if ($importUser) {
                $this->importDao->writeLog([
                        'type' =>1,
                        'source' => json_encode($row),
                        'table_name'=> 'users',
                        'result' => json_encode($importUser),
                        'task_id' => $this->config['task_id'],
                        'task_status' => 1,
                    ]);

                return $importUser;
            } else {
                $this->importDao->writeLog([
                    'type' =>1,
                    'source' => json_encode($row),
                    'table_name'=> 'users',
                    'task_id' => $this->config['task_id'],
                    'task_status' => -1,
                ]);
                return false;
            }
        }
    }

    public function writeLog($row, $tableName='', $target='', $result='', $type=3, $status=-1)
    {
       return $this->importDao->writeLog([
            'type' => $type,
            'source' => json_encode($row),
            'target' => $target?json_encode($target):'',
            'table_name'=> $tableName,
            'result'=> $result?json_encode($result):'',
            'task_id' => $this->config['task_id'],
            'task_status' => $status,
        ]);
    }

}
