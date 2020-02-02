<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Contents\AlbumDao;
use App\Dao\Schools\SchoolDao;
use App\Models\School;
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
            // 表示用户找到了， 并正确的对应到了学校
            $albumDao = new AlbumDao();
            $album = $albumDao->getAllBySchool($schoolId);
            /**
             * @var School $school
             */
            $school = (new SchoolDao())->getSchoolById($schoolId);
            $scenery = new CampusScenery($album, $school->configuration->campus_intro);
            return JsonBuilder::Success($scenery->toArray());
        }
        return JsonBuilder::Error('无法定位用户所属学校');
    }
}
