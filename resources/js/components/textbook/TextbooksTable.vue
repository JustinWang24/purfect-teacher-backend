<template>
    <div class="books-list-wrap">
        <el-row>
            <el-col :span="5" v-for="(book, idx) in books" :key="idx">
                <el-card shadow="hover" class="book-card">
                    <div class="card-image">
                        <figure class="image">
                            <img :src="avatarUrl(book)" class="book-image">
                        </figure>
                    </div>
                    <p class="title">
                        <el-button type="text">教材名: {{ book.name }}({{ book.edition }})</el-button>
                    </p>
                    <p class="author">作者: {{ book.author }}</p>
                    <p class="press">{{ book.press }}</p>
                    <p class="price">
                        价格: ¥{{ book.price }}元
                        <span class="internal" v-if="asAdmin">{{ book.purchase_price }}</span>
                    </p>
                    <el-divider style="margin: 10px 0;"></el-divider>
                    <div>
                        <el-button type="text" class="button" v-on:click="connectCoursesHandler(book)">关联/管理课程</el-button>
                        <el-button type="text" class="button" v-on:click="editBookHandler(book)">编辑教材</el-button>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    import TextBadge from '../../components/misc/TextBadge';

    export default {
        name: "TextbooksTable",
        components: {
            TextBadge
        },
        props: {
            books: { // 书
                type: Array, required: true
            },
            asAdmin: {
                type: Boolean, required: true
            }
        },
        data(){
            return {
                highlightIdx: -1,
            };
        },
        methods: {
            bookItemClicked: function(idx, row){
                this.highlightIdx = idx;
                this.$emit('book-item-clicked', {idx: idx, course: row});
            },
            editBookHandler: function(book){
                this.$emit('book-edit', {book: book});
            },
            // 去关联课程
            connectCoursesHandler: function(book){
                this.$emit('connect-courses', {book: book});
            },
            avatarUrl: function(book){
                if(book.medias.length === 0){
                    return '/assets/img/mega-img1.jpg';
                }else{
                    return book.medias[0].url;
                }
            },

        }
    }
</script>

<style scoped lang="scss">
    $colorGrey: #c9cacc;
    .books-list-wrap{
        padding: 0;
        .book-card{
            margin: 4px;
            .card-image{
                .image{
                    .book-image{
                        width: 100%;
                        height: 120px;
                        border-radius: 10px;
                    }
                }
            }
            p{
                margin-bottom: 4px;
            }
            .title{
                font-size: 14px;
                line-height: 20px;
                font-weight: bold;
            }
            .author, .press{
                font-size: 12px;
                color: $colorGrey;
            }
            .price{
                font-size: 13px;
                font-weight: bold;
                color: #f56c6c;
            }
        }
    }
</style>