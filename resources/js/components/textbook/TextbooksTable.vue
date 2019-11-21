<template>
    <div class="books-list-wrap">
        <el-row>
            <el-col :span="8" v-for="(book, idx) in books" :key="idx">
                <el-card shadow="hover" class="book-card">
                    <div class="the-book-wrap">
                        <div class="book-image">
                            <figure class="image">
                                <img :src="avatarUrl(book)" class="book-image">
                            </figure>
                        </div>
                        <div class="book-desc">
                            <p class="title">
                                <el-button type="text">教材名: {{ book.name }}({{ book.edition }})</el-button>
                            </p>
                            <p class="author">作者: {{ book.author }}</p>
                            <p class="press">出版: {{ book.press }}</p>
                            <p class="price">
                                价格: ¥{{ book.price }}元
                                <span class="internal" v-if="asAdmin">{{ book.purchase_price }}</span>
                            </p>
                            <p class="press">
                                课程: &nbsp;
                                <el-tag size="mini" v-for="(c, idx) in book.courses" :key="idx">
                                    {{ getCourseNameText(c.course_id) }}
                                </el-tag>
                            </p>
                        </div>
                    </div>
                    <el-divider style="margin: 0;"></el-divider>
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
    import { Util } from "../../common/utils";

    export default {
        name: "TextbooksTable",
        props: {
            books: { // 书
                type: Array, required: true
            },
            asAdmin: {
                type: Boolean, required: true
            },
            courses: {
                type: Array, required: true
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
            getCourseNameText: function(courseId){
                const c = Util.GetItemById(courseId, this.courses);
                return Util.isEmpty(c) ? '' : c.name;
            },
        }
    }
</script>

<style scoped lang="scss">
    $colorGrey: #c9cacc;
    .books-list-wrap{
        padding: 0;
        .book-card{
            margin: 10px;
            .the-book-wrap{
                display: flex;
                .book-image{
                    .image{
                        .book-image{
                            margin: 0 auto;
                            max-width: 190px;
                            padding-right: 10px;
                            height: 120px;
                            border-radius: 10px;
                        }
                    }
                }
                .book-desc{
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
        }
    }
</style>