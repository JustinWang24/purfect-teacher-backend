<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 27/10/19
 * Time: 5:27 PM
 */

namespace App\BusinessLogic\TimetableLogic;
use App\Dao\Timetable\TimetableItemDao;
use App\Models\Timetable\TimetableItem;
use App\Utils\Time\GradeAndYearUtil;

class SpecialItemsLoadLogic
{
    protected $ids;
    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function build(){
        $dao = new TimetableItemDao();

        /**
         * @var TimetableItem[] $items
         */
        $items = $dao->getItemsByIdArray($this->ids);

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'id'=>$item->id,
                'date'=>$item->at_special_datetime->format(GradeAndYearUtil::DEFAULT_FORMAT_DATE),
                'course'=>$item->course->name,
                'teacher'=>$item->teacher->profile->name,
                'location'=>$item->building->name . ' - ' .$item->room->name,
                'updated_by'=>$item->updatedBy->name,
            ];
        }

        return $result;
    }
}