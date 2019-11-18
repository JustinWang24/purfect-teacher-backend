/**
 * 一些常用的工具函数
 */
import { Constants } from './constants';
import Lockr from 'lockr';

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
    GetItemIndexByUuid: function (uuid, obj) {
        let result = null;
        _.each(obj, (item, idx) => {
            if(uuid === item.uuid){
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
    },
    // 利用 Local Storage 获取用户最后输入的身份证号码手机号, 或其他数据对象
    getIdNumber: function () {
        return Lockr.get(Constants.STUDENT_ID_NUMBER);
    },
    setStudentIdNumber(number){
        Lockr.set(Constants.STUDENT_ID_NUMBER, number);
    },
    getLocalStudentMobile: function () {
        return Lockr.get(Constants.STUDENT_MOBILE);
    },
    setStudentMobile: function(number){
        Lockr.set(Constants.STUDENT_MOBILE, number);
    },
    setObjToLocalStorage: function(k,obj){
        Lockr.set(k, obj);
    },
    getObjFromLocalStorage: function(k){
        return Lockr.get(k)
    },
    isDevEnv: function(){
        return false;
        // return document.domain.indexOf('.test')>-1 || document.domain.indexOf('.pftytx.com')>-1;
    },
    buildUrl: function(url, affix) {
        // 方便的创建 url, 可以自动判定是测试环境还是生产环境
        const isDev = this.isDevEnv();
        const base = isDev ? 'https://mock.api.pftytx.com' : '';
        if(isDev && this.isEmpty(affix)){
            affix = '/mock.json';
        }
        return base + url + (isDev ? affix : '');
    },
    // 转换文件大小, 从整数到 text
    fileSize: function(sizeInt){
        let size = '';
        if(sizeInt < 1048576){
            size = (sizeInt/1024).toFixed(1) + 'K';
        }
        else{
            size = (sizeInt/1048576).toFixed(1) + 'M';
        }
        return size;
    }
};