# 流程相关接口

## 1: 打开一个流程 open

用户在使用一个流程之前, 需要先打开流程, 填写启动流程所需要的信息, 然后提交后才能启动流程.

- 调用 URL: /api/pipeline/flow/open
- 请求参数:

   | 参数名       | 是否必须          | 参数类型  | 说明  |
   | ------------- |:-------------:| -----:| -----:|
   | version     | Yes | String | 表示当前呼叫方的版本号 |
   | flow      | Yes      |   String | 要打开的流程 ID |
   | user | Optional     |    String | 当前用户的 UUID 或 API_TOKEN |
   
- 请求方法: POST
- 响应数据: json 格式

```json
{
  "code": 1000,
  "message": "",
  "data": {
      "node": {
          "id": 1,
          "flow_id": 10,
          "prev_node": 0,
          "next_node": 3,
          "type": 1,
          "dynamic": 1,
          "thresh_hold": 1,
          "name": "步骤名称",
          "description": "步骤详细说明",
          "attachments": [
              {
                  "id":111,
                  "file_name": "附件1文件名",
                  "url": "附件1链接地址"
              },{
                  "id":222,
                  "file_name": "附件2文件名",
                  "url": "附件2链接地址"
              }
          ]
      }
  }
}
```

## 2: 启动一个流程 start

用户在打开流程之后, 如果填写了表单, 可以调用这个接口来启动流程.

- 调用 URL: /api/pipeline/flow/start
- 请求参数:

   | 参数名       | 是否必须          | 参数类型  | 说明  |
   | ------------- |:-------------:| -----:| -----:|
   | version     | Yes | String | 表示当前呼叫方的版本号 |
   | action      | Yes      |   Object | 启动流程做需要的数据 |
   | action.flow_id      | Yes      |   String/Number | 被启动的流程的 ID |
   | action.content      | Yes      |   Object | 被启动的流程的启动步骤所要求的文字说明 |
   | action.attachments      | Optional      |   Array | 启动时提交的文件附件集合/数组, 每个元素为文件的 ID |
   | user | Optional     |    String | 当前用户的 UUID 或 API_TOKEN |
   
- 请求方法: POST
- 响应数据: json 格式

```json
{
    "code": 1000,
    "message": "",
    "data": {
        "id": 666 // 新创建的action 的 ID
    }
}
```
