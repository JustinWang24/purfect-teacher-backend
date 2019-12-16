<?php
namespace App\Http\Controllers\Teacher;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\GradeRequest;
use App\Dao\Schools\GradeDao;
use App\Models\Schools\GradeManager;
use App\Utils\JsonBuilder;

class GradesController extends Controller
{

    public function set_monitor(GradeRequest $request){
        if($request->isMethod('POST')){
            $adviserData = $request->getAdviserForm();

            $adviserData['monitor_name'] = explode(' - ',$adviserData['monitor_name'])[0];

            $result = (new GradeDao())->setAdviser($adviserData);

            return $result->isSuccess() ? JsonBuilder::Success():JsonBuilder::Error($result->getMessage());
        }
        elseif ($request->isMethod('GET')){
            $this->dataForView['pageTitle'] = '设置班长';
            $grade = (new GradeDao())->getGradeById($request->get('grade'));
            $this->dataForView['grade'] = $grade;

            if($grade->gradeManager){
                $gradeManager = $grade->gradeManager;
            }
            else{
                // 如果系主任记录还不存在, 那么构造一个新的
                $gradeManager = new GradeManager();
                $gradeManager->grade_id = $grade->id;
                $gradeManager->school_id = $request->session()->get('school.id');
                $gradeManager->adviser_id = 0;
                $gradeManager->adviser_name = '';
                $gradeManager->monitor_id = 0;
                $gradeManager->monitor_name = '';
            }
            $this->dataForView['gradeManager'] = $gradeManager;
            return view('teacher.grade.set_monitor',$this->dataForView);
        }
    }
}