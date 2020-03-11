import { Util } from "../../../../common/utils";
let fetch = {};
fetch.get = url => {
    return new Promise((resolve, reject) => {
        axios.get(url).then(res => {
            if (Util.isAjaxResOk(res)) {
                resolve(res);
            } else {
                reject(false);
            }
        }).catch((err) => {
            reject(false)
        })
    });
};
fetch.post = (url, data) => {
    return new Promise((resolve, reject) => {
        axios.post(url, data).then(res => {
            if (Util.isAjaxResOk(res)) {
                resolve(res.data.data);
            } else {
                reject(false);
            }
        }).catch((err) => {
            reject(false);
        })
    });
};
export default fetch;
