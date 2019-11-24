import {Constants} from "./constants";
import {Util} from "./utils";

export function saveRegistrationForm(form, majorId, affix) {
    if(Util.isDevEnv()){
        return axios.get(Util.buildUrl(Constants.API.REGISTRATION_FORM.SUBMIT_FORM, affix));
    }
    return axios.post(
        Util.buildUrl(Constants.API.REGISTRATION_FORM.SUBMIT_FORM, affix),
        {form: form, recruitment_plan_id: majorId,version:Constants.VERSION}
    );
}

export function queryStudentProfile(idNumber,mobile) {
    if(Util.isDevEnv()){
        return axios.get(Util.buildUrl(Constants.API.REGISTRATION_FORM.QUERY_STUDENT_PROFILE));
    }
    return axios.post(
        Util.buildUrl(Constants.API.REGISTRATION_FORM.QUERY_STUDENT_PROFILE),
        {id_number: idNumber, mobile: mobile,version:Constants.VERSION}
    );
}

export function loadOpenedMajors(idNumber,mobile) {
    if(Util.isDevEnv()){
        return axios.get(Util.buildUrl(Constants.API.REGISTRATION_FORM.QUERY_STUDENT_MAJORS));
    }
    return axios.post(
        Util.buildUrl(Constants.API.REGISTRATION_FORM.QUERY_STUDENT_MAJORS),
        {id_number: idNumber, mobile: mobile,version:Constants.VERSION}
    );
}

export function loadMajorDetail(majorId) {
    if(Util.isDevEnv()){
        return axios.get(Util.buildUrl(Constants.API.REGISTRATION_FORM.LOAD_MAJOR_DETAIL));
    }
    return axios.post(
        Util.buildUrl(Constants.API.REGISTRATION_FORM.LOAD_MAJOR_DETAIL),
        {id: majorId, version:Constants.VERSION}
    );
}