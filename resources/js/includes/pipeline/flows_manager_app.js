/**
 * 工作流程管理
 */
import { deleteFlow, deleteNode, loadNodes, saveFlow, saveNode, updateNode, saveNodeOption, deleteNodeOption } from "../../common/flow";
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('pipeline-flows-manager-app')){
    new Vue({
        el:'#pipeline-flows-manager-app',
        data(){
            return {
                currentFlow: {
                    name: '',
                    id: '',
                    type: 0,
                    school_id: null,
                    icon: '',
                },
                selectedImgUrl: '',
                lastNewFlow: null,
                schoolId: '',
                flowFormFlag: false,
                nodeFormFlag: false,    // 步骤编辑表单显示
                nodeOptionsFormFlag: false,    // 步骤必填项表单显示
                nodeOption:{ // 步骤必填项表单
                    id:null,
                    name:'',
                    type:'',
                    node_id: null,
                },
                nodeOptionTypes:[Constants.NODE_OPTION.TEXT, Constants.NODE_OPTION.DATE], // 步骤必填项的数据类型
                showFileManagerFlag: false,
                iconSelectorShowFlag: false, // 控制图标选择器的显示
                node: {
                    id: null,
                    name: '', // 步骤名称
                    description: '', // 创建新流程时, 发起流程的第一步的说明
                    options: [], // node 流程步骤的必填项集合
                    handlers: [], // node 流程步骤的处理人
                    organizations: [], // node 流程步骤针对的部门
                    attachments: [], // node 流程步骤关联的附件
                    notice_to: [], // node 流程步骤关联的附件
                    titles: [], // node 流程步骤针对的部门的角色
                },
                prevNodeId:null, // 编辑 node 的时候, 前一个步骤的 ID
                organizationsTabArrayWhenEdit: [],
                flowNodes: [],
                loadingNodes: false, // 正在加载步骤
                props: {
                    lazy: true,
                    multiple: true,
                    value:'id',
                    label:'name',
                    lazyLoad (node, resolve) {
                        let parentId = null;
                        if(!Util.isEmpty(node.data)){
                            parentId = node.data.id;
                        }
                        axios.post(
                            Constants.API.ORGANIZATION.LOAD_CHILDREN,
                            {level: node.level+1, parent_id: parentId}
                        ).then(res => {
                            if(Util.isAjaxResOk(res)){
                                resolve(res.data.data.orgs);
                            }
                        });
                    }
                }
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.currentFlow.school_id = this.schoolId;

            // 可能是刚刚创建了新流程, 检查一下
            this.lastNewFlow = dom.dataset.newflow;
            if(!Util.isEmpty(this.lastNewFlow)){
                // 去加载这个流程的所有节点
                this.loadFlowNodes(this.lastNewFlow);
            }
        },
        methods:{
            // 步骤所需要的必填项的管理
            editNodeOptions: function(node, theOption){
                this.nodeOptionsFormFlag = true;
                this._setEditingNode(node);
                if(!Util.isEmpty(theOption)){
                    this.editNodeOption(theOption);
                }
            },
            editNodeOption: function(theOption){
                this.nodeOption.id = theOption.id;
                this.nodeOption.name = theOption.name;
                this.nodeOption.type = theOption.type;
                this.nodeOption.node_id = theOption.node_id;
            },
            removeNodeOption: function(option, index){
                const result = confirm('此操作将彻底删除必填项目: ' + option.name + ', 是否继续?');
                if(result === true){
                    deleteNodeOption(option.id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.node.options.splice(index, 1);
                            this.$message({
                                type:'success',
                                message: '删除成功'
                            });
                        }
                        else{
                            this.$message.error(res.data.message);
                        }
                    })

                }
            },
            onNodeOptionFormSubmit: function(){
                // 准备数据
                if(Util.isEmpty(this.nodeOption.name)){
                    this.$message.error('选项的名称为必填项');
                    return;
                }
                if(Util.isEmpty(this.nodeOption.type)){
                    this.$message.error('请选择选项的数据类型');
                    return;
                }
                this.nodeOption.node_id = this.node.id;
                saveNodeOption(this.nodeOption).then(res => {
                    if(Util.isAjaxResOk(res)){
                        // 保存成功
                        if(this.nodeOption.id !== res.data.data.id){
                            // 新建 option 的返回结果
                            this.node.options.push({
                                id: res.data.data.id,
                                name: this.nodeOption.name,
                                type: this.nodeOption.type,
                                node_id: this.nodeOption.node_id
                            });
                        }
                        else{
                            const idx = Util.GetItemIndexById(res.data.data.id, this.node.options);
                            this.node.options[idx].name = this.nodeOption.name;
                            this.node.options[idx].type = this.nodeOption.type;
                        }
                        this.nodeOption.id = null;
                        this.nodeOption.name = '';
                        this.nodeOption.type = '';
                        this.nodeOption.node_id = null;
                        this.$message({type:'success',message:'保存成功'});
                    }
                    else {
                        this.$message.error(res.data.message);
                    }
                });
            },
            // 步骤所需要的必填项的管理 结束
            loadFlowNodes: function(flowId, flowName){
                this.currentFlow.name = flowName;
                this.currentFlow.id = flowId;
                this.loadingNodes = true;
                loadNodes(flowId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.currentFlow = res.data.data.flow;
                        this.flowNodes = res.data.data.nodes;
                    }
                    else{
                        this.$notify.error(
                            {title: '加载失败',message: res.data.message, duration: 0}
                        );
                    }
                    this.loadingNodes = false;
                })
            },
            createNewFlow: function(type){
                this.currentFlow.name = '';
                this.currentFlow.id = '';
                this.currentFlow.type = type;
                this._resetNodeForm();
                this.flowFormFlag = true;
            },
            onNewFlowSubmit: function(){
                if(this.currentFlow.name.trim() === ''){
                    this.$notify.error({
                        title: '错误',
                        message: '流程的名称必须填写'
                    });
                    return;
                }
                if(this.node.description.trim() === ''){
                    this.$notify.error({
                        title: '错误',
                        message: '新流程必须填写说明信息 - "如何发起流程"'
                    });
                    return;
                }
                if(this.node.organizations.length === 0 && this.node.handlers.length === 0){
                    this.$notify.error({
                        title: '错误',
                        message: '新流程必须选择: 哪些用户可以发起本流程'
                    });
                    return;
                }
                // 创建新的流程
                saveFlow(this.currentFlow, this.node).then(res => {
                    if(Util.isAjaxResOk(res)){
                        window.location.href = '/school_manager/pipeline/flows/manager?lastNewFlow=' + res.data.data.id;
                    }
                    else{
                        this.$notify.error(
                            {title: '保存失败',message: res.data.message, duration: 0}
                        );
                    }
                })
            },
            deleteFlow: function(){
                this.$confirm('此操作将彻底删除此流程, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loadingNodes = true;
                    deleteFlow(this.currentFlow.id, this.schoolId).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({type:'success',message:'删除成功, 页面将重新加载, 请稍候!'});
                            window.location.reload();
                        }
                        else{
                            this.$message.error('系统繁忙, 请稍候再试');
                        }
                        this.loadingNodes = false;
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            // 对 node 的操作
            deleteNode: function(idx, node){
                this.$confirm('此操作将永久删除步骤: "'+node.name+'", 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    deleteNode(node.id, this.schoolId).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.flowNodes.splice(idx, 1);
                            this.$message({type:'success',message: '删除成功'});
                        }
                        else{
                            this.$message.error(res.data.message);
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            _resetNodeForm: function(){
                this.node.id = null;
                this.node.description = '';
                this.node.name = '';
                this.node.handlers = [];
                this.node.organizations = [];
                this.node.titles = [];
                this.node.notice_to = [];
                this.organizationsTabArrayWhenEdit = [];
            },
            // 创建新的步骤
            createNewNode: function(){
                this.nodeFormFlag = true;
                if(this.flowNodes.length > 0){
                    // 默认的新的前一个步骤的 id
                    this.prevNodeId = this.flowNodes[this.flowNodes.length - 1].id;
                }
                this._resetNodeForm();
            },
            editNode: function(node){
                this.nodeFormFlag = true;
                this._setEditingNode(node);
            },
            _setEditingNode: function(node){
                this.prevNodeId = node.prev_node;
                this.node.id = node.id;
                this.node.description = node.description;
                this.node.name = node.name;
                this.node.handlers = node.handler.role_slugs === '' ? [] : this.splitHelper(node.handler.role_slugs);
                this.organizationsTabArrayWhenEdit = node.handler.organizations === '' ? [] : this.splitHelper(node.handler.organizations);
                this.node.titles = node.handler.titles === '' ? [] : this.splitHelper(node.handler.titles);
                this.node.notice_to = node.handler.notice_to === '' ? [] : this.splitHelper(node.handler.notice_to);
                this.node.options = node.options;
            },
            splitHelper: function(str){
                return str.substring(0,str.length -1).split(';')
            },
            // 保存选定的步骤
            onNodeFormSubmit: function(){
                if(this.node.name.trim() === ''){
                    this.$notify.error({
                        title: '错误',
                        message: '步骤的名称必须填写'
                    });
                    return;
                }
                if(this.node.description.trim() === ''){
                    this.$notify.error({
                        title: '错误',
                        message: '步骤的说明必须填写'
                    });
                    return;
                }

                if(this.node.notice_to.length === 0){
                    this.$confirm('没有指定下一步的负责人, 表示这将是本流程的最后一步, 是否确认?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this._updateNode();
                    }).catch(() => {

                    });
                }
                else{
                    this._updateNode();
                }
            },
            _updateNode: function(){
                if(Util.isEmpty(this.node.id)){
                    // 创建新步骤的操作
                    if(this.node.organizations.length === 0 && this.node.handlers.length === 0){
                        this.$notify.error({
                            title: '错误',
                            message: '流程必须选择相关的部门或用户群'
                        });
                        return;
                    }

                    saveNode(this.currentFlow, this.node, this.prevNodeId).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.flowNodes = res.data.data.nodes;
                            this.prevNodeId = null;
                            this.nodeFormFlag = false;
                            this.$message({type:'success', message:'步骤保存成功'});
                            this._resetNodeForm();
                        }
                        else{
                            this.$notify.error(
                                {title: '保存失败',message: res.data.message, duration: 0}
                            );
                        }
                    });
                }
                else{
                    // 更新操作
                    if(
                        this.node.organizations.length === 0
                        && this.organizationsTabArrayWhenEdit.length ===0
                        && this.node.handlers.length === 0
                    ){
                        this.$notify.error({
                            title: '错误',
                            message: '流程必须选择相关的部门或用户群'
                        });
                        return;
                    }

                    updateNode(this.currentFlow, this.node, this.prevNodeId, this.organizationsTabArrayWhenEdit)
                        .then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.flowNodes = res.data.data.nodes;
                                this.prevNodeId = null;
                                this.organizationsTabArrayWhenEdit = [];
                                this.nodeFormFlag = false;
                                this.$message({type:'success', message:'步骤保存成功'});
                                this._resetNodeForm();
                            }
                            else{
                                this.$notify.error(
                                    {title: '保存失败',message: res.data.message, duration: 0}
                                );
                            }
                        });
                }
            },
            // 选择步骤的附件所用的监听器
            pickFileHandler: function(payload){
                this.showFileManagerFlag = false;
                const attachment = {
                    id:null,
                    node_id: this.node.id,
                    media_id: payload.file.id,
                    url: payload.file.url,
                    file_name: payload.file.file_name
                };
                this.node.attachments.push(attachment);
            },
            dropAttachment: function(idx, attachment, nodeIndex){
                if(!Util.isEmpty(attachment.id)){
                    // 从服务器删除
                    deleteNodeAttachment(attachment.id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            if(Util.isEmpty(nodeIndex)){
                                this.node.attachments.splice(idx, 1);
                            }else{
                                this.flowNodes[nodeIndex].attachments.splice(idx, 1);
                            }
                        }else{
                            this.$message.error('无法删除附件');
                        }
                    })
                }else{
                    this.node.attachments.splice(idx, 1);
                }
            },
            iconSelectedHandler: function(payload){
                this.currentFlow.icon = payload.url;
                this.selectedImgUrl = payload.url;
                this.iconSelectorShowFlag = false;
            },
            // 辅助函数
            timelineItemTitle: function(idx, node){
                if(idx === 0){
                    return '流程开始: ' + node.name;
                }
                else{
                    return '第'+ idx +'步: ' + node.name;
                }
            },
            handleOrganizationTagClose: function (idx) {
                this.$confirm('此操作将从本步骤中删除此部门, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.organizationsTabArrayWhenEdit.splice(idx, 1);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            }
        }
    });
}