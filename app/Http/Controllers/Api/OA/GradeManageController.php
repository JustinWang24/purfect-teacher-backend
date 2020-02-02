<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\Schools\GradeManagerDao;
use App\Dao\Schools\GradeResourceDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Schools\GradeResource;
use App\Utils\JsonBuilder;

class GradeManageController extends Controller
{

    /**
     * 获取班级
     * @param MyStandardRequest $request
     * @return string
     */
    public function index(MyStandardRequest $request)
    {
        $teacher = $request->user();

        $dao = new GradeManagerDao;
        $grades = $dao->getAllGradesByAdviserId($teacher->id);
        $data = [];
        foreach ($grades as $key => $val) {
             $data[$key]['grade_id'] = $val->grade->id;
             $data[$key]['name'] = $val->grade->name;

             $data[$key]['image'] = [];
             foreach ($val->grade->gradeResource as $k => $v) {
                $data[$key]['image'][$k]['image_id'] = $v->id;
                $data[$key]['image'][$k]['path'] = $v->path;
             }
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 上传班级风采
     * @param MyStandardRequest $request
     * @return string
     */
    public function uploadGradeResource(MyStandardRequest $request)
    {
         $gradeId = $request->get('grade_id');
         $file = $request->file('file');
         $data['grade_id'] = $gradeId;
         $data['name'] = $file->getClientOriginalName();
         $data['type'] = $file->extension();
         $data['size'] = getFileSize($file->getSize());
         $data['path'] = GradeResource::gradeResourceUploadPathToUrl($file->store(GradeResource::DEFAULT_UPLOAD_PATH_PREFIX));

         $dao = new GradeResourceDao;
         $result = $dao->create($data);
         if($result) {
             return JsonBuilder::Success('上传成功');
         } else {
             return JsonBuilder::Error('上传失败');
         }
    }

    /**
     * 刪除班級风采
     * @param MyStandardRequest $request
     * @return string
     */
    public function delGradeResource(MyStandardRequest $request)
    {
        $id = $request->get('image_id');
        $dao  = new GradeResourceDao;
        $result = $dao->delete($id);
        if ($result) {
            return JsonBuilder::Success('删除成功');
        } else {
            return JsonBuilder::Error('删除失败');
        }
    }


}
