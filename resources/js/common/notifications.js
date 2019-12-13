import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 根据学校的 ID 获取所有的校区与建筑的集合
 * @param schoolId
 * @param affix
 * @returns Promise
 */
export function loadMessages(schoolId, affix) {
    const url = Util.buildUrl(Constants.API.NEWS.SAVE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, version:Constants.VERSION}
    );
}