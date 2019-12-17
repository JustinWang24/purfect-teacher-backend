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
        echo '学校《'.$schoolObj->name.'》资料获取成功\n';
        $data = $this->getData();
        $sheetIndexArray = $this->getSheetIndexArray();

        foreach($sheetIndexArray as $sheetIndex)
        {
            //获取当前sheet的配置
            $sheetConfig = $config['school']['sheet'][$sheetIndex];
            if (empty($sheetConfig)) {
                echo "\033[38;33;1;101m配置为空，跳过第".$sheetIndex."个sheet\033[0m\n";
                continue;
            }

            $sheetData = $this->getSheetData($sheetIndex);
            echo '获取到第'.$sheetIndex.'个sheet数据开始循环<br>';
            foreach ($sheetData as $key => $row)
            {
                if ($key<$sheetConfig['dataRow'])
                    continue;

                $rowData = $this->getRowData($sheetIndex, $row);
                //echo '获取到一行资料'.json_encode($rowData,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES).'\n';
                //手机号不能为空
                if (empty($rowData['mobile']) || strlen($rowData['mobile'])!=11) {
                    $this->writeLog($row,'手机号格式错误');
                    echo "\033[38;39;1;101m".$rowData['mobile']."手机号为空或者位数不对跳过\033[0m\n";
                    continue;
                }
                if (empty($rowData['idNumber']) || strlen($rowData['idNumber'])!=18) {
                    $this->writeLog($row,'身份证号格式错误');
                    echo "\033[38;39;1;101m".$rowData['idNumber']."身份证号为空或者位数不对跳过\033[0m\n";
                    continue;
                }

                //判断是否有学院
                if (!isset($rowData['institute'])) {
                    $rowData['institute'] = '默认';
                }
                if (!isset($rowData['department'])) {

                    $data = [
                        '现代农艺技术'=>'农林牧渔',
                        '老年人服务与管理'=>'医药卫生',
                        '护理'=>'医药卫生',
                        '酒店服务与管理'=>'旅游服务',
                        '机械制造技术'=>'加工制造',
                        '汽车运用与维修'=>'交通运输',
                        '电子技术应用'=>'加工制造',
                        '计算机应用'=>'信息技术',
                        '学前教育'=>'文化教育',
                        '会计电算化'=>'财经商贸',
                        '建筑工程施工'=>'土木水利',
                        '高星级饭店运营与管理'=>'旅游服务',
                        '文秘'=>'文化教育',
                        '旅游服务与管理'=>'旅游服务',
                        '汽车制造与检修'=>'交通运输',
                        '学前教育'=>'文化教育',
                    ];
                    $rowData['department'] = $data[$rowData['major']]??'默认';
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
                                    $studentUser = $this->saveStudent($user, $rowData,$row);
                                }else{
                                    echo "\033[102m班级用户《".$rowData['userName']."》创建失败跳过\033[0m\n";
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
