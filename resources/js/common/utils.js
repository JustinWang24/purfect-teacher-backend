/**
 * 一些常用的工具函数
 */
import { Constants } from './constants';

export const Util = {
    /**
     * 根据 id 来定位数组中的 item
     * @param id
     * @param obj
     * @returns {*}
     * @constructor
     */
    GetItemById: function (id, obj) {
        let result = null;
        _.each(obj, item => {
            if(id === item.id){
                result = item;
            }
        });
        return result;
    },
    // 输出学期文字的方法
    GetTermText: function (term) {
        return Constants.TERMS[term - 1];
    },
    GetRepeatUnitText: function (idx) {
        return Constants.REPEAT_UNITS[idx - 1];
    },
    GetWeekdayText: function (idx) {
        return Constants.WEEK_DAYS[idx];
    },
    // 检查是否 ajax 的 response 是一个成功的调用结果
    isAjaxResOk: function(res){
        return res.status === 200 && res.data.code === Constants.AJAX_SUCCESS;
    },
    // 在本环境中是否为空
    isEmpty: function (obj) {
        // null and undefined are "empty"
        if (obj == null) return true;

        // Assume if it has a length property with a non-zero value
        // that that property is correct.
        if (obj.length > 0)    return false;
        if (obj.length === 0)  return true;

        // If it isn't an object at this point
        // it is empty, but it can't be anything *but* empty
        // Is it empty?  Depends on your application.
        if (typeof obj !== "object") return true;

        // Otherwise, does it have any properties of its own?
        // Note that this doesn't handle
        // toString and valueOf enumeration bugs in IE < 9
        for (var key in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, key)) return false;
        }
        return true;
    }
};