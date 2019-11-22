<?php
    $subTotal = 0;
    $totalCost = 0;
?>
<table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
    <tr>
        <th>专业</th>
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
    @foreach($campus_textbook as $major)
        <?php
            $subTotal = 0;
        ?>
        @foreach($major['course']['course_textbooks'] as $key=>$book)
            <tr>
                <td>{{ $major['major_name'] }}</td>
                <td>{{ $major['course_name'] }}</td>
                <td>{{ $major['course']['year'] }}年级</td>
                <td>{{ $major['course']['term'] }}学期</td>
                <td>{{ $book['textbook']['name'] }}</td>
                <td>{{ $book['textbook']['author'] }}</td>
                <td>{{ $book['textbook']['edition'] }}</td>
                <td>{{ $book['textbook']['press'] }}</td>
                <td>{{ $book['textbook']['purchase_price'] }}元</td>
                <td>{{ $book['textbook']['price'] }}元</td>
                <td>
                    @if($major['type'] === 1)
                        {{ $major['textbook_num']['total_plan_seat'] }}人
                    @else
                        {{ $major['textbook_num'] }}人
                    @endif
                </td>
                <td>
                    @if($major['type'] === 1)
                        {{ $major['textbook_num']['total_informatics_seat'] }}人
                    @else
                        {{ $major['textbook_num'] }}人
                    @endif
                </td>
            </tr>
            <?php
                    if($major['type'] === 1){
                        // 是新生
                        $subTotal += $book['textbook']['purchase_price'] * $major['textbook_num']['total_informatics_seat'];
                    }
                    else{
                        $subTotal += $book['textbook']['purchase_price'] * $major['textbook_num'];
                    }
            ?>
        @endforeach
        <tr>
            <td colspan="12">成本小计(实际学生数 X 采购单价): {{ $subTotal }}元</td>
        </tr>
        <?php $totalCost += $subTotal; ?>
    @endforeach
    <tr>
        <td colspan="12">成本总计: {{ $totalCost }}元</td>
    </tr>
</table>
