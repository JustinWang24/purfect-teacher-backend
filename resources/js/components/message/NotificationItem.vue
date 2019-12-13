<template>
    <li class="noti-item-wrap">
        <a :href="href">
            <span class="time">{{ timeText }}</span>
            <span class="details">
				<span class="notification-icon circle deepPink-bgcolor">
                    <i class="fa fa-comments-o"></i>
                </span>
                {{ message.content }}
            </span>
        </a>
    </li>
</template>

<script>
    import {Util} from "../../common/utils";

    export default {
        name: "NotificationItem",
        props:{
            message:{
                type: Object,
                required: true
            }
        },
        computed:{
            'href': function () {
                return this.message.next_move === '' ? '#' : this.message.next_move;
            },
            'timeText': function(){
                // 如果创建时间小于 2 分钟, 那么就返回刚刚
                const diff = Util.diffInSecond(this.message.created_at);
                if(diff <= 1){
                    return '刚刚';
                }
                else if(diff > 1 && diff < 24){
                    return '今天';
                }
                else if(diff > 24){
                    return Math.ceil(diff/24) + '天前';
                }
            }
        }
    }
</script>

<style scoped lang="scss">
.noti-item-wrap{

}
</style>