<?php


namespace App\Http\Controllers\Admin;


use App\BusinessLogic\ImportExcel\Factory;

class ImporterController
{

    public function manager()
    {
        $obj = Factory::createAdapter(
            ['importerName'=>'App\BusinessLogic\ImportExcel\Impl\LixianImporter',
             'file_path'    => '技术部加班表-11月-朱晨光.xlsx',
            ]);
        $obj->loadExcelFile();
        dd($obj->data);
    }
}
