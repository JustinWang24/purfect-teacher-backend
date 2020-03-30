import './style/common.scss'
import './style/index.scss'
import {
  TaskMode
} from './common/enum'
import TaskList from './components/task'
import TaskForm from './components/task-form'
import {
  TaskApi
} from './common/api'

const APPID = 'teacher-oa-tasks-app'
let app = document.getElementById(APPID)
if (app) {
  var inited = {}
  new Vue({
    el: '#' + APPID,
    components: {
      TaskList,
      TaskForm
    },
    watch: {
      'activeName': function (val) {
        if (!inited[val] && this.$refs[val]) {
          this.$refs[val][0].getTaskList()
          inited[val] = true
        }
      }
    },
    computed: {
      activeNameText() {
        return (TaskMode[this.activeName] || {}).text || ''
      }
    },
    methods: {
      onTaskCreated() {
        this.refreshList(this.activeName)
        this.$refs.addTaskDrawer.closeDrawer()
        this.addDrawer = false
      },
      refreshList(val) {
        if (this.$refs[val]) {
          this.$refs[val][0].getTaskList()
        }
      },
      checkClose(close) {
        if (!this.$refs.addTaskDrawer.$children[0].selectMb) {
          close()
        } else {
          this.$refs.addTaskDrawer.$children[0].selectMb = false
        }
      }
    },
    data() {
      return {
        taskTypes: ((types) => {
          return Object.keys(types).map(typeKey => {
            return types[typeKey]
          })
        })(TaskMode),
        activeName: '',
        addDrawer: false,
        currentUserId: {}
      }
    },
    created() {
      this.activeName = 'pending'
      TaskApi.excute("getTeacherInfo").then(res => {
        this.currentUserId = res.data.data.user_id;
      });
    }
  })
}
