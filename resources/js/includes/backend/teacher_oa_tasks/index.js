import './style/common.scss'
import './style/index.scss'
import { TaskMode } from './common/enum'
import TaskList from './components/task'
import TaskForm from './components/task-form'

const APPID = 'teacher-oa-tasks-app'
let app = document.getElementById(APPID)
if (app) {
  var inited = {}
  new Vue({
    el: '#' + APPID,
    components: {
      TaskList, TaskForm
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
      activeNameText () {
        return (TaskMode[this.activeName] || {}).text || ''
      }
    },
    methods: {
      onTaskCreated () {
        this.refreshList(this.activeName)
        this.addDrawer = false
      },
      refreshList (val) {
        if (this.$refs[val]) {
          this.$refs[val][0].getTaskList()
        }
      }
    },
    data () {
      return {
        taskTypes: ((types) => {
          return Object.keys(types).map(typeKey => {
            return types[typeKey]
          })
        })(TaskMode),
        activeName: '',
        addDrawer: false
      }
    },
    created () {
      this.activeName = 'pending'
    }
  })
}