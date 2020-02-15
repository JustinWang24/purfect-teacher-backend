/**
 * 一些常用的工具函数
 */
import { Constants } from './constants';
import Lockr from 'lockr';
import moment from 'moment';

export const Util = {
    /**
     * 根据 id 来定位数组中的 item
     * @param id
     * @param obj: Array
     * @returns {*}
     * @constructor
     */
    GetItemById: function (id, obj) {
        let result = null;
        if(Array.isArray(obj)){
            // 传入的是数组
            obj.forEach((item) => {
                if(id === item.id){
                    result = item;
                }
            });
        }
        else{
            // 传入的是对象
            const keys = Object.keys(obj);
            keys.forEach( key => {
                if(id === obj[key].id){
                    result = obj[key];
                }
            })
        }
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
        if(Array.isArray(obj)){
            obj.forEach((item, idx) => {
                if(id === item.id){
                    result = idx;
                }
            });
        }
        else{
            const keys = Object.keys(obj);
            keys.forEach( key => {
                if(id === obj[key].id){
                    result = key;
                }
            })
        }
        return result;
    },
    /**
     * 数组换位
     * @param arr
     * @param index1
     * @param index2
     * @returns {*}
     */
    swapArray: function (arr, index1, index2) {
        arr[index1] = arr.splice(index2, 1, arr[index1])[0];
        return arr;
    },
    // 判断文章段落类型的通用方法
    checkArticleSectionType: function(section){
        if(this.isEmpty(section.media_id)){
            return Constants.SECTION_TYPE.TEXT;
        }
        else{
            return Constants.SECTION_TYPE.IMAGE;
        }
    },
    GetItemIndexByUuid: function (uuid, obj) {
        let result = null;
        obj.forEach((item, idx) => {
            if(uuid === item.uuid){
                result = idx;
            }
        });

        return result;
    },
    // 输出学期文字的方法
    GetTermText: function (term) {
        return Constants.TERMS[term];
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
            if(obj instanceof Date){
                // 日期类型, 则肯定不是空的
            }
            else{
                let anyProperty = 0;
                for (let key in obj) {
                    anyProperty++;
                    break;
                }
                return anyProperty === 0;
            }
        }
        return false;
    },
    // 验证日期格式
    checkData: function (str) {
        var a = /^(\d{4})-(\d{2})-(\d{2})$/
        if (!a.test(str)){
            return false
        } else {
            return true
        }
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
    },
    isImage: function (type) {
        return type === Constants.FILE_TYPE.IMAGE;
    },
    isWordDoc: function (type) {
        return type === Constants.FILE_TYPE.WORD;
    },
    isExcelDoc: function (type) {
        return type === Constants.FILE_TYPE.EXCEL;
    },
    isPowerPointDoc: function (type) {
        return type === Constants.FILE_TYPE.PPT;
    },
    isPdfDoc: function (type) {
        return type === Constants.FILE_TYPE.PDF;
    },
    isVideoDoc: function (type) {
        return type === Constants.FILE_TYPE.VIDEO;
    },
    isAudioDoc: function (type) {
        return type === Constants.FILE_TYPE.AUDIO;
    },
    isTxtDoc: function (type) {
        return type === Constants.FILE_TYPE.TXT;
    },
    isGeneralDoc: function (type) {
        return type === Constants.FILE_TYPE.GENERAL;
    },
    // 返回系统图标所在的数组
    icons: function () {
        const a = [];
        for(let i=1;i<14;i++){
            a.push('/assets/img/pipeline/icon'+i+'@2x.png');
        }
        for(let i=1;i<18;i++){
            a.push('/assets/img/pipeline/t'+i+'@2x.png');
        }
        return a;
    },
    reloadCurrentPage: function(vm){
        vm.$message({type:'success',message:'正在刷新 ... '});
        window.location.reload();
    },
    /**
     * 比较两个给定日期的间隔描述
     * @param t1
     * @param t2
     */
    diffInSecond: function(t1, t2){
        const earlier = moment(t1);
        if(this.isEmpty(t2)){
            return moment().diff(earlier,"hours");
        }
        else{
            return moment(t2).diff(earlier,'hours');
        }
    },
    buildQuery: function(url, params, tail){
        let query = '?';
        const keys = Object.keys(params);
        keys.forEach(function(key){
            query += key + '=' + params[key] + '&';
        });
        return url + query + tail + '=1';
    },
    // 富文本编辑器所需的插件和配置
    getWysiwygGlobalOption: function(teacherUuid){
        return {
            lang:'zh_cn', // 语言使用中文
            plugins: [    // 所要加载的插件
                'fontsize',
                'fontcolor',
                'alignment',
                'fontfamily',
                'table',
                'specialchars',
                'imagemanager',
                'filemanager',
            ],
            fileUpload: '/api/wysiwyg/files/upload?uuid=' + teacherUuid,  // 文件上传的 Action
            fileManagerJson: '/api/wysiwyg/files/view?uuid=' + teacherUuid, // 已存在的文件的资源 URL, 返回为 json 格式
            imageUpload: '/api/wysiwyg/images/upload?uuid=' + teacherUuid, // 图片上传的 Action
            imageManagerJson: '/api/wysiwyg/images/view?uuid=' + teacherUuid, // 已存在的图片的资源 URL, 返回为 json 格式
        }
    },
    getTeacherQualificationTypes: function(){
        return Constants.TEACHER_QUALIFICATION_TYPES;
    }
};
