import {Util} from "./utils";
import {Constants} from "./constants";

/**
 * 保存课件
 * @param materialModel
 * @param affix
 * @returns Promise
 */
export function saveMaterial(materialModel, affix) {
    const url = Util.buildUrl(Constants.API.COURSE_MATERIAL.SAVE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {material: materialModel, version:Constants.VERSION}
    );
}

/**
 * 加载课件
 * @param id
 * @param affix
 */
export function loadMaterial(id, affix) {
    const url = Util.buildUrl(Constants.API.COURSE_MATERIAL.LOAD);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {id: id, version:Constants.VERSION}
    );
}

/**
 * 删除课件
 * @param id
 * @param affix
 */
export function deleteMaterial(id, affix) {
    const url = Util.buildUrl(Constants.API.COURSE_MATERIAL.DELETE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {id: id, version:Constants.VERSION}
    );
}

/**
 * 加载课件
 * @param index
 * @param teacher
 * @param course
 * @param affix
 */
export function loadLectureByIndex(index, teacher, course, affix) {
    const url = Util.buildUrl(Constants.API.COURSE_MATERIAL.LOAD_LECTURE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {index: index, teacher:teacher, course:course, version:Constants.VERSION}
    );
}