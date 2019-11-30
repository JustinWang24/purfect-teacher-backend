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
    }

    /**
     *
     */
    public function loadExcelFile()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        //$filePath = $this->config['path'];
        $filePath = config('filesystems.disks.apk')['root'].'/技术部加班表-11月-朱晨光.xlsx';
        Excel::load($filePath, function($reader) {
            $data = $reader->all()->toArray();
            dd($data);
            foreach($data as $key=>$value)
            {
                foreach ($value as $k=>$v)
                {
                    dd($v);
                }
            }
            return $data;
        });
        // TODO: Implement loadExcelFile() method.
    }


}
