import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 根据学校的 ID 获取所有的校区与建筑的集合
 * @param schoolId
 * @param affix
 * @returns Promise
 */
export function saveNews(schoolId, news, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.SAVE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, news: news, version:Constants.VERSION}
    );
}

export function publishNews(schoolId, newsId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.PUBLISH);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, news_id: newsId, version:Constants.VERSION}
    );
}

export function deleteNews(schoolId, newsId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.DELETE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, news_id: newsId, version:Constants.VERSION}
    );
}

export function loadNews(newsId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.LOAD);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {news_id: newsId, version:Constants.VERSION}
    );
}

export function saveSections(newsId, sections, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.SAVE_SECTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {news_id: newsId, sections: sections, version:Constants.VERSION}
    );
}

/**
 * 删除文章的段落
 * @param sectionId
 * @param affix
 * @returns {*}
 */
export function deleteSection(sectionId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.DELETE_SECTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {section_id: sectionId, version:Constants.VERSION}
    );
}

/**
 * 删除文章的段落
 * @param sectionId
 * @param affix
 * @returns {*}
 */
export function moveUpSection(sectionId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.MOVE_UP_SECTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {section_id: sectionId, version:Constants.VERSION}
    );
}


/**
 * 删除文章的段落
 * @param sectionId
 * @param affix
 * @returns {*}
 */
export function moveDownSection(sectionId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.MOVE_DOWN_SECTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {section_id: sectionId, version:Constants.VERSION}
    );
}