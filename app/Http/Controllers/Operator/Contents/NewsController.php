<?php

namespace App\Http\Controllers\Operator\Contents;

use App\Dao\Schools\NewsDao;
use App\Dao\Schools\NewsSectionDao;
use App\Models\Schools\News;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function management(Request $request){
        $this->dataForView['pageTitle'] = '校园动态';
        $this->dataForView['newsList'] = News::paginate();
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
