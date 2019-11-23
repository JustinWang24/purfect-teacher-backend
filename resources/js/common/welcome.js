import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 加载教材
 * @param schoolId
 * @param campusId
 * @param type
 * @param stepId
 * @param affix
 * @returns Promise
 */
export function loadWelcomeStepDetails(schoolId, campusId, type, stepId, affix) {
    const url = Util.buildUrl(Constants.API.WELCOME.LOAD_STEP_DETAILS);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.get(
        url,
        {
            school_id: schoolId,
            step_type: type,
            campus_id: campusId,
            step_id: stepId ,
            version:Constants.VERSION
        }
    );
}