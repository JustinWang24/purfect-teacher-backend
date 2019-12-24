<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 6:05 PM
 */

namespace App\Http\Controllers\H5\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schools\Room;
use App\Dao\Schools\RoomDao;

class RoomsController extends Controller
{
    public function rooms(Request $request){
        $user = $request->user('api');
        $this->dataForView['teacher'] = $user;
        $this->dataForView['api_token'] = $request->get('api_token');
        $type = $request->get('type', Room::TYPE_CLASSROOM);
        $building = $request->get('building_id', null);

        $rooms = (new RoomDao())->getRoomByType($user->getSchoolId(), $type, $building);
        $this->dataForView['rooms'] = $rooms;
        $this->dataForView['type'] = $type;
        return view('h5_apps.teacher.management.rooms', $this->dataForView);
    }
}