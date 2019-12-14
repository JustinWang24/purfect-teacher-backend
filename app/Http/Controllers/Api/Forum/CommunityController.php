<?php


namespace App\Http\Controllers\Api\Forum;


use App\Dao\Forum\ForumCommunityDao;
use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function approve(Request $request)
    {
        $data = $this->optPic($request);
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $detail = strip_tags($request->get('detail'));
        $name = strip_tags($request->get('name'));
        $data['school_id'] = $schoolId;
        $data['user_id'] = $user->id;
        $data['detail'] = $detail;
        $data['name'] = $name;

        if (empty($data['school_id']) || empty($data['detail']) || empty($data['name']))
        {
            return JsonBuilder::Error('内容不合法请重试');
        }
        $dao = new ForumCommunityDao();
        $result = $dao->createCommunity($data);
        return JsonBuilder::Success($result);

    }


    public function optPic($request)
    {
        $data = [];
        $fileConfig = config('filesystems.disks.community');
        $files = $request->allFiles();
        foreach ($files as $name => $file) {
            $path = date('Ymd').DIRECTORY_SEPARATOR.date('md');
            $realFileName = $file->store($path,'community');
            $ext = pathinfo($realFileName,PATHINFO_EXTENSION);
            if (in_array($ext, ['png','jpg','jpeg','bmp'])) {
                $data[$name] = $fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName;
            }else{
                Storage::delete($fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName);
            }
        }
    }


}
