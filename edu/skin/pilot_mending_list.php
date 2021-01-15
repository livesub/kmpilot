<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$ajaxpage_apply = G5_URL."/edu_process/lecture_apply_ajax.php";
$ajaxpage_cancel = G5_URL."/edu_process/lecture_cancel_ajax.php";

$sql = " select count(*) as cnt from ".$g5['pilot_mending_table'];
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select * from ".$g5['pilot_mending_table']." order by idx desc limit {$from_record}, {$rows} ";
$result = sql_query($sql);
$now_date = date("Y-m-d");
?>

<table border=1>
    <tr>
        <td><?=$lang['lecture_title2']?></td>
    </tr>
</table>
<table border=1>
    <tr>
        <td><?=$lang['lecture_num']?></td>
        <td><?=$lang['lecture_subject']?></td>
        <td><?=$lang['lecture_write_name']?></td>
        <td><?=$lang['lecture_date']?></td>
        <td><?=$lang['lecture_status']?></td>
        <td><?=$lang['lecture_etc']?></td>
    </tr>

<?php
    $virtual_num = $total_count - $rows * ($page - 1);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
    <tr>
        <td><?=$virtual_num;?></td>
        <td><?=$row['subject'];?></td>
        <td><?=$row['writer_name']?></td>
        <td><?=$row['startdatetime']?> ~ <?=$row['enddatetime']?></td>
<?php
    $row_apply = sql_fetch(" select * from {$g5['pilot_lecture_apply_table']} where mb_id='{$member['mb_id']}' and lecture_idx = '{$row[idx]}' and lecture_type_table = 'pilot_mending' ");
    if(count($row_apply) == 0) $ment = $lang['lecture_memt2'];
    else{
        //강의를 시청 했으면 했다 표시
        if($row_apply['lecture_completion_status'] == 'Y') $ment = $lang['lecture_memt3'];
        else $ment = $lang['lecture_memt1'];
    }
?>
        <td><?=$ment?></td>
        <td>
<?php
    if(count($row_apply) == 0){
        //신청이 안된상태
        if($now_date > $row['enddatetime']){
            //기간이 지났는지 파악
            echo $lang['lecture_end'];
        }else{
?>
            <input type="button" class="btn btn_02" value="<?=$lang['lecture_apply']?>" onclick="apply_chk('<?=$row['subject'];?>',<?=$row[idx]?>);">
<?php
        }
    }else{
        //신청이 된 상태
        //기간안에 있는지 확인
        if($now_date >= $row['startdatetime'] && $now_date <= $row['enddatetime']){
            if($row_apply['lecture_completion_status'] == 'N'){
                //강의 보기가 완료 되었을땐 취소를 못한다!
?>
                <input type="button" class="btn btn_02" value="<?=$lang['lecture_cancel']?>" onclick="cancel_chk('<?=$row['subject'];?>',<?=$row[idx]?>);">
<?php
            }
?>

            <input type="button" class="btn btn_02" value="<?=$lang['lecture_start']?>"  onclick="location.href='<?=G5_BBS_URL?>/content.php?co_id=lecture_view&idx=<?=$row[idx]?>&type=pilot_mending'">
<?php
        }else{
            echo $lang['lecture_end'];  //강의가 종료 됐을시
            if($row_apply['lecture_completion_status'] == 'N'){
                //강의 보기가 완료 되었을땐 취소를 못한다!
?>
            <input type="button" class="btn btn_02" value="<?=$lang['lecture_cancel']?>" onclick="cancel_chk('<?=$row['subject'];?>',<?=$row[idx]?>);">
<?php
            }
        }
    }
?>

        </td>
    </tr>
<?php
        $virtual_num--;
    }
?>
<?php if ($total_count == 0) { echo '<tr><td colspan="6" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>
</table>

<script>
    function apply_chk(subject,idx){
        var subject_tmp = subject + " <?=$lang['apply_confirm']?>";
        if(confirm(subject_tmp)){
            var ajaxUrl = "<?=$ajaxpage_apply?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "idx"                   : idx,
                    "lecture_type_table"    : "pilot_mending",
                },
                success: function(data){
                    if(trim(data) == "no_member"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "fail"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "OK"){
                        alert("<?=$lang['apply_ok']?>");
                        location.reload();
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
        }else{
            return false;
        }
    }

    function cancel_chk(subject,idx){

        var subject_tmp = subject + " <?=$lang['cancel_confirm']?>";
        if(confirm(subject_tmp)){
            var ajaxUrl = "<?=$ajaxpage_cancel?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "idx"                   : idx,
                    "lecture_type_table"    : "pilot_mending",
                },
                success: function(data){
                    if(trim(data) == "no_member"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "fail"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "OK"){
                        alert("<?=$lang['cancel_ok']?>");
                        location.reload();
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
        }else{
            return false;
        }
    }
</script>