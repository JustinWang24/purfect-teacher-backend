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
        $re = $categoriesDao->create($data);

        if($re) {
            return JsonBuilder::Success($re);
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
        $categoriesDao = new CategoryDao();
        $mediaDao = new MediaDao();

        /**
         * @var User $user
         */
//        $user = $request->user();
        
        $uuid = $request->getCateUuId();

//        $logic = Factory::GetCategoryLogic($user, $uuid);

//        $category = $logic->getCategoryByUuid($uuid);

//        $category = $uuid ? $categoriesDao->getCateInfoByUuId($uuid) : $user->networkDiskRoot;

        $userId = $request->user()['id'];

        $cateInfo = $categoriesDao->getCateInfoByUuId($uuid);

        $categoryList = $categoriesDao->getCateByOwnerIdAndParentId($userId, $cateInfo['id']);
        $cateInfo['children'] = $categoryList;
        $files = $mediaDao->getMediaByCategoryId($cateInfo['id']);
        $parent = [];
        if($cateInfo['type'] == 1) {
            $parent = $categoriesDao->getCateInfoById($cateInfo['parent_id']);
        }
        $cateInfo['parent'] = $parent;
        $cateInfo['files'] = $files;
        $data = ['category'=>$cateInfo];

//        $data = [
//            'code'=>1000,
//            'message'=>'',
//            'data'=>[
//                'category'=>[
//                    'uuid'=>$category->uuid,
//                    'name'=>$category->name,
//                    'type'=>$category->type,
//                    'children'=>$category->children,
//                    'parent'=>$category->parent,
//                    'files'=>$category->files,
//                ]
//            ]
//        ];
//        $data = [
//            'code'=>1000,
//            'message'=>'',
//            'data'=>$logic->getData()
//        ];

        return JsonBuilder::Success($data,'请求成功');
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

