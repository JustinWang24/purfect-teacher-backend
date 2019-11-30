<?php


namespace App\Http\Controllers\Admin;


use App\BusinessLogic\ImportExcel\Factory;

class ImporterController
{

    public function test()
    {
        $obj = Factory::createAdapter(['importerName'=>'App\BusinessLogic\ImportExcel\Impl\LixianImporter']);
        $obj->loadExcelFile();
    }
}
