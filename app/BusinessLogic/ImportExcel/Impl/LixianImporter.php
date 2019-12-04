<?php


namespace App\BusinessLogic\ImportExcel\Impl;


use App\BusinessLogic\ImportExcel\Contracts\IImportExcel;
use App\Dao\Users\UserDao;

class LixianImporter extends AbstractImporter
{
    public function handle()
    {
        $this->loadExcelFile();
        //检测配置文件
        $config = $this->getConfig();
        $userDao = new UserDao();
        $user = $userDao->getUserById($config['manager_id']);
        //获取学校对象
        $schoolObj = $this->getSchoolId($user);
        echo '学校《'.$schoolObj->name.'》资料获取成功<br>';
        $data = $this->getData();
        $sheetIndexArray = $this->getSheetIndexArray();

        foreach($sheetIndexArray as $sheetIndex)
        {
            //获取当前sheet的配置
            $sheetConfig = $config['school']['sheet'][$sheetIndex];
            if (empty($sheetConfig)) {
                echo '配置为空，跳过第'.$sheetIndex.'个sheet<br>';
                continue;
            }

            $sheetData = $this->getSheetData($sheetIndex);
            echo '获取到第'.$sheetIndex.'个sheet数据开始循环<br>';
            foreach ($sheetData as $key => $row)
            {
                if ($key<$sheetConfig['dataRow'])
                    continue;

                $rowData = $this->getRowData($sheetIndex, $row);
                echo '获取到一行资料'.json_encode($row,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES).'<br>';
                //手机号不能为空
                if (empty($rowData['mobile']) || strlen($rowData['mobile'])!=11) {
                    $this->writeLog($row);
                    echo '手机号为空或者位数不对跳过<br>';
                    continue;
                }

                //判断是否有学院
                if (!isset($rowData['institute'])) {
                    $rowData['institute'] = '默认';
                }
                if (!isset($rowData['department'])) {
                    $rowData['department'] = '默认';
                }
                $institute = $this->getInstitute($user, $rowData['institute'], $schoolObj->id);
                if ($institute) {
                    $department = $this->getDepartment($user, $rowData['department'], $schoolObj->id, $institute);
                    if ($department) {
                        $major = $this->getMajor($user, $rowData['major'], $schoolObj->id, $institute, $department);
                        if ($major) {
                            $rowData['grade'] = explode('-',$rowData['grade'])[0];
                            $rowData['year'] = substr($rowData['year'],0,4);
                            $grade = $this->getGrade($user, $rowData['grade'], $schoolObj->id, $major, $rowData['year']);
                            if ($grade) {
                                $passwordInPlainText = substr($rowData['idNumber'],-4);
                                $importUser = $this->getUser($rowData['mobile'], $rowData['userName'], $passwordInPlainText,$row);
                                if ($importUser) {
                                    $gradeUser = $this->getGradeUser($importUser, $rowData,$schoolObj->id, $institute, $department, $major, $grade, $row);
                                }else{
                                    echo '班级用户《'.$rowData['userName'].'》创建失败跳过<br>';
                                }
                            }else{
                                echo '班级《'.$rowData['grade'].'》创建失败跳过<br>';
                            }
                        }else{
                            echo '专业《'.$rowData['major'].'》创建失败跳过<br>';
                        }
                    }else{
                        echo '系《'.$rowData['department'].'》创建失败跳过<br>';
                    }
                }else{
                    echo '学院《'.$rowData['institute'].'》创建失败跳过<br>';
                }
            }
        }
    }
}
