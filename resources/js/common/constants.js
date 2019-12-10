/**
 * 可能需要广泛使用的一些常量
 */
export const Constants = {
    VERSION: '3.0',
    AJAX_SUCCESS: 1000,
    AJAX_ERROR: 999,
    API: {
        LOAD_TIME_SLOTS_BY_SCHOOL: '/api/school/load-time-slots',
        SAVE_TIME_SLOT: '/api/school/save-time-slot',
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
        GET_USER_NAME_BY_ID: '/api/school/get-user-name',
        QUICK_SEARCH_USERS_BY_NAME: '/api/school/quick-search-users',
        SAVE_COURSE: '/api/school/save-course',
        DELETE_COURSE: '/api/school/delete-course',
        // 课程表专有
        TIMETABLE: {
            CAN_BE_INSERTED: '/api/timetable/timetable-item-can-be-inserted',
            SAVE_NEW: '/api/timetable/save-timetable-item',
            UPDATE: '/api/timetable/update-timetable-item',
            DELETE_ITEM: '/api/timetable/delete-timetable-item',
            PUBLISH_ITEM: '/api/timetable/publish-timetable-item',
            CLONE_ITEM: '/api/timetable/clone-timetable-item',
            LOAD_TIMETABLE: '/api/timetable/load', // 加载课程表
            LOAD_TIMETABLE_ITEM: '/api/timetable/load-item', // 加载课程表项
            CREATE_SPECIAL_CASE: '/api/timetable/create-special-case', // 加载课程表项
            LOAD_SPECIAL_CASES: '/api/timetable/load-special-cases', // 加载课程表项
            SWITCH_WEEK_VIEW: '/api/timetable/switch-week-view', // 加载课程表项
            // 从不同角度观察课程表的 url
            VIEW_TIMETABLE_FOR_COURSE: '/school_manager/timetable/manager/view-course-timetable', // 从课程的角度加载课程表
            VIEW_TIMETABLE_FOR_TEACHER: '/school_manager/timetable/manager/view-teacher-timetable', // 从课程的授课老师角度加载课程表
            VIEW_TIMETABLE_FOR_ROOM: '/school_manager/timetable/manager/view-room-timetable', // 从课程的授课老师角度加载课程表
        },
        // 申请
        ENQUIRY_SUBMIT: '/api/enquiry/save',
        // 静态页面: 学生报名
        REGISTRATION_FORM: {
            QUERY_STUDENT_PROFILE: '/api/student-register/query-student-profile',
            QUERY_STUDENT_MAJORS: '/api/student-register/load-open-majors',
            SUBMIT_FORM: '/api/student-register/submit-form',
            LOAD_MAJOR_DETAIL: '/api/student-register/load-major-detail',
            VERIFY_ID_NUMBER: '/api/student-register/verify-id-number',
            APPROVE_OR_REJECT: '/api/student-register/approve-or-reject',
            ENROL_OR_REJECT: '/api/student-register/enrol-or-reject',
            ENROLMENT_MANAGER: '/teacher/registration-forms/enrol',
            REGISTRATION_MANAGER: '/teacher/registration-forms/manage',
        },
        // 获取省市列表的接口
        LOCATION: {
            PROVINCES: '/api/location/get-provinces',
            CITIES: '/api/location/get-cities',
            DISTRICTS: '/api/location/get-districts',
        },
        RECRUITMENT: {
            LOAD_PLANS: '/api/recruitment/load-plans',
            SAVE_PLAN: '/api/recruitment/save-plan',
            GET_PLAN: '/api/recruitment/get-plan',
            DELETE_PLAN: '/api/recruitment/delete-plan',
        },
        FILE_MANAGER: {
            LOAD_CATEGORY: '/api/network-disk/categories/view',
            LOAD_PARENT_CATEGORY: '/api/network-disk/categories/view-parent',
            CREATE_CATEGORY: '/api/network-disk/categories/create',
            EDIT_CATEGORY: '/api/network-disk/categories/edit',
            DELETE_CATEGORY: '/api/network-disk/categories/delete',
            FILE_UPLOAD: '/network-disk/media/upload',
            FILE_MOVE: '/api/network-disk/media/move',
            FILE_DELETE: '/api/network-disk/media/delete',
            FILE_SEARCH: '/api/network-disk/media/search',
            RECENT_FILES: '/api/network-disk/media/latelyUploadingAndBrowse',
            GET_NETWORK_DISK_SIZE: '/api/network-disk/media/getNetWorkDiskSize',
            UPDATE_ASTERISK: '/api/network-disk/media/update-asterisk',
        },
        ELECTIVE_COURSE: {
            SAVE: '/api/elective-course/save', // 管理员直接添加
            APPLY: '/api/elective-course/apply', // 教师申请开选修课
            LOAD: '/api/elective-course/load', // 加载教师申请的选修课
            APPROVE: '/school_manager/elective-course/approve', // 同意教师申请的选修课
            REFUSE: '/school_manager/elective-course/refuse', // 拒绝教师申请的选修课
            DELETE_ARRANGEMENT: '/school_manager/elective-course/delete-arrangement', // 删除选修课上课的时间地点项
        },
        TEXTBOOK: {
            EXPORT_TEXTBOOKS_BY_MAJOR: '/teacher/textbook/loadMajorTextbook',
            EXPORT_TEXTBOOKS_BY_CAMPUS: '/school_manager/textbook/loadCampusTextbook',
            LOAD_TEXTBOOKS_PAGINATE: '/teacher/textbook/list-paginate',
            LOAD_TEXTBOOKS: '/teacher/textbook/list',
            SEARCH_TEXTBOOKS: '/teacher/textbook/search',
            ATTACH_TEXTBOOKS: '/teacher/textbook/courseBindingTextbook',
            DELETE_TEXTBOOK: '/teacher/textbook/delete',
        },
        WELCOME:{
            LOAD_STEP_DETAILS: '/api/enrolment-step/schoolEnrolmentStep/getEnrolmentInfo',
            LOAD_WHOLE_PROCESS: '/api/enrolment-step/step-list', // 获取某个学校校区的迎新流程中所有步骤
            LOAD_TEACHERS: '/api/school/teachers', // 获取某个学校的所有老师
        },
        CONTACTS: {
            ORG: '/api/campus/handleAffairs/getAddressBook/official',
            GRADE:'/api/campus/handleAffairs/getAddressBook/class',
            ALL_GRADES: '/api/campus/all-grades'
        },
        NEWS: {
            SAVE: '/school_manager/contents/news/save',
            PUBLISH: '/school_manager/contents/news/publish',
            DELETE: '/school_manager/contents/news/delete',
            LOAD: '/school_manager/contents/news/load',
            SAVE_SECTION: '/school_manager/contents/news/save-section',
            DELETE_SECTION: '/school_manager/contents/news/delete-section',
            MOVE_UP_SECTION: '/school_manager/contents/news/move-up-section',
            MOVE_DOWN_SECTION: '/school_manager/contents/news/move-down-section',
        },
        CALENDAR: {
            SAVE: '/school_manager/calendar/save',
        },
        ORGANIZATION: {
            LOAD_PARENTS: '/school_manager/organizations/load-parent',
            LOAD_CHILDREN: '/school_manager/organizations/load-children',
            SAVE: '/school_manager/organizations/save',
            LOAD: '/school_manager/organizations/load',
            DELETE: '/school_manager/organizations/delete',
            ADD_TO_ORG: '/school_manager/organizations/add-member',
            DELETE_FROM_ORG: '/school_manager/organizations/remove-member',
        },
        FLOW: {
            SAVE: '/school_manager/pipeline/flows/save-flow',
            SAVE_NODE: '/school_manager/pipeline/flows/save-node',
            UPDATE_NODE: '/school_manager/pipeline/flows/update-node',
            LOAD_FLOW_NODES: '/school_manager/pipeline/flows/load-nodes',
            DELETE_FLOW: '/school_manager/pipeline/flows/delete-flow',
            DELETE_NODE: '/school_manager/pipeline/flows/delete-node',
            DELETE_NODE_ATTACHMENT: '/school_manager/pipeline/flows/delete-node-attachment',
            // Action相关
            OPEN: '/api/pipeline/flow/open', // 打开流程
            START_BY_ME: '/api/pipeline/flows/started-by-me', // 获取我的流程
            WAITING_FOR_ME: '/api/pipeline/flows/waiting-for-me', // 获取等待我处理的流程
            START: '/api/pipeline/flow/start', // 流程开始
            PROCESS: '/api/pipeline/flow/process', // 同意流程向下一步
            RESUME: '/api/pipeline/flow/resume', // 流程驳回
            WATCH: '/api/pipeline/flow/watch', // 查看一个流程的最新状态
        }
    },
    YEARS: ['N.A','一年级','二年级','三年级','四年级','五年级','六年级'],
    TERMS: ['N.A','第一学期','第二学期'],
    REPEAT_UNITS: ['每周重复','仅单周重复','仅双周重复'],
    WEEK_DAYS: ['周一','周二','周三','周四','周五','周六','周日',],
    ENQUIRY_TYPES: ['请假','外出, 出差','报销','用章','用车','场地','物品领用','其他'],
    WEEK_NUMBER_ODD: 1, // 单周
    WEEK_NUMBER_EVEN: 2,// 双周
    LOGIC: {
        TIMETABLE: {
            ENQUIRY: 'timetable-enquiry'
        }
    },
    STUDENT_ID_NUMBER: 'purfect.id_number',
    STUDENT_MOBILE: 'purfect.mobile',
    STUDENT_PROFILE: 'purfect.profile',
    TYPE_FILE: 'file',  // 文件类型
    TYPE_CATEGORY: 'category', // 目录类型
    MAX_UPLOAD_FILE_SIZE: 10 * 1024 * 1024, // 最大 10 兆文件上传
    FILE_TYPE:{
        GENERAL: 1,
        IMAGE: 2,
        WORD: 3,
        EXCEL: 4,
        PPT: 5,
        PDF: 6,
        REFERENCE: 7,
        VIDEO: 10,
        AUDIO: 11,
        TXT: 12
    },
    // 文章段落类型的定义
    SECTION_TYPE: {
        TEXT: 1,
        IMAGE: 2,
        VIDEO: 3,
        AUDIO: 4,
        SLIDER: 5, // 轮播图
    },
    ELECTIVE_COURSE: {
        STATUS_APPROVED: 2,
        STATUS_REFUSED: 3,
        STATUS_WAITING: 1,
        STATUS_PUBLISHED: 4,
    }
};