<template>
    <div class="books-list-wrap">
        <el-row>
            <el-col :span="5" v-for="(book, idx) in books" :key="idx">
                <el-card shadow="hover" class="book-card">
                    <div class="card-image">
                        <figure class="image">
                            <img src="/assets/img/mega-img1.jpg" class="book-image">
                        </figure>
                    </div>
                    <p class="title">
                        <el-button type="text">教材名: {{ book.name }}(第{{ book.edition }}版)</el-button>
                    </p>
                    <p class="author">作者: {{ book.author }}</p>
                    <p class="press">{{ book.press }}</p>
                    <p class="price">
                        价格: ¥{{ book.price }}元
                        <span class="internal" v-if="asAdmin">{{ book.purchase_price }}</span>
                    </p>
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
            handleViewClick: function(idx, course){
                // 查看必修课的课程安排, 根据指定的课程 ID
                window.open(Constants.API.TIMETABLE.VIEW_TIMETABLE_FOR_COURSE + '?uuid=' + course.uuid, '_blank');
            },
            yearText: function(year){
                return Constants.YEARS[year];
            },
            termText: function (term) {
                return Constants.TERMS[term];
            },
            courseNameClickedHandler: function(idx, row){
                this.$emit('course-view', {idx: idx, course: row});
            }
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