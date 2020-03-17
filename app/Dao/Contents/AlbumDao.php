<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/1/20
 * Time: 4:21 PM
 */

namespace App\Dao\Contents;

use App\Dao\Schools\SchoolDao;
use App\Models\Contents\Album;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AlbumDao
{
    /**
     * @param $schoolId
     * @return Collection
     */
    public function getAllBySchool($schoolId){
        return Album::where('school_id', $schoolId)->get();
    }

    public function getById($id){
        return Album::find($id);
    }

    /**
     * @param Request $request
     * @return MessageBag
     */
    public function create(Request $request){
        $bag = new MessageBag();
        $school = (new SchoolDao())->getSchoolById($request->get('album')['school_id']);

        // 保存文件
        $file = $request->file('file');
        $albumData = $request->get('album');
        // 课件使用一个特殊的单独目录进行保存: /storage/course_material/course_id/teacher_id/
        $base = 'app/public/school_album';
        $folderPath = storage_path($base);
        if(!file_exists($folderPath)){
            mkdir($folderPath, 0777, true);
        }

        if($file){
            $filePath = $file->storeAs(
                'public/school_album',
                $file->getClientOriginalName()
            );
            $url = str_replace(
                'public',
                'storage',
                $filePath);
            $albumData['url'] = $url;
            Album::create($albumData);
            $bag->setData($school->uuid);
            $bag->setMessage('保存成功');
        }
        return $bag;
    }

  /**
   * Func 删除之前的附件信息
   * @param $param
   * @return bool
   */
  public function deleteListInfo($param = []){
    if (isset($param['school_id'])) {
      $condition[] = ['school_id', '=', (Int)$param['school_id']];
    }
    if (isset($param['type'])) {
      $condition[] = ['type', '=', (Int)$param['type']];
    }
    if (empty($condition)) return false;
    return Album::where($condition)->delete();
  }
}
