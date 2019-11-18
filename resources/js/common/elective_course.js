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

/**
 * 获取一个空的课程申请对象
 * @returns {{id: null, code: string, name: string, teachers: Array, scores: string, majors: Array, optional: string, year: string, term: string, desc: string, timeSlots: Array, dayIndexes: Array, weekNumbers: Array}}
 */
export function getEmptyElectiveCourseApplication(optional){
    return {
        id: null, // 课程 ID
        code: '', // 课程编号
        name: '', // 课程名称
        teachers: [], // 任课教师, 可以包含多个老师
        scores: '0', // 学分
        majors: [], // 所属专业, 一门课可以属于多个专业共享
        optional: optional ? '1' : '0', // 必修还是选修
        year: '',
        term: '', // 学期
        desc: '',  // 课程描述
        open_num: '',  // 最多人数
        min_applied: '',  // 最少开班人数
        timeSlots: [], // 课程上课的时间段
        dayIndexes: [], // 课程上课的时间段
        weekNumbers: [], // 课程上课的时间段
    };
}