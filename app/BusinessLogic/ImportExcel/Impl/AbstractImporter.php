<?php


namespace App\BusinessLogic\ImportExcel\Impl;


use App\BusinessLogic\ImportExcel\Contracts\IImportExcel;
use App\Dao\Importer\ImporterDao;
use App\Dao\Schools\SchoolDao;
use League\Flysystem\Config;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class AbstractImporter implements IImportExcel
{
    protected $config;
    public $data;
    public function __construct($configArr)
    {
        $this->config = $configArr;
        $this->data   = [];
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
            $importDao = new ImporterDao();
            if ($schoolObj) {
                $importDao->writeLog([
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

}
