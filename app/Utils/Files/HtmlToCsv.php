<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/11/19
 * Time: 3:30 PM
 */

namespace App\Utils\Files;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class HtmlToCsv
{
    const TMP_FILE_DIR = 'app/public/tmp/';

    public static function Convert($viewPath, $viewData){
        try{
            $path = storage_path(self::TMP_FILE_DIR . Str::random().'.xls');
            if(!is_dir(storage_path(self::TMP_FILE_DIR))){
                mkdir(storage_path(self::TMP_FILE_DIR));
            }
            $html = View::make($viewPath,$viewData)->render();
            $reader = new Html();
            $spreadsheet = $reader->loadFromString($html);
            $writer = IOFactory::createWriter($spreadsheet,'Xls');
            $writer->save($path);
            return $path;
        }catch (\Exception $exception){
            Log::info('导出错误',['msg'=>$exception->getMessage()]);
            return null;
        }
    }
}