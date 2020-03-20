import {
  Util
} from "../../../../common/utils";

const message = Vue.prototype.$message

const API_MAP = {
  getOaTaskListInfo: '/Oa/task/getOaTaskListInfo',
  getOrganization: '/Oa/tissue/getOrganization',
  getOaProjectListInfo: '/Oa/project/getOaProjectListInfo',
  getOaProjectUserListInfo: '/Oa/project/getOaProjectUserListInfo',
  addOaTaskInfo: '/Oa/task/addOaTaskInfo',
  getOaTaskInfo: '/Oa/task/getOaTaskInfo',
  addOaTaskForum: '/Oa/task/addOaTaskForum',
  delOaTaskForum: '/Oa/task/delOaTaskForum',
  finishOaTaskInfo: '/Oa/task/finishOaTaskInfo',
  addOaTaskUser: '/Oa/task/addOaTaskUser',
  receiveOaTaskInfo: '/Oa/task/receiveOaTaskInfo',
  getOaTaskReport: '/Oa/task/getOaTaskReport'
}

export const TaskApi = {
  excute: function (fn, params = {}) {
    return new Promise((resolve, reject) => {
      const url = Util.buildUrl(API_MAP[fn]);
      if (Util.isDevEnv()) {
        return axios.get(url, affix).then(res => {
          if (Util.isAjaxResOk(res)) {
            resolve(res)
          } else {
            message.error(res.data.message);
            reject(res)
          }
        });
      }
      return axios.post(
        url,
        params
      ).then(res => {
        if (Util.isAjaxResOk(res)) {
          resolve(res)
        } else {
          message.error(res.data.message);
          reject(res)
        }
      });
    })
  }
}

export const finishTask = function (data) {
  return axios({
    method: 'post',
    url: API_MAP.finishOaTaskInfo,
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    },
    data
  })
}
