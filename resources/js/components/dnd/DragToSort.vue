<template>
    <div>
        <div  class="drag-wrapper" v-drag-and-drop:options="options">
            <ul>
                <li>Item 4</li>
                <li>Item 5</li>
                <li>Item 6</li>
            </ul>
            <ul>
                <li>Item 4</li>
                <li>Item 5</li>
                <li>Item 6</li>
            </ul>
        </div>
    </div>
</template>

<script>
    import VueDraggable from 'vue-draggable';
    Vue.use(VueDraggable);

    export default {
        name: "DragToSort",
        props:{
            items:{
                type: Array,
                required: true
            },
            dndOptions:{
                type: Object,
                required: false,
                default: function(){
                    return {}
                }
            }
        },
        data(){
            return {
                options:{
                    dropzoneSelector: 'ul',
                    draggableSelector: 'li',
                    excludeOlderBrowsers: true,
                    multipleDropzonesItemsDraggingEnabled: true,
                    showDropzoneAreas: true,
                    onDrop(event) {},
                    onDragstart(event) {},
                    onDragend(event) {},
                },
                currentSelectedItem: null, // 当前被选择的拖拽的元素
                elements:[
                    {id:1, content: '第一个'},
                    {id:2, content: '第二个'},
                    {id:3, content: '第三个'},
                ]
            }
        },
        created(){
            // 把传入的选项覆盖默认的选项
            // const keys = Object.keys(this.dndOptions);
            // keys.forEach(key => {
            //     this.options[key] = this.dndOptions[key];
            // })
        },
        methods:{
            /**
             *
             * @param event :native js event
             * @param items : list of selected draggable elements
             * @param owner : old dropzone element
             * @param dropTarget : new dropzone element
             */
            onDrop: function(event){
                console.log(event);
            },
            /**
             *
             * @param event :native js event
             * @param items : list of selected draggable elements
             * @param owner : old dropzone element
             * @param dropTarget : new dropzone element
             * @param stop : stop D&D
             */
            onDragStart: function(event){
                console.log(event);
            },
            /**
             *
             * @param event :native js event
             * @param items : list of selected draggable elements
             * @param owner : old dropzone element
             * @param dropTarget : new dropzone element
             * @param stop : stop D&D
             */
            onDragEnd: function(event){
                console.log(event);
            }
        }
    }
</script>

<style scoped lang="scss">
    .drag-wrapper {
        display: flex;
        justify-content: center;
    }

    ul {
        display: flex;
        flex-direction: column;
        padding: 3px !important;
        min-height: 70vh;
        width: 100px;
        float:left;
        list-style-type:none;
        overflow-y:auto;
        border:2px solid #888;
        border-radius:0.2em;
        background:#8adccc;
        color:#555;
        margin-right: 5px;
    }

    /* drop target state */
    ul[aria-dropeffect="move"] {
        border-color:#68b;
        background:#fff;
    }

    /* drop target focus and dragover state */
    ul[aria-dropeffect="move"]:focus,
    ul[aria-dropeffect="move"].dragover
    {
        outline:none;
        box-shadow:0 0 0 1px #fff, 0 0 0 3px #68b;
    }

    /* draggable items */
    li {
        display:block;
        list-style-type:none;
        margin:0 0 2px 0;
        padding:0.2em 0.4em;
        border-radius:0.2em;
        line-height:1.3;
    }

    li:hover {
        box-shadow:0 0 0 2px #68b, inset 0 0 0 1px #ddd;
    }

    /* items focus state */
    li:focus
    {
        outline:none;
        box-shadow:0 0 0 2px #68b, inset 0 0 0 1px #ddd;
    }

    /* items grabbed state */
    li[aria-grabbed="true"]
    {
        background:#5cc1a6;
        color:#fff;
    }

    @keyframes nodeInserted {
        from { opacity: 0.2; }
        to { opacity: 0.8; }
    }

    .item-dropzone-area {
        height: 2rem;
        background: #888;
        opacity: 0.8;
        animation-duration: 0.5s;
        animation-name: nodeInserted;
    }
</style>