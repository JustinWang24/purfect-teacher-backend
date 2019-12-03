<?php


namespace App\Http\Controllers\Api\Notice;


use App\Utils\JsonBuilder;
use App\Dao\Notice\NoticeDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notice\NoticeRequest;

class NoticeController extends Controller
{

    /**
     * 获取通知
     * @param NoticeRequest $request
     * @return string
     */
    public function getNotice(NoticeRequest $request) {
        $type = $request->getType();
        $dao = new NoticeDao();
        $schoolId = 1;
        $result = $dao->getNotice($type, $schoolId)->toArray();
        $data = [
            'currentPage'=> $result['current_page'],
            'lastPage'   => $result['last_page'],
            'total'      => $result['total'],
            'data'       => $result['data']
            ];
        return JsonBuilder::Success($data);
    }
}
