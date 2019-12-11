<?php


namespace App\Dao\Teachers;


use App\Models\Teachers\ExamsPlansRoom;

trait IsValid
{

    /**
     * 判断是否有空
     * @param $type
     * @param $id
     * @param $from
     * @param $to
     * @return bool|string
     */
    public function isLeisure($type, $id, $from, $to) {
        switch ($type) {
            case  'teacher' :
                return $this->teacher($id, $from, $to);
                break;
            case  'room' :
                return $this->Room($id,$from,$to);
                break;
                default :
                return '';

        }
    }

    protected function teacher($id, $from, $to) {
        $return = ExamsPlansRoom::where([['first_teacher_id', '=', $id],['from', '<', $to],['to','>',$from]])
            ->orWhere([['second_teacher_id', '=', $id],['from', '<', $to],['to','>',$from]])
            ->orWhere([['thirdly_teacher_id', '=', $id],['from', '<', $to],['to','>',$from]])
            ->first();

        if(is_null($return))
            return true;
        return false;
    }


    protected function Room($id,$from,$to)
    {
        $return = ExamsPlansRoom::where([['room_id', '=', $id],['from', '<', $to],['to','>',$from]])
            ->first();
        if(is_null($return))
            // todo 需要再判断是否有课程

            return true;
        return false;
    }

}
