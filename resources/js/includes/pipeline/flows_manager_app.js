/**
 * 工作流程管理
 */
import { deleteFlow, loadNodes, saveFlow } from "../../common/flow";
import { Constants } from "../../common/constants";
import { Util } from "../../common/utils";

if (document.getElementById('pipeline-flows-manager-app')) {
    new Vue({
        el: '#pipeline-flows-manager-app',
        data() {
            return {
                loadingNodes: false, // 正在加载步骤
                newFlow: true, // 立即创建显示
                posiList: [{ name: '办公审批', key: 1 }, { name: '办事大厅', key: 2 }, { name: '系统流程', key: 3 }],
                typeList: [],
                organizationList: [{ name: '组织架构人员', key: 1 }, { name: '其他职务人员', key: 2 }],
                iconSelectorShowFlag: false, // 控制图标选择器的显示
                selectedImgUrl: '',
                businessList: [],
                posiType: '', // 显示位置
                role: '', // 角色类型
                flow: {
                    type: '', // 流程分类
                    name: '', // 流程名称
                    icon: '',
                    business: '',
                    school_id: ''
                },
                organization: 1, // 组织
                node: {
                    handlers: [], // 目标用户
                    organizations: [], // 部门
                    titles: [], // 角色
                    attachments: []
                },
                handler: [], // 右侧审批人列表，设置页面同理
                copy: [], // 右侧抄送人列表，设置页面同理
                juese: [], // 右侧框框角色
                zuzhi: [], // 右侧框框组织
                options: [], // 表单
                returnId: '', // flow_id
                titlesList: [], // 侧边栏角色获取
                agree: '',
                agreeList: [{ name: '同一审批人，自动同意', key: 1 }, { name: "不启用自动同意", key: 0 }], // 是否同意
                teacher: '', // 请输入教职工名字
                members: [], // 展示所有选中的老师
                teachers: [], // 所有选中的老师
                approval: '', // 设置审批人--审批人类型
                section: [], // 设置审批人--审批人部门
                show1: false, // 审批人的显示隐藏
                show2: false, // 抄送人的显示隐藏
                prev_node: 0, // 设置审批人时的节点

                lastNewFlow: null,
                schoolId: '',
                flowFormFlag: false,
                nodeFormFlag: false,    // 步骤编辑表单显示
                nodeOptionsFormFlag: false,    // 步骤必填项表单显示
                nodeOption: { // 步骤必填项表单
                    id: null,
                    name: '',
                    type: '',
                    node_id: null,
                },
                nodeOptionTypes: [Constants.NODE_OPTION.TEXT, Constants.NODE_OPTION.DATE, Constants.NODE_OPTION.TIME], // 步骤必填项的数据类型
                showFileManagerFlag: false,
                // node: {
                //     id: null,
                //     name: '', // 步骤名称
                //     description: '', // 创建新流程时, 发起流程的第一步的说明
                //     options: [], // node 流程步骤的必填项集合
                //     handlers: [], // node 流程步骤的处理人
                //     organizations: [], // node 流程步骤针对的部门
                //     attachments: [], // node 流程步骤关联的附件
                //     notice_to: [], // node 流程步骤关联的附件
                //     titles: [], // node 流程步骤针对的部门的角色
                // },
                prevNodeId: null, // 编辑 node 的时候, 前一个步骤的 ID
                organizationsTabArrayWhenEdit: [],
                flowNodes: [],

                props: {
                    lazy: true,
                    value: 'id',
                    multiple: true,
                    label: 'name',
                    lazyLoad(node, resolve) {
                        let parentId = null;
                        if (!Util.isEmpty(node.data)) {
                            parentId = node.data.id;
                        }
                        axios.post(
                            Constants.API.ORGANIZATION.LOAD_CHILDREN,
                            { level: node.level + 1, parent_id: parentId }
                        ).then(res => {
                            if (Util.isAjaxResOk(res)) {
                                resolve(res.data.data.orgs);
                            }
                        });
                    }
                },
                prop: {
                    lazy: true,
                    value: 'id',
                    multiple: false,
                    label: 'name',
                    lazyLoad(node, resolve) {
                        let parentId = null;
                        if (!Util.isEmpty(node.data)) {
                            parentId = node.data.id;
                        }
                        axios.post(
                            Constants.API.ORGANIZATION.LOAD_CHILDREN,
                            { level: node.level + 1, parent_id: parentId }
                        ).then(res => {
                            if (Util.isAjaxResOk(res)) {
                                resolve(res.data.data.orgs);
                            }
                        });
                    }
                },
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.returnId = dom.dataset.newflow;
            this.flow.school_id = dom.dataset.school

            // 可能是刚刚创建了新流程, 检查一下
            // this.lastNewFlow = dom.dataset.newflow;
            // if (!Util.isEmpty(this.lastNewFlow)) {
            //     // 去加载这个流程的所有节点
            //     this.loadFlowNodes(this.lastNewFlow);
            // }
            this.getList(1);
            // 进入设置页面之后获取流程
            if (window.location.search != '') {
                this.returnId = window.location.search.split('=')[1]
                loadNodes(this.returnId).then(res => {
                    this.copy = res.data.data.nodes.copy
                    this.handler = res.data.data.nodes.handler
                    this.agree = res.data.data.flow.auto_processed
                })
            }
        },
        methods: {
            // 获取左边分类和侧边栏显示位置和侧边栏关联业务
            getList(tab) {
                // /school_manager/pipeline/flows/load-flows
                const url = Util.buildUrl(Constants.API.FLOW.GETFLOWS);
                axios.post(url, {
                    position: tab
                }).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        this.typeList = res.data.data // 左边分类
                        this.posiType = tab // 侧边栏显示位置
                        if (this.posiType === 3) {
                            this.getbusinessList(); // 侧边栏关联业务
                        }
                    }
                }).catch((err) => {
                    console.log(err)
                });
            },
            // 新增流程按钮--打开侧边栏
            createNewFlow() {
                this.flowFormFlag = true;
                this.newFlow = true;
                this.flow.type = '';
                this.flow.name = '';
                this.node.handlers = []; // 目标用户
                this.node.organizations = []; // 部门
                this.node.titles = []; // 角色
                this.role = 1;
                if (this.posiType === 2) {
                    this.organization = 2
                }
                this.gettitlesList();
            },
            // 关闭侧边栏
            handleClose(done) {
                done();
            },
            // 获取侧边栏流程图标       
            iconSelectedHandler(payload) {
                this.flow.icon = payload.url;
                this.selectedImgUrl = payload.url;
                this.iconSelectorShowFlag = false;
            },
            // 获取侧边栏关联业务
            getbusinessList() {
                axios.post('/school_manager/pipeline/flows/load-business')
                    .then((res) => {
                        if (Util.isAjaxResOk(res)) {
                            this.businessList = res.data.data
                        }
                    }).catch((err) => {
                        console.log(err)
                    });
            },
            // 获取侧边栏角色列表
            changeItem(name, value) {
                if (name == 'p') {
                    this.posiType = value
                    if (this.posiType == 2) {
                        this.organization = 2
                    }
                }
                this.gettitlesList();
            },
            gettitlesList() {
                axios.post('/school_manager/pipeline/flows/load-titles', {
                    position: this.posiType, // 显示位置
                    type: this.organization, // 组织类型
                    role: this.role // 1.使用者2.审批者
                })
                    .then((res) => {
                        if (Util.isAjaxResOk(res)) {
                            this.titlesList = res.data.data
                        }
                    }).catch((err) => {
                        console.log(err)
                    });
            },
            // 侧边栏创建新流程按钮
            onNewFlowSubmit: function (id) {
                if (this.flow.type === '') {
                    this.$notify.error({
                        title: '错误',
                        message: '流程分类不能为空'
                    });
                    return;
                }
                if (this.flow.name.trim() === '') {
                    this.$notify.error({
                        title: '错误',
                        message: '流程的名称不能为空'
                    });
                    return;
                } else if (this.flow.name.trim().length > 10) {
                    this.$notify.error({
                        title: '错误',
                        message: '流程的名称不可超过10个字符'
                    });
                    return;
                }
                if (this.flow.icon === '') {
                    this.$notify.error({
                        title: '错误',
                        message: '流程图标不能为空'
                    });
                    return;
                }
                if (this.posiType === 3) {
                    if (this.flow.business === '') {
                        this.$notify.error({
                            title: '错误',
                            message: '关联业务不能为空'
                        });
                        return;
                    }
                }
                if (this.node.handlers.length === 0) {
                    this.$notify.error({
                        title: '错误',
                        message: '请选择目标用户'
                    });
                    return;
                }
                if (this.organization === 1) {
                    if (this.node.organizations.length === 0) {
                        this.$notify.error({
                            title: '错误',
                            message: '请选择部门'
                        });
                        return;
                    }
                }
                if (this.node.titles.length === 0) {
                    this.$notify.error({
                        title: '错误',
                        message: '请选择角色类型'
                    });
                    return;
                }
                // 创建新的流程
                if (id === 1) {
                    saveFlow(this.flow, this.node).then(res => {
                        if (Util.isAjaxResOk(res)) {
                            window.location.href = '/school_manager/pipeline/flows/manager?lastNewFlow=' + res.data.data.id;
                        }
                        else {
                            this.$notify.error(
                                { title: '保存失败', message: res.data.message, duration: 0 }
                            );
                        }
                    })
                } else {
                    // 右侧编辑时侧边栏的保存按钮
                    this.editFlow();
                    saveFlow(this.flow, this.node).then(res => {
                        if (Util.isAjaxResOk(res)) {
                            window.location.href = '/school_manager/pipeline/flows/manager?lastNewFlow=' + res.data.data.id;
                        }
                        else {
                            this.$notify.error(
                                { title: '保存失败', message: res.data.message, duration: 0 }
                            );
                        }
                    })
                }
            },
            // 每个流程的点击事件
            loadFlowNodes: function (flowId, flowName) {
                this.returnId = flowId
                this.flow.name = flowName
                this.loadingNodes = true;
                // 获取流程信息
                loadNodes(flowId).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.juese = res.data.data.nodes.head.handler.titles
                        this.zuzhi = res.data.data.nodes.head.handler.organizations
                        this.copy = res.data.data.nodes.copy
                        this.handler = res.data.data.nodes.handler
                        this.options = res.data.data.nodes.options
                    }
                    else {
                        this.$notify.error(
                            { title: '加载失败', message: res.data.message, duration: 0 }
                        );
                    }
                    this.loadingNodes = false;
                })
            },
            // 右侧编辑按钮回显
            editFlow() {
                this.newFlow = false;
                this.role = 1;
                this.flowFormFlag = true;
                loadNodes(this.returnId).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.flow.type = res.data.data.flow.type; // 流程分类
                        this.flow.name = res.data.data.flow.name // 流程名称
                        this.flow.icon = res.data.data.flow.icon
                        this.flow.business = res.data.data.flow.business
                        this.flow.school_id = res.data.data.flow.school_id
                        this.flow.id = this.returnId
                        // 有部门
                        if (res.data.data.nodes.head.handler.organizations.length > 0) {
                            this.organization = 1;
                            this.node.organizations = ''
                            // this.node.organizations = res.data.data.nodes.head.handler.organizations.substring(0, res.data.data.nodes.head.handler.organizations.length - 1).split(';')
                            this.node.titles = res.data.data.nodes.head.handler.titles.substring(0, res.data.data.nodes.head.handler.titles.length - 1).split(';')
                            this.gettitlesList();
                        } else {
                            this.organization = 2;
                            this.node.titles = res.data.data.nodes.head.handler.titles.substring(0, res.data.data.nodes.head.handler.titles.length - 1).split(';')
                            this.gettitlesList();
                        }
                        this.node.handlers = res.data.data.nodes.head.handler.role_slugs.substring(0, res.data.data.nodes.head.handler.role_slugs.length - 1).split(';')
                    } else {
                        this.$notify.error(
                            { title: '加载失败', message: res.data.message, duration: 0 }
                        );
                    }
                })
            },
            // 右侧的删除按钮
            deleteFlow: function () {
                this.$confirm('此操作将彻底删除此流程, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loadingNodes = true;
                    deleteFlow(this.returnId).then(res => {
                        if (Util.isAjaxResOk(res)) {
                            this.$message({ type: 'success', message: '删除成功, 页面将重新加载, 请稍候!' });
                            window.location.reload();
                        } else {
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
            // 自定义表单 -- 进入页面
            option() {
                if (this.options.length) {
                    this.$message('无法修改，可删除重新创建');
                } else {
                    var url = this.$refs.option.$attrs.href + '?flow_id=' + this.returnId;
                    location.href = url;
                }
            },
            // 设置审批人按钮--进入页面
            approver() {
                if (this.handler.length) {
                    this.$message('无法修改，可删除重新创建');
                } else {
                    var url = this.$refs.approver.$attrs.href + '?flow_id=' + this.returnId;
                    location.href = url;
                }
            },
            approvers() {
                if (this.copy.length) {
                    this.$message('无法修改，可删除重新创建');
                } else {
                    var url = this.$refs.approver.$attrs.href + '?flow_id=' + this.returnId;
                    location.href = url;
                }
            },
            // 设置页面---流程开始的加号
            first() {
                this.show1 = !this.show1;
                this.role = 2;
                this.gettitlesList();
            },
            // 新建下一级审批人时获取上一级审批人的id
            prev(id) {
                this.show1 = !this.show1;
                this.prev_node = id;
                this.role = 2;
                this.gettitlesList();
            },
            // 设置审批人确定啊按钮 
            setone() {
                if (this.section == '' && this.organization === 1) {
                    this.$message({
                        message: '请选择部门',
                        type: 'error'
                    });

                } else if (this.approval == '') {
                    this.$message({
                        message: '请选择审批人',
                        type: 'error'
                    });
                } else {
                    this.node.titles.splice(0, 0, this.approval)
                    if (this.section.length !== 0) {
                        this.node.organizations.splice(0, 0, this.section)
                    }
                    const obj = {
                        flow_id: this.returnId,
                        prev_node: this.prev_node,
                        node: {
                            organizations: this.node.organizations, // 组织
                            titles: this.node.titles
                        }
                    }
                    axios.post('/school_manager/pipeline/flows/save-node', obj).then((res) => {
                        if (Util.isAjaxResOk(res)) {
                            this.handler = res.data.data.nodes.handler
                            this.node.organizations = []
                            this.approval = ''
                            this.section = []
                            this.$message({
                                message: '保存成功',
                                type: 'success'
                            });
                        } else {
                            this.$notify.error(
                                { title: '保存失败', message: res.data.message, duration: 0 }
                            );
                        }
                    }).catch((err) => {
                        console.log(err)
                    });
                }
            },
            // 删除某一级审批人
            deleteNode(id) {
                axios.post("/school_manager/pipeline/flows/delete-node", {
                    node_id: id,
                    school_id: this.flow.school_id
                }).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        window.location.reload()
                    }
                }).catch(err => {
                    console.log(err)
                })
            },
            // 保存 --- 是否自动审批
            saveagree() {
                if (this.agree === '') {
                    this.$message({
                        message: '请选择是否自动同意',
                        type: 'error'
                    });
                } else {
                    axios.post('/school_manager/pipeline/flows/save-auto-processed', {
                        flow_id: this.returnId,
                        auto_processed: this.agree
                    }).then((res) => {
                        if (Util.isAjaxResOk(res)) {
                            this.$message({
                                message: '保存成功',
                                type: 'success'
                            });
                        } else {
                            this.$notify.error(
                                { title: '保存失败', message: res.data.message, duration: 0 }
                            );
                        }
                    }).catch((err) => {
                        console.log(err)
                    });
                }
            },
            // 联想teacher
            selectMember: function (payload) {
                this.members.push(payload.item.value); // 存名字
                this.teachers.push(payload.item.id); // 存id
            },
            // 删除某个teacher
            removeFromOrg: function (index) {
                this.members.splice(index, 1);
                this.teachers.splice(index, 1);
            },
            // 保存所有选中的老师
            savecopy() {
                if (this.teachers == '') {
                    this.$message({
                        message: '请选择抄送人',
                        type: 'error'
                    });
                } else {
                    axios.post('/school_manager/pipeline/flows/save-copy', {
                        flow_id: this.returnId,
                        users: this.teachers
                    }).then((res) => {
                        if (Util.isAjaxResOk(res)) {
                            this.copy = res.data.data.copy
                            this.teacher = ''
                            this.members = []
                            this.$message({
                                message: '保存成功',
                                type: 'success'
                            });
                        } else {
                            this.$notify.error(
                                { title: '保存失败', message: res.data.message, duration: 0 }
                            );
                        }
                    }).catch((err) => {
                        console.log(err)
                    });
                }
            },



            // 步骤所需要的必填项的管理
            // editNodeOptions: function (node, theOption) {
            //     this.nodeOptionsFormFlag = true;
            //     this._setEditingNode(node);
            //     if (!Util.isEmpty(theOption)) {
            //         this.editNodeOption(theOption);
            //     }
            // },
            // editNodeOption: function (theOption) {
            //     this.nodeOption.id = theOption.id;
            //     this.nodeOption.name = theOption.name;
            //     this.nodeOption.type = theOption.type;
            //     this.nodeOption.node_id = theOption.node_id;
            // },
            // removeNodeOption: function (option, index) {
            //     const result = confirm('此操作将彻底删除必填项目: ' + option.name + ', 是否继续?');
            //     if (result === true) {
            //         deleteNodeOption(option.id).then(res => {
            //             if (Util.isAjaxResOk(res)) {
            //                 this.node.options.splice(index, 1);
            //                 this.$message({
            //                     type: 'success',
            //                     message: '删除成功'
            //                 });
            //             }
            //             else {
            //                 this.$message.error(res.data.message);
            //             }
            //         })
            //     }
            // },
            // onNodeOptionFormSubmit: function () {
            //     // 准备数据
            //     if (Util.isEmpty(this.nodeOption.name)) {
            //         this.$message.error('选项的名称为必填项');
            //         return;
            //     }
            //     if (Util.isEmpty(this.nodeOption.type)) {
            //         this.$message.error('请选择选项的数据类型');
            //         return;
            //     }
            //     this.nodeOption.node_id = this.node.id;
            //     saveNodeOption(this.nodeOption).then(res => {
            //         if (Util.isAjaxResOk(res)) {
            //             // 保存成功
            //             if (this.nodeOption.id !== res.data.data.id) {
            //                 // 新建 option 的返回结果
            //                 this.node.options.push({
            //                     id: res.data.data.id,
            //                     name: this.nodeOption.name,
            //                     type: this.nodeOption.type,
            //                     node_id: this.nodeOption.node_id
            //                 });
            //             }
            //             else {
            //                 const idx = Util.GetItemIndexById(res.data.data.id, this.node.options);
            //                 this.node.options[idx].name = this.nodeOption.name;
            //                 this.node.options[idx].type = this.nodeOption.type;
            //             }
            //             this.nodeOption.id = null;
            //             this.nodeOption.name = '';
            //             this.nodeOption.type = '';
            //             this.nodeOption.node_id = null;
            //             this.$message({ type: 'success', message: '保存成功' });
            //         }
            //         else {
            //             this.$message.error(res.data.message);
            //         }
            //     });
            // },




            // // 对 node 的操作
            // deleteNode: function (idx, node) {
            //     this.$confirm('此操作将永久删除步骤: "' + node.name + '", 是否继续?', '提示', {
            //         confirmButtonText: '确定',
            //         cancelButtonText: '取消',
            //         type: 'warning'
            //     }).then(() => {
            //         deleteNode(node.id, this.schoolId).then(res => {
            //             if (Util.isAjaxResOk(res)) {
            //                 this.flowNodes.splice(idx, 1);
            //                 this.$message({ type: 'success', message: '删除成功' });
            //             }
            //             else {
            //                 this.$message.error(res.data.message);
            //             }
            //         })
            //     }).catch(() => {
            //         this.$message({
            //             type: 'info',
            //             message: '已取消删除'
            //         });
            //     });
            // },

            // 创建新的步骤
            //     createNewNode: function () {
            //         this.nodeFormFlag = true;
            //         if (this.flowNodes.length > 0) {
            //             // 默认的新的前一个步骤的 id
            //             this.prevNodeId = this.flowNodes[this.flowNodes.length - 1].id;
            //         }
            //         this._resetNodeForm();
            //     },
            //     editNode: function (node) {
            //         this.nodeFormFlag = true;
            //         this._setEditingNode(node);
            //     },
            //     _setEditingNode: function (node) {
            //         this.prevNodeId = node.prev_node;
            //         this.node.id = node.id;
            //         this.node.description = node.description;
            //         this.node.name = node.name;
            //         this.node.handlers = node.handler.role_slugs === '' ? [] : this.splitHelper(node.handler.role_slugs);
            //         this.organizationsTabArrayWhenEdit = node.handler.organizations === '' ? [] : this.splitHelper(node.handler.organizations);
            //         this.node.titles = node.handler.titles === '' ? [] : this.splitHelper(node.handler.titles);
            //         this.node.notice_to = node.handler.notice_to === '' ? [] : this.splitHelper(node.handler.notice_to);
            //         this.node.options = node.options;
            //     },
            //     splitHelper: function (str) {
            //         return str.substring(0, str.length - 1).split(';')
            //     },
            //     // 保存选定的步骤
            //     onNodeFormSubmit: function () {
            //         if (this.node.name.trim() === '') {
            //             this.$notify.error({
            //                 title: '错误',
            //                 message: '步骤的名称必须填写'
            //             });
            //             return;
            //         }
            //         if (this.node.description.trim() === '') {
            //             this.$notify.error({
            //                 title: '错误',
            //                 message: '步骤的说明必须填写'
            //             });
            //             return;
            //         }

            //         if (this.node.notice_to.length === 0) {
            //             this.$confirm('没有指定下一步的负责人, 表示这将是本流程的最后一步, 是否确认?', '提示', {
            //                 confirmButtonText: '确定',
            //                 cancelButtonText: '取消',
            //                 type: 'warning'
            //             }).then(() => {
            //                 this._updateNode();
            //             }).catch(() => {

            //             });
            //         }
            //         else {
            //             this._updateNode();
            //         }
            //     },
            //     _updateNode: function () {
            //         if (Util.isEmpty(this.node.id)) {
            //             // 创建新步骤的操作
            //             if (this.node.organizations.length === 0 && this.node.handlers.length === 0) {
            //                 this.$notify.error({
            //                     title: '错误',
            //                     message: '流程必须选择相关的部门或用户群'
            //                 });
            //                 return;
            //             }

            //             saveNode(this.currentFlow, this.node, this.prevNodeId).then(res => {
            //                 if (Util.isAjaxResOk(res)) {
            //                     this.flowNodes = res.data.data.nodes;
            //                     this.prevNodeId = null;
            //                     this.nodeFormFlag = false;
            //                     this.$message({ type: 'success', message: '步骤保存成功' });
            //                     this._resetNodeForm();
            //                 }
            //                 else {
            //                     this.$notify.error(
            //                         { title: '保存失败', message: res.data.message, duration: 0 }
            //                     );
            //                 }
            //             });
            //         }
            //         else {
            //             // 更新操作
            //             if (
            //                 this.node.organizations.length === 0
            //                 && this.organizationsTabArrayWhenEdit.length === 0
            //                 && this.node.handlers.length === 0
            //             ) {
            //                 this.$notify.error({
            //                     title: '错误',
            //                     message: '流程必须选择相关的部门或用户群'
            //                 });
            //                 return;
            //             }

            //             updateNode(this.currentFlow, this.node, this.prevNodeId, this.organizationsTabArrayWhenEdit)
            //                 .then(res => {
            //                     if (Util.isAjaxResOk(res)) {
            //                         this.flowNodes = res.data.data.nodes;
            //                         this.prevNodeId = null;
            //                         this.organizationsTabArrayWhenEdit = [];
            //                         this.nodeFormFlag = false;
            //                         this.$message({ type: 'success', message: '步骤保存成功' });
            //                         this._resetNodeForm();
            //                     }
            //                     else {
            //                         this.$notify.error(
            //                             { title: '保存失败', message: res.data.message, duration: 0 }
            //                         );
            //                     }
            //                 });
            //         }
            //     },
            //     // 选择步骤的附件所用的监听器
            //     pickFileHandler: function (payload) {
            //         this.showFileManagerFlag = false;
            //         const attachment = {
            //             id: null,
            //             node_id: this.node.id,
            //             media_id: payload.file.id,
            //             url: payload.file.url,
            //             file_name: payload.file.file_name
            //         };
            //         this.node.attachments.push(attachment);
            //     },
            //     dropAttachment: function (idx, attachment, nodeIndex) {
            //         if (!Util.isEmpty(attachment.id)) {
            //             // 从服务器删除
            //             deleteNodeAttachment(attachment.id).then(res => {
            //                 if (Util.isAjaxResOk(res)) {
            //                     if (Util.isEmpty(nodeIndex)) {
            //                         this.node.attachments.splice(idx, 1);
            //                     } else {
            //                         this.flowNodes[nodeIndex].attachments.splice(idx, 1);
            //                     }
            //                 } else {
            //                     this.$message.error('无法删除附件');
            //                 }
            //             })
            //         } else {
            //             this.node.attachments.splice(idx, 1);
            //         }
            //     },


            //     handleOrganizationTagClose: function (idx) {
            //         this.$confirm('此操作将从本步骤中删除此部门, 是否继续?', '提示', {
            //             confirmButtonText: '确定',
            //             cancelButtonText: '取消',
            //             type: 'warning'
            //         }).then(() => {
            //             this.organizationsTabArrayWhenEdit.splice(idx, 1);
            //         }).catch(() => {
            //             this.$message({
            //                 type: 'info',
            //                 message: '已取消删除'
            //             });
            //         });
            //     }
        }
    });
}