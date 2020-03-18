import { Util } from "../../../../common/utils";

const getOaTaskListInfoUrl = '/Oa/task/getOaTaskListInfo'
const getOrganizationUrl = '/Oa/tissue/getOrganization'
const getProjectsUrl = '/Oa/project/getOaProjectListInfo'
const getOaProjectUserListInfoUrl = '/Oa/project/getOaProjectUserListInfo'
const addOaTaskInfoUrl = '/Oa/task/addOaTaskInfo'

export function getTaskList (params) {
  const url = Util.buildUrl(getOaTaskListInfoUrl);
  if (Util.isDevEnv()) {
    return axios.get(url, affix);
  }
  return axios.post(
    url,
    params
  );
}


export function getOrganization (params = {}) {
  const url = Util.buildUrl(getOrganizationUrl);
  if (Util.isDevEnv()) {
    return axios.get(url, affix);
  }
  return axios.post(
    url,
    params
  );
}

export function getProjects (params = {}) {
  const url = Util.buildUrl(getProjectsUrl);
  if (Util.isDevEnv()) {
    return axios.get(url, affix);
  }
  return axios.post(
    url,
    params
  );
}

export function getOaProjectUserListInfo (params = {}) {
  const url = Util.buildUrl(getOaProjectUserListInfoUrl);
  if (Util.isDevEnv()) {
    return axios.get(url, affix);
  }
  return axios.post(
    url,
    params
  );
}

export function addOaTaskInfo (params = {}) {
  const url = Util.buildUrl(addOaTaskInfoUrl);
  if (Util.isDevEnv()) {
    return axios.get(url, affix);
  }
  return axios.post(
    url,
    params
  );
}