import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * Func 保存迎新基础信息
 * @param school_id
 * @param data 数据
 * @param affix
 * @returns Promise
 */
export function saveBaseInfo(schoolId, data, affix) {
    const url = Util.buildUrl(Constants.API.WELCOMES.SAVE_BASE_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * Func 保存个人信息
 * @param school_id
 * @param data 数据
 * @param affix
 * @returns Promise
 */
export function saveUserInfo(schoolId, data, affix) {
    const url = Util.buildUrl(Constants.API.WELCOMES.SAVE_USER_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * Func 报到确认
 * @param school_id
 * @param affix
 * @returns Promise
 */
export function saveReportConfirmInfo(schoolId, data, affix) {
    const url = Util.buildUrl(Constants.API.WELCOMES.SAVE_REPORT_CONFIRM_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * Func 报到单
 * @param school_id
 * @param affix
 * @returns Promise
 */
export function saveReportBillInfo(schoolId, data, affix) {
    const url = Util.buildUrl(Constants.API.WELCOMES.SAVE_REPORT_BILL_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * Func 获取报到流程
 * @param school_id
 * @param affix
 * @returns Promise
 */
export function getConfigStepListInfo(schoolId, data, affix) {
    const url = Util.buildUrl(Constants.API.WELCOMES.GET_REPORT_LIST_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * Func 删除单个流程
 * @param school_id
 * @param affix
 * @returns Promise
 */
export function deleteConfigStep(schoolId, data, affix) {
    const url = Util.buildUrl(Constants.API.WELCOMES.DELETE_REPORT_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * 流程向上
 * @param schoolId
 * @param data
 * @returns {*}
 */
export function upConfigStep(schoolId, data) {
    const url = Util.buildUrl(Constants.API.WELCOMES.UP_REPORT_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}

/**
 * 流程向下
 * @param schoolId
 * @param data
 * @returns {*}
 */
export function downConfigStep(schoolId, data) {
    const url = Util.buildUrl(Constants.API.WELCOMES.DOWN_REPORT_INFO);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, data: data, version:Constants.VERSION}
    );
}
}
