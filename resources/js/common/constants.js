/**
 * 可能需要广泛使用的一些常量
 */
export const Constants = {
    AJAX_SUCCESS: 1000,
    AJAX_ERROR: 999,
    API: {
        LOAD_TIME_SLOTS_BY_SCHOOL: '/api/school/load-time-slots',
        LOAD_STUDY_TIME_SLOTS_BY_SCHOOL: '/api/school/load-study-time-slots',
        LOAD_BUILDINGS_BY_SCHOOL: '/api/school/load-buildings',
        LOAD_ROOMS_BY_BUILDING: '/api/school/load-building-rooms',
        LOAD_AVAILABLE_ROOMS_BY_BUILDING: '/api/school/load-building-available-rooms',
        LOAD_MAJORS_BY_SCHOOL: '/api/school/load-majors',
        LOAD_GRADES_BY_MAJOR: '/api/school/load-major-grades',
        LOAD_COURSES_BY_MAJOR: '/api/school/load-major-courses',
        LOAD_COURSES_BY_SCHOOL: '/api/school/load-courses',
        LOAD_TEACHERS_BY_COURSE: '/api/school/load-course-teachers',
        SEARCH_TEACHERS_BY_NAME: '/api/school/search-teachers',
        SAVE_COURSE: '/api/school/save-course',
        DELETE_COURSE: '/api/school/delete-course',
        // 课程表专有
        TIMETABLE: {
            SAVE_NEW: '/api/timetable/save-timetable-time',
            LOAD_TIMETABLE: '/api/timetable/load', // 加载课程表
        }
    },
    TERMS: ['第一学期','第二学期','第三学期','第四学期'],
    REPEAT_UNITS: ['单周重复','双周重复','三周重复','四周重复'],
    WEEK_DAYS: ['周一','周二','周三','周四','周五','周六','周日',],
};