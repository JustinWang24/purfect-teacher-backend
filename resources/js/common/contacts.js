import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 根据学校的 ID 获取所有的校区与建筑的集合
 * @param schoolId
 * @param affix
 * @returns Promise
 */
export function loadOrgContacts(schoolId, affix) {
    const url = Util.buildUrl(Constants.API.CONTACTS.ORG);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, version:Constants.VERSION}
    );
}

export function loadGradeContacts(schoolId, gradeId, affix) {
    const url = Util.buildUrl(Constants.API.CONTACTS.GRADE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, grade_id: gradeId, version:Constants.VERSION}
    );
}

export function loadGrades(schoolId, affix) {
    const url = Util.buildUrl(Constants.API.CONTACTS.ALL_GRADES);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, version:Constants.VERSION}
    );
}