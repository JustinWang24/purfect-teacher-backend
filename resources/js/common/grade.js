import {Util} from "./utils";
import {Constants} from "./constants";

/**
 * 加载课件的所有材料
 * @param gradeId
 * @param teacherId
 * @param courseId
 * @param affix
 */
export function loadGrade(gradeId, teacherId, courseId, affix) {
    const url = Util.buildUrl(Constants.API.GRADE.LOAD);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {grade_id: gradeId, teacher_id: teacherId, course_id: courseId,version:Constants.VERSION}
    );
}