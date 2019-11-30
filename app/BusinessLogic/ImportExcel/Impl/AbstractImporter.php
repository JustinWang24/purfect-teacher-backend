<?php


namespace App\BusinessLogic\ImportExcel\Impl;


use App\BusinessLogic\ImportExcel\Contracts\IImportExcel;
use League\Flysystem\Config;
use Maatwebsite\Excel\Facades\Excel;

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
        Excel::load($filePath, function($reader) {
            $data = $reader->all()->toArray();
            $this->data = $data;
            return true;
        });
    }


}
