<?php
$sub_menu = $_GET['sub_menu'];

include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');


$idx = $_GET['idx'];
$type = $_GET['type'];

$table = $g5[$type."_table"];

//교육 테이블 정보 가져 오기
$row_lecture = sql_fetch(" select * from {$table} where idx = '{$idx}' ");


$g5['title'] = $row_lecture['subject'];


include_once('./admin.head.php');


//수강신청 인원 구하기
$row_apply = sql_fetch(" select count(*) as cnt from {$g5['pilot_lecture_apply_table']} where lecture_idx = '{$row_lecture['idx']}' and lecture_type_table = '{$type}' ");
$row_apply_cnt = $row_apply['cnt'];

//수강신청 수료 인원 구하기
$row_complet = sql_fetch(" select count(*) as cnt from {$g5['pilot_lecture_apply_table']} where lecture_idx = '{$row_lecture['idx']}' and lecture_type_table = '{$type}' and lecture_completion_status = 'Y' ");
$row_complet_cnt = $row_complet['cnt'];

//수강신청 미수료 인원 구하기
$row_nocomplet = sql_fetch(" select count(*) as cnt from {$g5['pilot_lecture_apply_table']} where lecture_idx = '{$row_lecture['idx']}' and lecture_type_table = '{$type}' and lecture_completion_status = 'N' ");
$row_nocomplet_cnt = $row_nocomplet['cnt'];
?>


<table border=1>
    <tr>
        <th scope="col">수강 신청 인원 : <?=$row_apply_cnt?> 명</th>
        <th scope="col">수료 인원 : <?=$row_complet_cnt?> 명</th>
        <th scope="col">미수료 인원 : <?=$row_nocomplet_cnt?> 명</th>
    </tr>

    <tr>
        <td>
            <table>
                <tr>
                    <td>이름</td>
                    <td>수강신청일</td>
                </tr>
                <tr>
                    <td colspan=2>
                    <div style="width:100%; height:400px; overflow:auto">
                        <table>
<?php
$result_apply = sql_query(" select * from ".$g5['pilot_lecture_apply_table']." where lecture_idx = '{$row_lecture['idx']}' and lecture_type_table = '{$type}' order by idx desc ");
for ($i=0; $rows_apply=sql_fetch_array($result_apply); $i++) {
    //회원 이름 찾기
    $mb_name = sql_fetch(" select mb_name from {$g5['member_table']} where mb_id = '{$rows_apply[mb_id]}' ");
    $lecture_apply_date = explode(" ",$rows_apply['lecture_apply_date']);
?>
                            <tr>
                                <td><?=$mb_name['mb_name'];?></td>
                                <td><?=$lecture_apply_date[0];?></td>
                            </tr>
<?php
}
?>
                        </table>
                    </div>

                    </td>
                </tr>
            </table>
        </td>





        <td>
            <table>
                <tr>
                    <td>이름</td>
                    <td>수강신청일</td>
                </tr>
                <tr>
                    <td colspan=2>
                    <div style="width:100%; height:400px; overflow:auto">
                        <table>
<?php
$result_complet = sql_query(" select * from ".$g5['pilot_lecture_apply_table']." where lecture_idx = '{$row_lecture['idx']}' and lecture_type_table = '{$type}' and lecture_completion_status = 'Y' order by idx desc ");
//수료인원
for ($j=0; $rows_complet=sql_fetch_array($result_complet); $j++) {
    //회원 이름 찾기
    $mb_name = sql_fetch(" select mb_name from {$g5['member_table']} where mb_id = '{$rows_complet[mb_id]}' ");
    $lecture_complet_date = explode(" ",$rows_complet['lecture_apply_date']);
?>
                            <tr>
                                <td><?=$mb_name['mb_name'];?></td>
                                <td><?=$lecture_complet_date[0];?></td>
                            </tr>
<?php
}
?>
                        </table>
                    </div>

                    </td>
                </tr>
            </table>
        </td>




        <td>
            <table>
                <tr>
                    <td>이름</td>
                    <td>수강신청일</td>
                </tr>
                <tr>
                    <td colspan=2>
                    <div style="width:100%; height:400px; overflow:auto">
                        <table>
<?php
$result_nocomplet = sql_query(" select * from ".$g5['pilot_lecture_apply_table']." where lecture_idx = '{$row_lecture['idx']}' and lecture_type_table = '{$type}' and lecture_completion_status = 'N' order by idx desc ");
//수료인원
for ($k=0; $rows_nocomplet=sql_fetch_array($result_nocomplet); $k++) {
    //회원 이름 찾기
    $mb_name = sql_fetch(" select mb_name from {$g5['member_table']} where mb_id = '{$rows_nocomplet[mb_id]}' ");
    $lecture_nocomplet_date = explode(" ",$rows_nocomplet['lecture_apply_date']);
?>
                            <tr>
                                <td><?=$mb_name['mb_name'];?></td>
                                <td><?=$lecture_nocomplet_date[0];?></td>
                            </tr>
<?php
}
?>

                        </table>
                    </div>

                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
<?php
if($row_apply_cnt != 0){
?>
        <td scope="col"><input type="button" class="btn btn_02" value="다운로드" onclick="location.href='pilot_lecture_apply_down.php?lecture_idx=<?=$row_lecture['idx']?>&type=<?=$type?>&status=all'"></td>
<?php
}else{
?>
        <td scope="col"></td>
<?php
}

if($row_complet_cnt != 0){
?>
        <td scope="col"><input type="button" class="btn btn_02" value="다운로드" onclick="location.href='pilot_lecture_apply_down.php?lecture_idx=<?=$row_lecture['idx']?>&type=<?=$type?>&status=complet'"></td>
<?php
}else{
?>
        <td scope="col"></td>
<?php
}

if($row_nocomplet_cnt != 0){
?>
        <td scope="col"><input type="button" class="btn btn_02" value="다운로드" onclick="location.href='pilot_lecture_apply_down.php?lecture_idx=<?=$row_lecture['idx']?>&type=<?=$type?>&status=nocomplet'"></td>
<?php
}else{
?>
        <td scope="col"></td>
<?php
}
?>
    </tr>
</table>


<?php
include_once ('./admin.tail.php');