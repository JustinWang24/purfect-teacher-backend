import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 保存选修课报名表
 * @param course
 * @param schedule
 * @param affix
 * @returns Promise
 */
export function saveElectiveCourse(course, schedule, affix) {
    const url = Util.buildUrl(Constants.API.ELECTIVE_COURSE.SAVE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {course: course, schedule: schedule, version:Constants.VERSION}
    );
}

/**
 * 教师申请开一门选修课
 * @param course
 * @param schedule
 * @param affix
 * @returns Promise
 */
export function applyElectiveCourse(course, schedule, affix) {
    const url = Util.buildUrl(Constants.API.ELECTIVE_COURSE.SAVE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {course: course, schedule: schedule, version:Constants.VERSION}
    );
}