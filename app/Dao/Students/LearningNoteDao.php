<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/12/19
 * Time: 6:36 PM
 */

namespace App\Dao\Students;


use App\Models\Students\LearningNote;
use App\Models\Timetable\TimetableItem;
use App\User;
use Illuminate\Support\Collection;

class LearningNoteDao
{
    /**
     * @param User $student
     * @param TimetableItem $item
     * @return Collection
     */
    public function getByStudentAndTimetableItem(User $student, TimetableItem $item){
        return LearningNote::where('student_id', $student->id)
            ->where('timetable_item_id', $item->id)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param $data
     * @return LearningNote
     */
    public function create($data){
        return LearningNote::create($data);
    }
}