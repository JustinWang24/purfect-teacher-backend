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
    }
};