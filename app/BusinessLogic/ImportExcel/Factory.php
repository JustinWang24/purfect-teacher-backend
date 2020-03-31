<?php


namespace App\BusinessLogic\ImportExcel;

use App\BusinessLogic\ImportExcel\Impl\ImporterUsers;

class Factory
{
    public static function createAdapter(array $configArr)
    {

        $instance = null;
        if (!isset($configArr['importerName'])) {
            $instance = new ImporterUsers($configArr);
        } else {
            $adapterName = $configArr['importerName'];
            $instance = new $adapterName($configArr);
        }


        return $instance;
    }
}
