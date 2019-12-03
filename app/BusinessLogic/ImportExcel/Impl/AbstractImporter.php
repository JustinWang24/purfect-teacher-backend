<?php


namespace App\BusinessLogic\ImportExcel\Impl;


use App\BusinessLogic\ImportExcel\Contracts\IImportExcel;
use League\Flysystem\Config;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class AbstractImporter implements IImportExcel
{
    protected $config;
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
        $worksheet = $objPHPExcel->getActiveSheet();

        $this->data = $worksheet;
    }


}
