<?php
namespace App\Http\Controllers\H5\Banner;

use App\Dao\Banners\BannerDao;
use App\Dao\Misc\SystemNotificationDao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 资源位图文页面
     * @param Request $request
     * @return page
     */
    public function page_info(Request $request)
    {
      $id = (Int)$request->get('id');
      $data = (object)null;
      if ($id) {
        $dao = new BannerDao;
        $data = $dao->getBannerById($id);
      }
      $this->dataForView['data'] = $data;
      return view('h5_apps.banner.page_info', $this->dataForView);
    }

    /**
     * 后台消息页
     * @param Request $request
     * @return page
     */
    public function notification_info(Request $request)
    {
      $uuid = (String)$request->get('uuid');
      $data = (object)null;
      if ($uuid) {
        $systemNotificationDao = new SystemNotificationDao();
        $data = $systemNotificationDao->getNotificationOne(['uuid' => $uuid ]);
      }
      $this->dataForView['data'] = $data;
      return view('h5_apps.banner.notification_info', $this->dataForView);
    }
}
