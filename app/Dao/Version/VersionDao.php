<?php


namespace App\Dao\Version;


use App\Dao\BuildFillableData;
use App\Models\Version\Version;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class VersionDao
{
    use BuildFillableData;
    public function __construct()
    {
    }

    /**
     * @param $data
     * @return MessageBag
     */
    public function create($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            unset($data['id']);
        }
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new Version(), $data);
            $version = Version::create($fillableData);
            if ($version) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($version);
            } else {
                DB::rollBack();
                $messageBag->setMessage('保存版本号失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * @param $data
     * @return MessageBag
     */
    public function update($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new Version(), $data);
            $version = Version::where('id', $id)->update($fillableData);
            if ($version) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($version);
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新版本号失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * @param string $field
     * @return Version[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getVersions($field="*")
    {
        return Version::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getVersionById($id)
    {
        return Version::find($id);
    }

    /**
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return Version::where('id', $id)->delete();
    }

    /**
     * @return mixed
     */
    public function getLastVersion()
    {
        return Version::where('id',Version::max('id'))->select('code','name','download_url')->get();
    }
}
