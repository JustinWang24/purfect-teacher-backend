<?php


namespace App\Dao\Notice;


use App\Models\Notices\AppProposal;
use App\Models\Notices\AppProposalImage;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Facades\DB;

class AppProposalDao
{

    /**
     * 添加
     * @param $data
     * @param $images
     * @return bool
     */
    public function add($data, $images)
    {
        DB::beginTransaction();
        try {
            $content = AppProposal::create($data);
            foreach ($images as $key => $val) {
                $image = [
                    'app_proposal_id' => $content->id,
                    'path' => $val,
                ];
                AppProposalImage::create($image);
            }
            DB::commit();
            $result = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }

        return $result;
    }

    /**
     * 根据用户ID 获取反馈
     * @param $userId
     * @return mixed
     */
    public function getProposalByUserId($userId)
    {
        return AppProposal::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 获取所有
     */
    public function getAllProposal()
    {
        return AppProposal::all();
    }

    /**
     * 根据 ID 获取一条
     * @param $id
     * @return
     */
    public function getProposalById($id)
    {
        return AppProposal::find($id);
    }

}
