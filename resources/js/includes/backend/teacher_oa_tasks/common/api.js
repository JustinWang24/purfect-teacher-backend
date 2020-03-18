import {
  Util
} from "../../../../common/utils";

const API_MAP = {
  getOaTaskListInfo: '/Oa/task/getOaTaskListInfo',
  getOrganization: '/Oa/tissue/getOrganization',
  getOaProjectListInfo: '/Oa/project/getOaProjectListInfo',
  getOaProjectUserListInfo: '/Oa/project/getOaProjectUserListInfo',
  addOaTaskInfo: '/Oa/task/addOaTaskInfo',
  getOaTaskInfo: '/Oa/task/getOaTaskInfo',
  addOaTaskForum: '/Oa/task/addOaTaskForum',
  delOaTaskForum: '/Oa/task/delOaTaskForum',
  finishOaTaskInfo: '/Oa/task/finishOaTaskInfo'
}

export const TaskApi = {
  excute: function (fn, params = {}) {
    const url = Util.buildUrl(API_MAP[fn]);
    if (Util.isDevEnv()) {
      return axios.get(url, affix);
    }
    return axios.post(
      url,
      params
    );
  }
}
