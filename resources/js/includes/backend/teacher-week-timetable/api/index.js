import fetch from './fetch'
const postUrls = {
  timetable_teacher_week:'/api/study/timetable-teacher-week',
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
  _timetable_teacher_week
} = assetFuns;

