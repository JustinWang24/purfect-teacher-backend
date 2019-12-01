<?php


namespace App\BusinessLogic\ImportExcel;

class Factory
{
    public static function createAdapter(array $configArr)
    {
        $instance = null;

        $adapterName = $configArr['importerName'];

        $instance = new $adapterName($configArr);

        return $instance;
    }
}
