<?php
namespace App\Http\Controllers\Operator\RecruitStudent;
use App\Models\RecruitStudent\RecruitNote;
use App\Models\Schools\SchoolConfiguration;
use App\User;
use App\Utils\FlashMessageBuilder;
use App\Dao\RecruitStudent\ConsultDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\ConsultRequest;

class ConsultController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * 招生简章、报名须知管理
     *
     * @param ConsultRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function note(ConsultRequest $request){
        $this->dataForView['pageTitle'] = '招生简章/报名须知管理';
        $this->dataForView['redactor'] = true;
        $this->dataForView['redactorWithVueJs'] = true;
        $this->dataForView['js'] = [
            'school_manager.recruitStudent.consult.note_js'
        ];

        /**
         * @var User $user
         */
        $user = $request->user();
        $note = RecruitNote::where('school_id', $user->getSchoolId())->first();
        // 招生简章
        $config = SchoolConfiguration::where('school_id', $user->getSchoolId())->first();
        $recruitment_intro = $config->recruitment_intro;

        // 由于需求太简单, 就不去使用 Dao 了, 而是直接操作 Model. 后期如果需求变复杂, 还是要回归 Dao 的方式
        if($request->isMethod('post')){
            if($request->has('note')){
                $noteData = $request->get('note');
                if(is_null($note)){
                    $note = new RecruitNote();
                }
                $note->content = $noteData['content'];
                $note->school_id = $noteData['school_id'];
                $note->plan_id = $noteData['school_id'];
                if($note->save()){
                    FlashMessageBuilder::Push($request, 'success','报名须知已经成功保存!');
                }
            }


            // 招生简章
            if($request->has('config')){
                $configData = $request->get('config');
                $config->recruitment_intro = $configData['recruitment_intro'];
                if($config->save()){
                    FlashMessageBuilder::Push($request, 'success','招生简章已经成功保存!');
                }
            }
        }

        $this->dataForView['note'] = $note;
        $this->dataForView['recruitment_intro'] = $recruitment_intro;
        $this->dataForView['user'] = $user;

        return view('school_manager.recruitStudent.consult.note', $this->dataForView);
    }

    public function list(ConsultRequest $request) {
        $schoolId = $request->getSchoolId();
        $consultDao = new ConsultDao();
        $result = $consultDao->getConsultPage($schoolId);
        $this->dataForView['consult'] = $result;
        return view('school_manager.recruitStudent.consult.list', $this->dataForView);
    }


    public function add(ConsultRequest $request) {
        if($request->isMethod('post')) {
            $user = $request->user();
            $all = $request->post('consult');
            $all['school_id'] = $request->getSchoolId();
            $consultDao = new ConsultDao($user);
            $result = $consultDao->saveConsult($all);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'添加成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'添加失败');
            }
            return redirect()->route('school_manager.consult.list');
        }
        return view('school_manager.recruitStudent.consult.add', $this->dataForView);

    }


    public function edit(ConsultRequest $request) {
        $user = $request->user();
        $consultDao = new ConsultDao($user);
        if($request->isMethod('post')) {
            $all = $request->post('consult');
            $result = $consultDao->saveConsult($all);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }
            return redirect()->route('school_manager.consult.list');
        }

        $id = $request->get('id');
        $info = $consultDao->getConsultById($id);
        $this->dataForView['consult'] = $info;
        return view('school_manager.recruitStudent.consult.edit', $this->dataForView);

    }

    public function delete(ConsultRequest $request) {
        $id = $request->get('id');
        $consultDao = new ConsultDao();
        $re = $consultDao->delete($id);
        if($re) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
        }
        return redirect()->route('school_manager.consult.list');
    }
}
