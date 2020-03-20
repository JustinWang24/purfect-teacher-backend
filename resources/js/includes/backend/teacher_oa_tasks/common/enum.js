export const TaskMode = {
  pending: {
    status: 'pending',
    text: '待处理的',
    value: '1'
  },
  received: {
    status: 'received',
    text: '已接收的',
    value: '2'
  },
  done: {
    status: 'done',
    text: '已完成的',
    value: '3'
  },
  mystart: {
    status: 'mystart',
    text: '我发起的',
    value: '4'
  }
}
//任务状态 0-待接收 1-进行中按时 2-已完成按时 3-进行中超时 4-已完成超时
export const TaskStatus = {
  0: {
    text: '待接收',
    classes: 'waiting'
  },
  1: {
    text: '进行中',
    classes: 'pending'
  },
  2: {
    text: '已完成',
    classes: 'done'
  },
  3: {
    text: '超时',
    classes: 'timeout'
  },
  4: {
    text: '超时完成',
    classes: 'timeout'
  }
}

export const TaskFinishStatus = {
  1: {
    text: '已开始'
  },
  2: {
    text: '进行中',
  },
  3: {
    text: '已完成'
  }
}
