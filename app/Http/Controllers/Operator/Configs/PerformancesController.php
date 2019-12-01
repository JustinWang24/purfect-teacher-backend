<?php
namespace App\Http\Controllers\Operator\Configs;

use App\Dao\Performance\TeacherPerformanceConfigDao;
use App\Dao\Schools\SchoolDao;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerformancesController extends Controller
{
    /**
     * 加载教师工作考评配置项目的管理页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function teachers(Request $request){
        $this->dataForView['pageTitle'] = '教师工作考评管理';
        $item = null;
        if($request->get('item',null)){
            // 表示要编辑某个考核项
            $dao = new TeacherPerformanceConfigDao();
            $item = $dao->getById($request->get('item'));
        }
        $this->dataForView['item'] = $item;
        $schoolDao = new SchoolDao();
        $this->dataForView['configs'] = $schoolDao->getSchoolById($request->session()->get('school.id'))->teacherPerformanceConfigs;
        return view('school_manager.configs.teachers',$this->dataForView);
    }

    /**
     * 保存教师工作考评项
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function teacher_save(Request $request){
        $item = $request->get('item');
        $dao = new TeacherPerformanceConfigDao();
        if(empty($item['id'])){
            $result = $dao->create($item);
        }
        else{
            $result = $dao->update($item);
        }
        if($result){
            FlashMessageBuilder::Push($request, 'success','考核项已经保存');
        }
        else{
            FlashMessageBuilder::Push($request, 'error','系统繁忙');
        }
        return redirect()->route('school_manger.configs.performance-teacher');
    }

    /**
     * 删除考评项
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function teacher_delete(Request $request){
        $dao = new TeacherPerformanceConfigDao();
        $id = $request->get('item');

        if($dao->delete($id)){
            FlashMessageBuilder::Push($request, 'success','考核项已经成功删除');
        }
        else{
            FlashMessageBuilder::Push($request, 'error','系统繁忙');
        }
        return redirect()->route('school_manger.configs.performance-teacher');
    }
}
