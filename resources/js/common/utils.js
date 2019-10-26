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
    /**
     * 根据 id 来定位数组中的 item 的索引
     * @param id
     * @param obj
     * @returns {*}
     * @constructor
     */
    GetItemIndexById: function (id, obj) {
        let result = null;
        _.each(obj, (item, idx) => {
            if(id === item.id){
                result = idx;
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
        // null and undefined 等同于空
        if (obj === null) return true;
        if (obj === '') return true;
        if (typeof obj === 'undefined') return true;

        // 处理数组
        if (obj.length > 0)    return false;
        if (obj.length === 0)  return true;

        // 是否传入的对象自己有属性
        if(typeof obj === 'object'){
            let anyProperty = 0;
            for (let key in obj) {
                anyProperty++;
                break;
            }
            return anyProperty === 0;
        }
        return false;
    },
    pageScrollTo: function(pos){
        // 移动到页面顶部
        document.body.scrollTop = document.documentElement.scrollTop = pos;
    }
};