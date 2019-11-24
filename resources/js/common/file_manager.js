import {Constants} from "./constants";
import {Util} from "./utils";

// 加载目录的详情
export function loadCategory(userUuid, categoryId, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.LOAD_CATEGORY);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, uuid: categoryId, version:Constants.VERSION}
    );
}

// 加载父级目录, 根据给定的当前目录的 uuid 或者 id
export function loadParentCategory(userUuid, uuid, affix) {
    // const url = Util.buildUrl(Constants.API.FILE_MANAGER.LOAD_PARENT_CATEGORY);
    // if(Util.isDevEnv()){
    //     return axios.get(url, affix);
    // }
    return axios.post(
        url,
        {user: userUuid, uuid: uuid, version:Constants.VERSION}
    );
}

// 编辑目录/文件的方法
export function renameAction(userUuid, file, type, affix) {
    let url = null;
    if(type === Constants.TYPE_CATEGORY){
        url = Util.buildUrl(Constants.API.FILE_MANAGER.EDIT_CATEGORY);
    }else{
        url = Util.buildUrl(Constants.API.FILE_MANAGER.FILE_EDIT);
    }
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, uuid: file.uuid, name: file.name, version:Constants.VERSION}
    );
}

// 编辑目录/文件的方法
export function deleteAction(userUuid, file, type, affix) {
    let url = null;
    if(type === Constants.TYPE_CATEGORY){
        url = Util.buildUrl(Constants.API.FILE_MANAGER.DELETE_CATEGORY);
    }else{
        url = Util.buildUrl(Constants.API.FILE_MANAGER.FILE_DELETE);
    }
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, uuid: file.uuid, version:Constants.VERSION}
    );
}

// 创建目录的方法
export function createNewCategoryAction(userUuid, parent, name, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.CREATE_CATEGORY);

    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, parent: parent, name: name, version:Constants.VERSION}
    );
}

// 获取最新的文件列表
export function recentFilesAction(userUuid, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.RECENT_FILES);

    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, version:Constants.VERSION}
    );
}

// 获取我的网盘大小
export function networkDiskSizeAction(userUuid, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.GET_NETWORK_DISK_SIZE);

    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, version:Constants.VERSION}
    );
}

// 更新星标
export function updateAsteriskAction(userUuid, fileUuid, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.UPDATE_ASTERISK);

    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, uuid: fileUuid, version:Constants.VERSION}
    );
}

// 搜索文件
export function searchFileAction(userUuid, query, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.FILE_SEARCH);

    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, keywords: query, version:Constants.VERSION}
    );
}

/**
 * 移动文件
 * @param userUuid 操作的用户
 * @param categoryUuid 移动到的文件夹
 * @param fileUuid 被移动的文件
 * @param affix
 * @returns Promise
 */
export function moveFileAction(userUuid, categoryUuid, fileUuid, affix) {
    const url = Util.buildUrl(Constants.API.FILE_MANAGER.FILE_MOVE);

    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user: userUuid, category: categoryUuid, file_uuid: fileUuid, version:Constants.VERSION}
    );
}