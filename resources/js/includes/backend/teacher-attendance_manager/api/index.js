import fetch from './fetch'
const postUrls = {
  load_attendance:'/school_manager/teacher-attendance/load-attendance',
  load_manager:'/school_manager/teacher-attendance/load-manager',
  load_children:'/school_manager/organizations/load-children',
  save_attendance:'/school_manager/teacher-attendance/save-attendance',
  save_clocksets:'/school_manager/teacher-attendance/save-clocksets',
  load_all:'/school_manager/organizations/load-all',
  save_exceptionday:'/school_manager/teacher-attendance/save-exceptionday',
  delete_exceptionday:'/school_manager/teacher-attendance/delete-exceptionday'
}
const getUrls = {
}
let assetFuns = {};
Object.keys(getUrls).forEach((item, index) => {
  assetFuns["_" + item] = parmas => {
    return fetch.get(getUrls[item], parmas);
  };
});
Object.keys(postUrls).forEach((item, index) => {
  assetFuns["_" + item] = parmas => {
    return fetch.post(postUrls[item], parmas);
  };
});
export const catchErr = promise => {
  return promise
    .then(function() {
      return [null, ...arguments]
    })
    .catch(err => {
      return [err, null]
    })
}
export const {
  _load_attendance,
  _load_manager,
  _load_children,
  _save_attendance,
  _save_clocksets,
  _load_all,
  _save_exceptionday,
  _delete_exceptionday
} = assetFuns;

