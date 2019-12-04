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