import {Util} from "./utils";
import {Constants} from "./constants";

/**
 * 根据用户来加载所有可能的组织机构
 * @param schoolId
 * @param userUuid
 * @param roles
 * @param affix
 * @returns Promise
 */
export function loadOrganizationsByRoles(schoolId, userUuid, roles, affix) {
    const url = Util.buildUrl(Constants.API.ORGANIZATION.LOAD_BY_ROLES);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {school: schoolId, user: userUuid, roles: roles, version:Constants.VERSION}
    );
}