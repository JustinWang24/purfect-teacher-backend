import {Constants} from "./constants";

export function searchTeachers(schoolId, teacherName, majors) {
    return axios.post(
        Constants.API.SEARCH_TEACHERS_BY_NAME,
        {query: teacherName, school: schoolId, majors: majors}
    );
}