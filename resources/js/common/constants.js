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
        LOAD_MAJORS_BY_SCHOOL: '/api/school/load-majors',
        LOAD_GRADES_BY_MAJOR: '/api/school/load-major-grades',
        LOAD_COURSES_BY_MAJOR: '/api/school/load-major-courses',
        LOAD_COURSES_BY_SCHOOL: '/api/school/load-courses',
        LOAD_TEACHERS_BY_COURSE: '/api/school/load-course-teachers',
        SEARCH_TEACHERS_BY_NAME: '/api/school/search-teachers',
        SAVE_COURSE: '/api/school/save-course',
        DELETE_COURSE: '/api/school/delete-course',
    }
};