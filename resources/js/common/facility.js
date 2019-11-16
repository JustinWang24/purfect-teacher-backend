// 加载目录的详情
import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 根据学校的 ID 获取所有的校区与建筑的集合
 * @param schoolId
 * @param affix
 * @returns Promise
 */
export function loadBuildings(schoolId, affix) {
    const url = Util.buildUrl(Constants.API.LOAD_BUILDINGS_BY_SCHOOL);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, version:Constants.VERSION}
    );
}

/**
 * 根据建筑的 ID 获取所有的房间
 * @param buildingId
 * @param affix
 * @returns Promise
 */
export function loadRoomsByBuilding(buildingId, affix) {
    const url = Util.buildUrl(Constants.API.LOAD_ROOMS_BY_BUILDING);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {building: buildingId, version:Constants.VERSION}
    );
}

/**
 * 根据建筑的 ID 和其他的查询条件 获取所有的房间
 * @param schoolId
 * @param buildingId
 * @param year
 * @param term
 * @param weekday_index
 * @param time_slot_id
 * @param affix
 * @returns Promise
 */
export function loadAvailableRoomsByBuilding(schoolId, buildingId, year, term, weekday_index,time_slot_id , affix) {
    const url = Util.buildUrl(Constants.API.LOAD_AVAILABLE_ROOMS_BY_BUILDING);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {
            school: schoolId,
            building: buildingId,
            year: year,
            term: term,
            weekday_index: weekday_index,
            timeSlot: time_slot_id,
            version:Constants.VERSION
        }
    );
}