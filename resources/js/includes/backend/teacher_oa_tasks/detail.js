import './style/common.scss'
import TaskDetail from './components/task-detail'

const APPID = 'teacher-oa-task-detail-app'
let app = document.getElementById(APPID)
if (app) {
  var inited = {}
  new Vue({
    el: '#' + APPID,
    components: {
      TaskDetail
    }
  })
}