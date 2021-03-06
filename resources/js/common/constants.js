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
        LOAD_INSTITUTES_BY_SCHOOL: '/api/school/load-institute',
        LOAD_DEPARTMENTS_BY_SCHOOL: '/api/school/load-department',
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
            GET_CLASS_LIST: '/api/student-register/get-class-list',
            SAVE_CLASS_INFO: '/api/student-register/save-class-info',
        },
        // 获取省市列表的接口
        LOCATION: {
            PROVINCES: '/api/location/get-provinces',
            CITIES: '/api/location/get-cities',
            DISTRICTS: '/api/location/get-districts',
        },
        RECRUITMENT: {
            LOAD_PLANS: '/api/recruitment/backend-load-plans',
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
            FILE_UPLOAD: '/api/network-disk/media/upload',
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
        WELCOME: {
            LOAD_STEP_DETAILS: '/api/enrolment-step/schoolEnrolmentStep/getEnrolmentInfo',
            LOAD_WHOLE_PROCESS: '/api/enrolment-step/step-list', // 获取某个学校校区的迎新流程中所有步骤
            LOAD_TEACHERS: '/api/school/teachers', // 获取某个学校的所有老师
        },
        CONTACTS: {
            ORG: '/api/campus/handleAffairs/getAddressBook/official',
            GRADE: '/api/campus/handleAffairs/getAddressBook/class',
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
        WELCOMES: {
            SAVE_BASE_INFO: '/manager_welcome/welcome-config/save-base-info',
            SAVE_USER_INFO: '/manager_welcome/welcome-config/save-user-info',
            SAVE_REPORT_CONFIRM_INFO: '/manager_welcome/welcome-config/save-report-confirm-info',
            SAVE_REPORT_BILL_INFO: '/manager_welcome/welcome-config/save-report-bill-info',
            GET_REPORT_LIST_INFO: '/manager_welcome/welcome-config/get-report-list-info',
            DELETE_REPORT_INFO: '/manager_welcome/welcome-config/delete-report-info',
            UP_REPORT_INFO: '/manager_welcome/welcome-config/up-report-info',
            DOWN_REPORT_INFO: '/manager_welcome/welcome-config/down-report-info',
        },
        CALENDAR: {
            SAVE: '/school_manager/calendar/save',
            DELETE: '/school_manager/calendar/delete',
        },
        ORGANIZATION: {
            LOAD_PARENTS: '/school_manager/organizations/load-parent',
            LOAD_CHILDREN: '/school_manager/organizations/load-children',
            SAVE: '/school_manager/organizations/save',
            LOAD: '/school_manager/organizations/load',
            DELETE: '/school_manager/organizations/delete',
            ADD_TO_ORG: '/school_manager/organizations/add-member',
            DELETE_FROM_ORG: '/school_manager/organizations/remove-member',
            // 可见范围选择器专用
            LOAD_BY_ROLES: '/api/organizations/load-by-roles',
            LOAD_BY_PARENT: '/Oa/tissue/getOrganization',
        },
        FLOW: {
            GETFLOWS: '/school_manager/pipeline/flows/load-flows', // 获取某一位置下的分类和流程列表
            SAVE: '/school_manager/pipeline/flows/save-flow',
            SAVE_NODE: '/school_manager/pipeline/flows/save-node',
            SAVE_NODE_OPTION: '/school_manager/pipeline/flows/save-node-option',
            UPDATE_NODE: '/school_manager/pipeline/flows/update-node',
            LOAD_FLOW_NODES: '/school_manager/pipeline/flows/load-nodes',
            DELETE_FLOW: '/school_manager/pipeline/flows/delete-flow',
            DELETE_NODE: '/school_manager/pipeline/flows/delete-node',
            DELETE_NODE_OPTION: '/school_manager/pipeline/flows/delete-node-option',
            DELETE_NODE_ATTACHMENT: '/school_manager/pipeline/flows/delete-node-attachment',
            // Action相关
            OPEN: '/api/pipeline/flow/open', // 打开流程
            START_BY_ME: '/api/pipeline/flows/started-by-me', // 获取我的流程
            WAITING_FOR_ME: '/api/pipeline/flows/waiting-for-me',  // 获取等待我处理的流程
            MY_PROCESSED: '/api/pipeline/flows/my-processed',  // 我审批的流程
            COPY_TO_ME: '/api/pipeline/flows/copy-to-me',  // 抄送我的流程
            START: '/api/pipeline/flow/start', // 流程开始
            PROCESS: '/api/pipeline/flow/process', // 同意流程向下一步
            RESUME: '/api/pipeline/flow/resume', // 流程驳回
            WATCH: '/api/pipeline/flow/watch', // 查看一个流程的最新状态
            CANCEL_ACTION: '/api/pipeline/flow/cancel-action', // 取消一个流程中的 action
            VIEW_ACTION: '/api/pipeline/flow/view-action', // 查看一个流程中的 action
        },
        MESSAGE: {
            LOAD: '/api/notification/list'
        },
        COURSE_MATERIAL: {
            SAVE: '/teacher/course/materials/create',
            LOAD: '/teacher/course/materials/load',
            DELETE: '/teacher/course/materials/delete',
            LOAD_LECTURE: '/api/course/teacher/load-lecture',
            LOAD_LECTURE_MATERIALS: '/api/course/teacher/lecture/load-materials',
            LOAD_STUDENT_HOMEWORK: '/api/course/student/load-homework',
            DELETE_STUDENT_HOMEWORK: '/api/course/student/delete-homework',
            SAVE_LECTURE: '/teacher/course/materials/save-lecture',
            LOAD_LECTURE_HOMEWORKS: '/teacher/course/materials/load-lecture-homeworks',
            SUBMIT_HOMEWORK: '/api/course/student/save-homework',
        },
        GRADE: {
            LOAD: '/teacher/grade/load-students'
        },
        TEACHER_WEB: {
            INDEX: '/api/office/help-page',
            TIME_SCREEN: '/api/signInGrade/timeScreen',
            SIGN_IN_COURSES: '/api/signInGrade/signInCourses',
            SIGN_IN_STUDENTLSIT: '/api/signInGrade/signInStudentList',
            REMARK_LIST: '/api/signInGrade/remarkList',
            GRADE_LIST: '/api/signInGrade/gradeList',
            GRADE_SINGIN: '/api/signInGrade/gradeSignIn',
            GRADE_SINGIN_DETAIL: '/api/signInGrade/gradeSignIn-details',
            GRADE_TODAY_GRADE: '/api/signInGrade/todayGrade',
            GRADE_DETAIL: '/api/signInGrade/gradeDetails',
            STUDENTS_GRADE_LIST: '/api/Oa/grade-list',
            STUDENTS_LIST: '/api/Oa/student-list',
            STUDENTS_INFO: '/api/Oa/student-info',
            STUDENTS_UPDATE_INFO: '/api/Oa/update-student-info'
        },
        BANNER: {
          POST_TYPE: '/school_manager/banner/get-type', // 获取基础信息
          POST_SAVE_BANNER: '/school_manager/banner/save-banner', // 保存信息
          GET_BANNER_ONE: '/school_manager/banner/get-banner-one', // 获取数据
        },
        CAMPUS_VIDEO: {
          GET_CAMPUS_VIDEO: '/school_manager/contents/get-campus-video', // 获取视频
          SAVE_CAMPUS_VIDEO: '/school_manager/contents/save-campus-video', // 保存视频
        },
        MATERIAL: {
          POST_COURSE_URL: '/api/material/myCourse', // 我的课程
          POST_MATERIALS_URL: '/api/material/materials', // 我的课程资料
        },
    },
    YEARS: ['N.A', '一年级', '二年级', '三年级', '四年级', '五年级', '六年级'],
    TERMS: ['N.A', '第一学期', '第二学期'],
    REPEAT_UNITS: ['每周重复', '仅单周重复', '仅双周重复', '指定区间'],
    WEEK_DAYS: ['周一', '周二', '周三', '周四', '周五', '周六', '周日',],
    ENQUIRY_TYPES: ['请假', '外出, 出差', '报销', '用章', '用车', '场地', '物品领用', '其他'],
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
    MAX_UPLOAD_FILE_SIZE: 20 * 1024 * 1024, // 最大 10 兆文件上传
    FILE_TYPE: {
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
    },
    FLOW_ACTION_RESULT: {
        PENDING: 1,
        PENDING_TXT: '审核中',
        PENDING_CLASS: 'text-warning',
        NOTICED: 2,
        NOTICED_TXT: '已阅',
        NOTICED_CLASS: 'text-success',
        PASSED: 3,
        PASSED_TXT: '通过',
        PASSED_CLASS: 'text-success',
        REJECTED: 4,
        REJECTED_TXT: '退回',
        REJECTED_CLASS: 'text-danger',
        TERMINATED: 5,
        TERMINATED_TXT: '驳回, 流程终止',
        TERMINATED_CLASS: 'text-danger',
    },
    FLOW_FINAL_RESULT: {
        PENDING: 0,
        PENDING_TXT: '审核中',
        PENDING_CLASS: 'text-warning',
        DONE: 1,
        DONE_TXT: '已通过',
        DONE_CLASS: 'text-success',
        REJECTED: 2,
        REJECTED_TXT: '未通过',
        REJECTED_CLASS: 'text-danger',
        CANCEL: 3,
        CANCEL_TXT: '已撤回',
        CANCEL_CLASS: 'text-grey-cancel',
    },
    NODE_OPTION: {
        TEXT: '文本',
        DATE: '日期',
        TIME: '时间'
    },
    ADVISER: {
        DEPARTMENT: 1, // 系主任
        GRADE: 2, // 班主任
        STUDY_GROUP: 3, // 教研组长
        STUDENTS: 4, // 班长
    },
    COURSE_MATERIAL_TYPES: {
        TYPE_PRE: 1,
        TYPE_PRE_TXT: '预习材料',
        TYPE_LECTURE: 2,
        TYPE_LECTURE_TXT: '课堂讲义',
        TYPE_AFTER: 3,
        TYPE_AFTER_TXT: '课后阅读',
        TYPE_HOMEWORK: 4,
        TYPE_HOMEWORK_TXT: '随堂作业',
        TYPE_EXAM: 5,
        TYPE_EXAM_TXT: '随堂测试'
    },
    COURSE_MATERIAL_TYPES_TEXT: [
        '预习材料',
        '课堂讲义',
        '课后阅读',
        '随堂作业',
        '随堂测试',
    ],
    NOTICE_TYPE_INSPECT: 3,
    // 教师评教
    TEACHER_QUALIFICATION_TYPES: [
        '论文', '课题', '荣誉称号', '教学', '技能大赛'
    ]
};
