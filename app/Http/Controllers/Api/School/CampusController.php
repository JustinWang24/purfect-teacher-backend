<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Contents\AlbumDao;
use App\Dao\Schools\NewsDao;
use App\Dao\Schools\SchoolDao;
use App\Models\School;
use App\Models\Schools\News;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\CampusScenery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampusController extends Controller
{
    /**
     * 校园风光 接口
     * @param Request $request
     * @return string
     */
    public function scenery(Request $request){
        /**
         * @var User $user
         */
        $user = $request->user('api');
        if($user){
            $schoolId = $user->getSchoolId();
            // 表示用户找到了
            $albumDao = new AlbumDao();
            $album = $albumDao->getAllBySchool($schoolId);
            $school = (new SchoolDao())->getSchoolById($schoolId);
            $str = '<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet"><style>
                    body{margin-left:10px;margin-right:10px;}
                    img{max-width: 100% !important;}
                  </style>';
            $scenery = new CampusScenery($album, $str . $school->configuration->campus_intro);
            return JsonBuilder::Success($scenery->toArray());
          }
        return JsonBuilder::Error('无法定位用户所属学校');
    }

    /**
     * 科研成果
     * @param Request $request
     * @return string
     */
    public function scientific(Request $request)
    {
        $user = $request->user();

        $schoolId = $user->getSchoolId();

        $dao = new NewsDao;

        $data = $dao->getNewBySchoolId($schoolId,null,News::TYPE_SCIENCE);
        $result = [];
        foreach ($data as $key => $val) {
            $result[$key]['title'] = $val->title;
            $result[$key]['content'] = '';
            $result[$key]['web_url'] = route('h5.teacher.news.view', ['id' => $val['id']]);
            $result[$key]['created_at'] = $val->created_at->format('Y-m-d H:i');
            $result[$key]['image']       = '';
            $sections  = $val->sections;

            foreach ($sections as $k => $v) {
                if (!empty($v->content) && empty($v->media_id)) {
                    $result[$key]['content'] = $v->content;
                }
                if (!empty($v->media_id)) {
                    $result[$key]['image'] = asset($v->content);
                }
            }
        }

        return JsonBuilder::Success($result);
    }

}
