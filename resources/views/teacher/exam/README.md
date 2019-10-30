## 考试管理
1.创建考试
::: tip
1.输入考试名称  例：2019年秋期末考试

2.选择课程 接口地址：teacher.exam.getCourses

3.输入学期  例：1 

4:选择考试形式 

5:考试类型  
      
    1:期中 2:期末 3:随堂  4:补考 5:缓考 6:清考 
    根据考试类型切换考试时间的选择
    随堂考根据周几的第几节课考试，其余根据时间选择
    需要接口提供



:::
考试时间  开始时间   结束时间 /  第几周哪几节课

2. 选择考试群体 分配教室 选择老师  
:::tip
选择考试的群体 

    根据学院、系 、专业、班划分 如果是全院考试，  
    则选择到学院就不再往下选，以此论推

分配教室 
 
    根据所选择的院系专业查找当前考试时间可以使用的教室
    
分配监考老师

    监考老师时间不能冲突
    

:::


### 2.获取课程接口  teacher/exam/getCourses

#### 响应数据: json 格式
``` json
{
	"code": 1000,
	"message": "请求成功",
	"data": [{
		"code": "1",        // 课程编号
		"name": "语文",      // 课程名称
		"uuid": "1",        // 课程的UUid 
		"id": 2,
		"scores": 1,        // 学分
		"optional": true,
		"year": 1,          // 年级
		"term": 1,          // 学期
		"desc": "语文课",    // 描述
		"teachers": [],
		"majors": []
	}]
}
```


### 3.获取教室接口 teacher.exam.getClassRooms

#### 响应数据: json 格式
```` json
{
	"code": 1000,
	"message": "请求成功",
	"data": [{
		"id": 1,
		"school_id": 50,      // 学校ID
		"campus_id": 1,       // 校区ID
		"building_id": 1,     // 建筑ID
		"name": "101教室",     // 教室名称
		"type": 1,            // 类型 1:教室 2智慧教室 3会议室 4办公室 5学生宿舍
		"seats": 1,           // 容纳人数
		"description": null,  // 描述
		"deleted_at": null
	}]
}
````

### 4.创建考试接口

#### 1: 请求数据
| 参数名       | 是否必须     | 参数类型  | 说明       |
| --------    |:----------:| -----:   | -----:    |
| name        | Yes        | string   | 考试名称|
| course_id   | Yes        | string   | 课程ID|
| semester    | Yes        | string   | 学期|
| formalism   | Yes        | string   | 考试形式|
| type        | Yes        | string   | 考试类型|
| room_id     | Yes        | array    | 房间ID|
| from        | Yes        | date     | 开始时间|
| to          | Yes        | date     | 结束时间|

#### 2.响应数据: json 格式
``` json
{
	"code": 1000,
	"message": "添加成功",
	"data": []
}
```

### 5.考试列表

``` json
{
	"code": 1000,
	"message": "请求成功",
	"data": [{
		"id": 17,
		"name": "2019年秋期末考试",   // 考试名称
		"school_id": 50,            // 学校ID
		"course_id": 2,             // 课程ID
		"semester": 1,              // 学期
		"formalism": 1,             // 考试形式
		"type": 1,                  // 考试类别
		"year": 2019,               // 年
		"month": 10,                // 月
		"week": 44,                 // 周
		"day": 29,                  // 日
		"exam_time": "2019-10-29",  // 考试时间
		"from": "10:00:00",         // 开始时间
		"to": "12:00:00",           // 结束时间
		"status": 1,                // 状态 
		"created_at": "2019-10-29 15:23:17",
		"updated_at": "2019-10-29 15:23:17",
		"type_text": "期中考试",      // 考试类型text
		"formalism_text": "笔试",    // 考试形式text
		"status_text": "已考试",     // 状态text
		"course": {
			"id": 2,
			"school_id": 50,
			"uuid": "1",
			"code": "1",
			"name": "语文",         //课程名称
			"scores": 1,
			"optional": true,
			"year": 1,
			"term": 1,
			"desc": "语文课",
			"created_at": "2019-10-29 09:51:57",
			"updated_at": null,
			"deleted_at": null
		},
		"exams_room": [{
			"id": 1,
			"exam_id": 17,
			"room_id": 1,
			"exam_time": "2019-10-29",
			"from": "10:00:00",
			"to": "12:00:00",
			"created_at": "2019-10-29 15:23:17",
			"updated_at": "2019-10-29 15:23:17",
			"room": {
				"id": 1,
				"school_id": 50,
				"campus_id": 1,
				"building_id": 1,
				"name": "101教室",     //考场名称
				"type": 1,
				"seats": 1,
				"description": null,
				"deleted_at": null
			}
		}]
	}, {
		"id": 18,
		"name": "2019年秋期末考试",
		"school_id": 50,
		"course_id": 2,
		"semester": 1,
		"formalism": 1,
		"type": 1,
		"year": 2019,
		"month": 10,
		"week": 44,
		"day": 29,
		"exam_time": "2019-10-29",
		"from": "14:00:00",
		"to": "16:00:00",
		"status": 2,
		"created_at": "2019-10-29 15:25:52",
		"updated_at": "2019-10-29 15:25:52",
		"type_text": "期中考试",
		"formalism_text": "笔试",
		"status_text": "正在考试",
		"course": {
			"id": 2,
			"school_id": 50,
			"uuid": "1",
			"code": "1",
			"name": "语文",
			"scores": 1,
			"optional": true,
			"year": 1,
			"term": 1,
			"desc": "语文课",
			"created_at": "2019-10-29 09:51:57",
			"updated_at": null,
			"deleted_at": null
		},
		"exams_room": [{
			"id": 4,
			"exam_id": 18,
			"room_id": 1,
			"exam_time": "2019-10-29",
			"from": "14:00:00",
			"to": "16:00:00",
			"created_at": "2019-10-29 15:25:52",
			"updated_at": "2019-10-29 15:25:52",
			"room": {
				"id": 1,
				"school_id": 50,
				"campus_id": 1,
				"building_id": 1,
				"name": "101教室",
				"type": 1,
				"seats": 1,
				"description": null,
				"deleted_at": null
			}
		}]
	}, {
		"id": 19,
		"name": "2019年秋期末考试",
		"school_id": 50,
		"course_id": 1,
		"semester": 1,
		"formalism": 1,
		"type": 1,
		"year": 2019,
		"month": 10,
		"week": 44,
		"day": 30,
		"exam_time": "2019-10-30",
		"from": "14:00:00",
		"to": "16:00:00",
		"status": 0,
		"created_at": "2019-10-29 15:45:29",
		"updated_at": "2019-10-29 15:45:29",
		"type_text": "期中考试",
		"formalism_text": "笔试",
		"status_text": "未考试",
		"course": null,
		"exams_room": [{
			"id": 7,
			"exam_id": 19,
			"room_id": 1,
			"exam_time": "2019-10-30",
			"from": "14:00:00",
			"to": "16:00:00",
			"created_at": "2019-10-29 15:45:29",
			"updated_at": "2019-10-29 15:45:29",
			"room": {
				"id": 1,
				"school_id": 50,
				"campus_id": 1,
				"building_id": 1,
				"name": "101教室",
				"type": 1,
				"seats": 1,
				"description": null,
				"deleted_at": null
			}
		}]
	}]
}
```


