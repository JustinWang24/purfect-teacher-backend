import {Constants} from "./constants";
import {Util} from "./utils";

/**
 * 保存流程
 * @param flow
 * @param node
 * @param affix
 * @returns Promise
 */
export function saveFlow(flow, node, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.SAVE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {flow: flow, node: node, version:Constants.VERSION}
    );
}

/**
 * 保存新步骤
 * @param flow
 * @param node
 * @param prevNodeId
 * @param affix
 * @returns {*}
 */
export function saveNode(flow, node, prevNodeId, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.SAVE_NODE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {flow_id: flow.id, node: node, prev_node: prevNodeId, version:Constants.VERSION}
    );
}

/**
 * 保存步骤的选项
 * @param nodeOption
 * @param affix
 * @returns {*}
 */
export function saveNodeOption(nodeOption, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.SAVE_NODE_OPTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {node_option: nodeOption, version:Constants.VERSION}
    );
}

/**
 * 删除步骤的选项
 * @param nodeOptionId
 * @param affix
 * @returns {*}
 */
export function deleteNodeOption(nodeOptionId, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.DELETE_NODE_OPTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {node_option_id: nodeOptionId, version:Constants.VERSION}
    );
}

export function start(action, isAppRequest, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.START);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {action: action, is_app: isAppRequest, version:Constants.VERSION}
    );
}

export function startedByMe(userUuid, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.START_BY_ME);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user_uuid: userUuid, version:Constants.VERSION}
    );
}

export function waitingForMe(userUuid, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.WAITING_FOR_ME);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user_uuid: userUuid, version:Constants.VERSION}
    );
}

/**
 * 查看流程详情
 * @param actionId
 * @param userFlowId
 * @param affix
 * @returns {*}
 */
export function viewApplicationByAction(actionId, userFlowId, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.VIEW_ACTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {action_id: actionId, user_flow_id: userFlowId, version:Constants.VERSION}
    );
}

/**
 * @param flow
 * @param node
 * @param prevNodeId
 * @param prevOrganizations: 编辑之前的步骤遗留的部门
 * @param affix
 * @returns {*}
 */
export function updateNode(flow, node, prevNodeId, prevOrganizations, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.UPDATE_NODE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {flow_id: flow.id, node: node, prev_node: prevNodeId, prev_orgs: prevOrganizations, version:Constants.VERSION}
    );
}

export function loadNodes(flowId,affix) {
    const url = Util.buildUrl(Constants.API.FLOW.LOAD_FLOW_NODES);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {flow_id: flowId, version:Constants.VERSION}
    );
}

/**
 * 删除流程
 * @param flowId
 * @param schoolId
 * @param affix
 * @returns {*}
 */
export function deleteFlow(flowId, schoolId,affix) {
    const url = Util.buildUrl(Constants.API.FLOW.DELETE_FLOW);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {flow_id: flowId, school_id: schoolId, version:Constants.VERSION}
    );
}

export function deleteNode(nodeId, schoolId,affix) {
    const url = Util.buildUrl(Constants.API.FLOW.DELETE_NODE);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {node_id: nodeId, school_id: schoolId, version:Constants.VERSION}
    );
}

export function deleteNodeAttachment(attachmentId,affix) {
    const url = Util.buildUrl(Constants.API.FLOW.DELETE_NODE_ATTACHMENT);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {attachment_id: attachmentId, version:Constants.VERSION}
    );
}

// 取消已经发起的流程
export function cancelApplicationByUser(userFlowId,affix) {
    const url = Util.buildUrl(Constants.API.FLOW.CANCEL_ACTION);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {user_flow_id: userFlowId, version:Constants.VERSION}
    );
}

/**
 * 处理某个步骤
 * @param action
 * @param affix
 * @returns {*}
 */
export function processAction(action, affix) {
    const url = Util.buildUrl(Constants.API.FLOW.PROCESS);
    if(Util.isDevEnv()){
        return axios.get(url, affix);
    }
    return axios.post(
        url,
        {action: action, version:Constants.VERSION}
    );
}