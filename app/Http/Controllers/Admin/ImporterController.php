<?php


namespace App\Http\Controllers\Admin;


use App\BusinessLogic\ImportExcel\Factory;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class ImporterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function manager(Request $request)
    {
        $obj = Factory::createAdapter(
            ['importerName'=>'App\BusinessLogic\ImportExcel\Impl\LixianImporter',
             'file_path'   => '技术部加班表-11月-朱晨光.xlsx',
            ]);
        $obj->loadExcelFile();
        dd($obj->data->toArray());

        //Artisan::call('命令名称');

    }
}
