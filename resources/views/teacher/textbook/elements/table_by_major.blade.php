<?php
    $subTotal = 0;
    $totalCost = 0;
?>
<table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
    <tr>
        <th>课程名称</th>
        <th>年级</th>
        <th>学期</th>
        <th>教材名称</th>
        <th>作者</th>
        <th>版本</th>
        <th>出版社</th>
        <th>采购单价</th>
        <th>零售单价</th>
        <th>计划学生数</th>
        <th>实际学生数</th>
    </tr>
    @foreach($major_textbook as $course)
        <?php
            $subTotal = 0;
        ?>
        @foreach($course['course_textbooks'] as $key=>$book)
            <tr>
                <td>{{ $course['name'] }}</td>
                <td>{{ $course['year'] }}年级</td>
                <td>{{ $course['term'] }}学期</td>
                <td>{{ $book['textbook']['name'] }}</td>
                <td>{{ $book['textbook']['author'] }}</td>
                <td>{{ $book['textbook']['edition'] }}</td>
                <td>{{ $book['textbook']['press'] }}</td>
                <td>{{ $book['textbook']['purchase_price'] }}元</td>
                <td>{{ $book['textbook']['price'] }}元</td>
                <td>
                    @if($course['type'] === 1)
                        {{ $course['textbook_num']['total_plan_seat'] }}人
                    @else
                        {{ $course['textbook_num'] }}人
                    @endif
                </td>
                <td>
                    @if($course['type'] === 1)
                        {{ $course['textbook_num']['total_informatics_seat'] }}人
                    @else
                        {{ $course['textbook_num'] }}人
                    @endif
                </td>
            </tr>
            <?php
                    if($course['type'] === 1){
                        // 是新生
                        $subTotal += $book['textbook']['purchase_price'] * $course['textbook_num']['total_informatics_seat'];
                    }
                    else{
                        $subTotal += $book['textbook']['purchase_price'] * $course['textbook_num'];
                    }
            ?>
        @endforeach
        <tr>
            <td colspan="11">成本小计(实际学生数 X 采购单价): {{ $subTotal }}元</td>
        </tr>
        <?php $totalCost += $subTotal; ?>
    @endforeach
    <tr>
        <td colspan="11">成本总计: {{ $totalCost }}元</td>
    </tr>
</table>
