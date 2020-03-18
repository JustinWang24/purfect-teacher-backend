import { Mixins } from "../Mixins";
Vue.component("WeekTimeTable", {
  template: `
    <div class="week-time-table">
      <div v-for="(pItem,pIndex) in tableData">
        <div class="table-list">
          <div v-for="(item,index) in pItem">
            {{item.time_slot_name}}  {{index}}
          </div>
        </div>
      </div>
    </div>
  `,
  mixins: [Mixins],
  methods: {},
  data() {
    return {
      minHeight: window.innerHeight - 299,
      tableData: {
        0: [
          {
            time_table_id: 314, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 314, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 314, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 314, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第一节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
        ],
        1: [
          {
            time_table_id: 314, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
          {
            time_table_id: 315, // 课程表ID
            time_slot_id: 6, // 课节ID
            grade_name: "17电子班", // 班级名称
            idx: "", // 课程第几次
            room: "101", // 地点
            course: "语文", // 课程
            time_slot_name: "第二节课", // 课节
            from: "08:00:00", // 开始时间
            to: "08:45:00", // 结束时间
            label: [
              // 标签
              "课中课件",
              "课前预习"
            ]
          },
        ]
      }
    };
  }
});
