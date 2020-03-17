import { Util } from "../../../../common/utils";

const TASK_LIST = '/api/Oa/task/getOaTaskListInfo'

export function getTaskList (params) {
  const url = Util.buildUrl(TASK_LIST);
  if (Util.isDevEnv()) {
    return axios.get(url, affix);
  }
  return axios.post(
    url,
    params
  );
}