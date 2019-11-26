<?php

namespace App\Http\Controllers\Operator;

use App\Models\Schools\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{

    public function management(Request $request){
        $this->dataForView['pageTitle'] = '校园动态';
        $this->dataForView['newsList'] = News::paginate();
        return view('school_manager.news.list',$this->dataForView);
    }
}
