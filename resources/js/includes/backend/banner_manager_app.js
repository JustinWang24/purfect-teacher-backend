/**
 * Banner 管理
 */
import {Util} from "../../common/utils";

if(document.getElementById('banner-manager-app')){
    new Vue({
        el:'#banner-manager-app',
        data(){
            return {
                banner:{
                    id:'',
                    schoolId:'',
                    title:'',
                    posit:0,
                    type:0,
                    content:'',
                    external:'', // 跳转到 url
                    image_url:'',
                    sort:1,
                    public:true,
                    status:false,
                },
                positions:[],
                types:[],
                showFileManagerFlag: false,
                isLoading: false,
            }
        },
        computed: {
            'isUrlOnly': function(){
                return this.banner.type === 2;
            },
            'isPictureAndText': function(){
                return this.banner.type === 1;
            },
            'isStatic': function(){
                return this.banner.type === 0;
            },
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.banner.schoolId = dom.dataset.school;
            this.positions = JSON.parse(dom.dataset.positions);
            this.types = JSON.parse(dom.dataset.types);
        },
        methods: {
            loadBanner: function(id){
                this.isLoading = true;
                axios.post(
                    '/school_manager/banner/load',
                    {id: id}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.banner = res.data.data.banner;
                    }
                    else{
                        this.$message.error(res.data.message);
                    }
                    this.isLoading = false;
                })
            },
            onSubmit: function(){
                this.isLoading = true;
                axios.post(
                    '/school_manager/banner/save',
                    {banner: this.banner}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        window.location.reload();
                    }
                    else{
                        this.$message.error(res.data.message);
                    }
                    this.isLoading = false;
                })
            },
            pickFileHandler: function(payload){
                this.showFileManagerFlag = false;
                this.banner.image_url = payload.file.url;
            },
            newBanner: function(){
                this.banner.id = '';
                this.banner.title = '';
                this.banner.posit = 0;
                this.banner.type = 0;
                this.banner.content = '';
                this.banner.external = '';
                this.banner.public = true;
                this.banner.status = false;
                this.banner.image_url = '';
                this.banner.sort = 1;
            },
            deleteBanner: function(id){
                this.$confirm('此操作将永久删除该资源, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    window.location.href = '/school_manager/banner/delete?id=' + id;
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            }
        }
    })
}