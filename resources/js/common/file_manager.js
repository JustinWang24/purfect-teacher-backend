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