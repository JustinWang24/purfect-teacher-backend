<?php

namespace App\Http\Controllers\Api\NetworkDisk;

use App\BusinessLogic\NetworkDriveLogics\Factory;
use App\Dao\NetworkDisk\CategoryDao;
use App\Dao\NetworkDisk\MediaDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkDisk\CategoryRequest;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{

    /**
     * 创建目录
     * @param CategoryRequest $request
     * @return string
     * @throws \Exception
     */
    public function create(CategoryRequest $request) {
        $return = $request->getCreate();
        if(!$return->isSuccess()) {
            return JsonBuilder::Error($return->getMessage());
        }
        $data = $return->getData();
        $categoriesDao = new CategoryDao();
        $category = $categoriesDao->create($data);

        if($category) {
            return JsonBuilder::Success(['uuid'=>$category->uuid]);
        } else {
            return JsonBuilder::Error('创建失败');
        }
    }


    /**
     * 编辑目录
     * @param CategoryRequest $request
     * @return string
     */
    public function edit(CategoryRequest $request) {
        $uuid = $request->getCateUuId();
        $data = $request->getEdit();
        $categoriesDao = new CategoryDao();
        $re = $categoriesDao->update($uuid,$data);
        if($re) {
            return JsonBuilder::Success('编辑成功');
        } else {
            return JsonBuilder::Error('编辑失败');
        }
    }

    /**
     * 查询目录下的列表
     * @param CategoryRequest $request
     * @return string
     */
    public function view(CategoryRequest $request) {
        /**
         * @var User $user
         */
        $user = $request->user();
        $uuid = $request->getCateUuId();
        $logic = Factory::GetCategoryLogic($user, $uuid);
        $data = $logic->getData();
        return $data ? JsonBuilder::Success($data) : JsonBuilder::Error('目录不存在');
    }

    //删除
    public function delete(CategoryRequest $request) {
        $uuid = $request->getCateUuId();
        $categoriesDao = new CategoryDao();
        $cateInfo = $categoriesDao->getCateInfoByUuId($uuid);

        try{
            DB::beginTransaction();
            $re = $categoriesDao->deleteCategory($cateInfo);

            DB::commit();
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            DB::rollBack();
        }

        if($re) {
            return JsonBuilder::Success('删除成功');
        } else {
            return JsonBuilder::Error('删除失败'.$msg);
        }

    }
}

