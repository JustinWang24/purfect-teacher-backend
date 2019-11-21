import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 加载教材
 * @param schoolId
 * @param affix
 * @returns Promise
 */
export function loadTextbooks(schoolId, affix) {
    const url = Util.buildUrl(Constants.API.TEXTBOOK.LOAD_TEXTBOOKS);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.get(
        url,
        {school: schoolId, version:Constants.VERSION}
    );
}

/**
 * 分页加载教材
 * @param schoolId
 * @param userUuid
 * @param pageNumber
 * @param affix
 * @returns Promise
 */
export function loadTextbooksPaginate(schoolId, userUuid, pageNumber, pageSize, affix) {
    const url = Util.buildUrl(Constants.API.TEXTBOOK.LOAD_TEXTBOOKS_PAGINATE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        Constants.API.TEXTBOOK.LOAD_TEXTBOOKS_PAGINATE,
        {school: schoolId, user_uuid: userUuid, pageNumber: pageNumber, pageSize: pageSize}
    )
}

/**
 * 关联教材与课程
 * @param schoolId
 * @param courseId
 * @param booksId
 * @param affix
 * @returns Promise
 */
export function attachTextbooksToCourse(schoolId, courseId, booksId, affix) {
    const url = Util.buildUrl(Constants.API.TEXTBOOK.ATTACH_TEXTBOOKS);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, course_id: courseId, textbook_ids: booksId, version:Constants.VERSION}
    );
}