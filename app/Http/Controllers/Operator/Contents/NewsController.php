<?php

namespace App\Http\Controllers\Operator\Contents;

use App\Dao\Contents\AlbumDao;
use App\Dao\Schools\NewsDao;
use App\Dao\Schools\NewsSectionDao;
use App\Dao\Schools\SchoolDao;
use App\Models\Schools\News;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * 校园相册管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function photo_album(Request $request){
        $school = (new SchoolDao())->getSchoolByIdOrUuid($request->get('uuid'));
        $dao = new AlbumDao();
        $album = $dao->getAllBySchool($school->id);
        $this->dataForView['pageTitle'] = '校园相册管理';
        $this->dataForView['album'] = $album;
        $this->dataForView['school'] = $school;
        return view('school_manager.news.album',$this->dataForView);
    }

    /**
     * 保存相册内容
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_album(Request $request){
        $dao = new AlbumDao();
        $msgBag = $dao->create($request);
        if($msgBag->isSuccess()){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$msgBag->getMessage());
        }
        else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$msgBag->getMessage());
        }
        return redirect()->route('school_manager.contents.photo-album',['uuid'=>$msgBag->getData()]);
    }

    /**
     * 删除相册内容
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete_album(Request $request){
        $dao = new AlbumDao();
        $album = $dao->getById($request->get('id'));
        if($album){
            $school = (new SchoolDao())->getSchoolById($album->school_id);
            $album->delete();
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
            return redirect()->route('school_manager.contents.photo-album',['uuid'=>$school->uuid]);
        }

    }

    public function management(Request $request){
        $this->dataForView['typeText'] = News::TypeText($request->get('type'));
        $this->dataForView['pageTitle'] = $this->dataForView['typeText'].'管理';
        $this->dataForView['type'] = $request->get('type');
        $dao = new NewsDao();
        $this->dataForView['newsList'] = $dao->paginateByType(
            $request->get('type'),
            $request->session()->get('school.id')
        );
        return view('school_manager.news.list',$this->dataForView);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $dao = new NewsDao();
        $schoolId = $request->get('school');
        $newsData = $request->get('news');
        $newsData['school_id'] = $schoolId;
        if(!isset($newsData['id']) || empty($newsData['id'])){
            $result = $dao->create($newsData);
            if($result->isSuccess()){
                return JsonBuilder::Success(['news'=>$result->getData()]);
            }
            else{
                return JsonBuilder::Error();
            }
        }else{
            unset($newsData['sections']);
            $dao->updateNewById($newsData['id'], $newsData);
            return JsonBuilder::Success();
        }
    }

    public function load(Request $request){
        $dao = new NewsDao();
        $newsId = $request->get('news_id');
        $news = $dao->getById($newsId);
        return JsonBuilder::Success(['news'=>$news]);
    }

    /**
     * 保存段落
     * @param Request $request
     * @return string
     */
    public function save_section(Request $request){
        $dao = new NewsSectionDao();
        $result = $dao->batchCreate($request->all());
        if($result->isSuccess()){
            $d = $result->getData();
            return $d ? JsonBuilder::Success(['id'=>$d]) : JsonBuilder::Success();
        }
        else{
            return JsonBuilder::Error($result->getMessage());
        }
    }

    public function delete(Request $request){
        $dao = new NewsDao();
        $dao->delete($request->get('news_id'));
        return JsonBuilder::Success();
    }

    public function publish(Request $request){
        $dao = new NewsDao();
        $dao->updateNewById($request->get('news_id'),['publish'=>true]);
        return JsonBuilder::Success();
    }

    /**
     * 删除段落
     * @param Request $request
     * @return string
     */
    public function delete_section(Request $request){
        $dao = new NewsSectionDao();
        $deleted = $dao->delete($request->get('section_id'));
        return $deleted ? JsonBuilder::Success(): JsonBuilder::Error();
    }

    /**
     * 段落上移
     * @param Request $request
     * @return string
     */
    public function move_up_section(Request $request){
        $dao = new NewsSectionDao();
        $result = $dao->moveUp($request->get('section_id'));
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 段落下移
     * @param Request $request
     * @return string
     */
    public function move_down_section(Request $request){
        $dao = new NewsSectionDao();
        return $dao->moveDown($request->get('section_id'))
            ? JsonBuilder::Success() : JsonBuilder::Error();
    }
}
