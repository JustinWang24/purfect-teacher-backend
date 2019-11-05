import {Constants} from "./constants";

/**
 * 根据给定的学校 id 和专业 ID集合, 按名字来搜索教师的用户
 * @param schoolId
 * @param teacherName
 * @param majors
 * @returns {*}
 */
export function searchTeachers(schoolId, teacherName, majors) {
    return axios.post(
        Constants.API.SEARCH_TEACHERS_BY_NAME,
        {query: teacherName, school: schoolId, majors: majors}
    );
}

/**
 * 根据指定的学校 ID 和用户的 ID, 获取用户的名字
 * @param schoolId
 * @param userId
 * @returns {*}
 */
export function getUserNameById(schoolId, userId) {
    return axios.post(
        Constants.API.GET_USER_NAME_BY_ID,
        {school: schoolId, user: userId}
    );
}