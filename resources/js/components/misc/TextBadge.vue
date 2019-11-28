<template>
    <div class="custom-badge" :class="customCss">
        {{ text }}
    </div>
</template>

<script>
    export default {
        name: "TextBadge",
        props:{
            color: {
                type: String,
                required: false,
                default: 'white'
            },
            text: {
                type: String,
                required: true,
            },
            circle:{
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            'customCss': function(){
                let css = '';
                if(this.color === 'primary'){
                    css =  'bg-primary';
                }else if(this.color === 'danger'){
                    css =  'bg-danger';
                }else if(this.color === 'success'){
                    css =  'bg-success';
                }else if(this.color === 'info'){
                    css =  'bg-info';
                }else{
                    css =  'bg-default';
                }

                if(this.circle){
                    css += ' as-circled'
                }

                return css;
            }
        }
    }
</script>

<style scoped lang="scss">
    .custom-badge{
        display: inline-block;
        color: #0c0c0c;
        text-align: center;
        font-size: 13px;
        height: 24px;
        border-radius: 5px;
        border: solid 1px white;
        box-sizing: border-box;
        padding-top: 1px;
        padding-left:5px;
        padding-right:5px;
    }
    .bg-default{
        background-color: white;
        border: solid 1px #888888;
    }
    .bg-primary{
        background-color: #409EFF;
        color: white;
    }
    .bg-success{
        background-color: #67C23A;
        color: white;
    }
    .bg-danger{
        background-color: #F56C6C;
        color: white;
    }
    .bg-info{
        background-color: #E6A23C;
        color: white;
    }
    .as-circled{
        width: 24px;
        padding-left:0;
        padding-right:0;
        border-radius: 50%;
    }
</style>