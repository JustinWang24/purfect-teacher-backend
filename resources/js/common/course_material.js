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