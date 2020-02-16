<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use App\Dao\Affiche\Api\AuthTissueDao;
use App\Dao\Affiche\Api\AuthTissuePicDao;
use App\Http\Requests\Api\Affiche\TissueauthRequest;

use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Models\Notices\AppProposalImage;
use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;
/**
 * 组织认证类
 * @author  zk
 * @version 1.0
 */
class TissueauthController extends Controller
{
    /**
     * Func 添加组织认证接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['authu_tissusname']  是   名称
     * @param['authu_tissusdesc']  是   介绍
     * @param['authu_pics[]	']  是   图片
     *
     * @return Json
     */
    public function add_auth_tissue_info(TissueauthRequest $request)
    {
        $token = (String)$request->input('token', '');
        $authu_tissusname = (String)$request->input('authu_tissusname', '');
        $authu_tissusdesc = (String)$request->input('authu_tissusdesc', '');
        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!trim($authu_tissusname)) {
            return JsonBuilder::Error('名称不能为空');
        }
        if (!trim($authu_tissusdesc)) {
            return JsonBuilder::Error('介绍不能为空');
        }

        $user = $request->user();
        $authTissueObj = new AuthTissueDao();
        $getAuthIno = $authTissueObj->getAuthIno($user->id);

        // 组装数据
        $addData['status'] = -1;
        $addData['user_id'] = $user->id;
        $addData['school_id'] = $user->gradeUser->school_id;
        $addData['campus_id'] = $user->gradeUser->campus_id;
        $addData['authu_tissusname'] = trim($authu_tissusname);
        $addData['authu_tissusdesc'] = trim($authu_tissusdesc);
        $addData[ 'authu_number' ]     = (String)$getAuthIno[ 'user_username' ]; //编号
        $addData[ 'authu_name' ]       = (String)$getAuthIno[ 'user_usercode' ];  //姓名
        $addData[ 'authu_cardno' ]     = (String)$getAuthIno[ 'user_cardno' ]; //身份证号码

        if ($tissueid = $authTissueObj->addAuthTissueInfo($addData))
        {
            // 上传图片
            $group_picssArr = [];
            $images = $request->file('authu_pics');
            if (!empty($images)) {
                foreach ($images as $key => $val) {
                    $group_picssArr[$key] = AppProposalImage::proposalUploadPathToUrl($val->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX));
                }
            }
            if (!empty($group_picssArr))
            {
                $authTissuePicObj = new AuthTissuePicDao();
                foreach ($group_picssArr as $key => $val)
                {
                    $picsAddData['user_id'] = $user->id;
                    $picsAddData['tissue_id'] = $tissueid;
                    $picsAddData['pics_small'] = $val;
                    $picsAddData['pics_big'] = $val;
                    $authTissuePicObj->addAuthTissuePicInfo($picsAddData);
                }
            }
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }



    /**
     * Func 获取组织信息
     *
     * @param Request $request
     * @param['token'] 是  token
     *
     * @return Json
     */
    public function one_auth_tissue_info(TissueauthRequest $request)
    {
        $token = (String)$request->input('token', '');

        $user = $request->user();
        $user_id = $user->id;

        $authTissueObj = new AuthTissueDao();
        $data = $authTissueObj->getAuthTissueOneInfo($user_id,[1]);
        $infos = $authTissueObj->getAuthTissueOneInfo($user_id,[ '-1' , 2 ]);

        $tissueid1 = isset($data['tissueid']) ? $data['tissueid'] : 0;
        $tissueid2 = isset($infos['tissueid']) ? $infos['tissueid'] : 0;
        if ( $tissueid1 < $tissueid2 )
        {
            $infos[ 'picsList' ] = [];
            if(!empty($infos))
            {
                $authTissuePicObj = new AuthTissuePicDao();
                $infos[ 'picsList' ] = $authTissuePicObj->getAuthTissuePicsListInfo( $infos[ 'tissueid' ] );
            }
        }
        return JsonBuilder::Success ( !empty($infos) ? $infos : (object)null, '组织认证小详情' );
    }


}
