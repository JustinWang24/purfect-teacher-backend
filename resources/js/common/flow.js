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