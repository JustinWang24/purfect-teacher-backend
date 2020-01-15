import {Constants} from "./constants";
import {Util} from "./utils";
import axios from 'axios';

// 获取省
export function provinces(affix) {
    const url = Util.buildUrl(Constants.API.LOCATION.PROVINCES, affix);
    if(Util.isDevEnv()){
        return axios.get(url);
    }
    return axios.get(url);
}
// 获取城市
export function cities(provinceId, affix) {
    const url = Util.buildUrl(Constants.API.LOCATION.CITIES, affix);
    if(Util.isDevEnv()){
        return axios.get(url);
    }
    return axios.post(
        url,
        {parent_id: provinceId}
    );
}
// 获取城区
export function districts(cityId, affix) {
    const url = Util.buildUrl(Constants.API.LOCATION.DISTRICTS, affix);
    if(Util.isDevEnv()){
        return axios.get(url);
    }
    return axios.post(
        url,
        {parent_id: cityId}
    );
}