import {
  TaskApi
} from './api'
export function getQueryString(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
  var r = window.location.search.substr(1).match(reg);
  if (r != null) return unescape(r[2]);
  return null;
}

export function getFileURL(file) {
  let getUrl = null;
  if (window.createObjectURL !== undefined) { // basic
    getUrl = window.createObjectURL(file);
  } else if (window.URL !== undefined) { // mozilla(firefox)
    getUrl = window.URL.createObjectURL(file);
  } else if (window.webkitURL !== undefined) { // webkit or chrome
    getUrl = window.webkitURL.createObjectURL(file);
  }
  return getUrl;
}

function debounce(func, wait, immediate) {
  let timer;
  return function () {
    let context = this;
    let args = arguments;

    if (timer) clearTimeout(timer);
    if (immediate) {
      var callNow = !timer;
      timer = setTimeout(() => {
        timer = null;
      }, wait)
      if (callNow) func.apply(context, args)
    } else {
      timer = setTimeout(function () {
        func.apply(context, args)
      }, wait);
    }
  }
}

const searchMember = function (keyword, call) {
  if (!keyword) {
    return
  }
  TaskApi.excute("getOrganization", {
    keyword: keyword,
    type: 2
  }).then(res => {
    call(res)
  })
}

export const searchMemberDebounce = debounce(searchMember, 500)
