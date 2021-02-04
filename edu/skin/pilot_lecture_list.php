<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$edu_type = $_GET['edu_type'];
$edu_idx = $_GET['edu_idx'];
$apply_idx = $_GET['apply_idx'];

if($edu_type == "" || $edu_idx == "" || $apply_idx == ""){
    alert($lang['fatal_err'],"content.php?co_id=pilot_edu_list");
    exit;
}

//신청자인지 판단
$row_cnt = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and apply_idx = '{$apply_idx}' and apply_cancel = 'N' ");
$apply_count = $row_cnt['apply_cnt'];

if($apply_count == 0){
    alert($lang['fatal_err'],"content.php?co_id=pilot_edu_list");
    exit;
}

//교육명 찾기
$edu_name_row = sql_fetch(" select edu_name_{$lang_type} as edu_name from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' ");

/* 교육 동영상을 확인 했는지 확인 하기 */
//교육에 등록된 동영상 갯수 구하기
$sql_cnt = " select count(*) as cnt from kmp_pilot_lecture_list where lecture_del_type = 'N' and edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' ";
$row_cnt = sql_fetch($sql_cnt);
$total_count = $row_cnt['cnt'];

$sql = " select * from kmp_pilot_lecture_list  where lecture_del_type = 'N' and edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' order by lecture_idx asc ";
$result = sql_query($sql);
?>

<form name="fform" id="fform" action="content.php?co_id=pilot_lecture_view" method="post">
    <input type="hidden" name="edu_type" id="edu_type" value="<?=$edu_type?>">
    <input type="hidden" name="edu_idx" id="edu_idx" value="<?=$edu_idx?>">
    <input type="hidden" name="apply_idx" id="apply_idx" value="<?=$apply_idx?>">
    <input type="hidden" name="lecture_idx" id="lecture_idx" value="<?=$apply_idx?>">
</form>

<table>
    <tr>
        <td><b><?=$lang['edu_subtitle3']?></b></td>
    </tr>
</table>
<br><br>
<table>
    <tr>
        <td><?=$edu_name_row['edu_name']?></td>
    </tr>
</table>


<table border=1>
    <tr>
        <td><?=$lang['edu_list_num']?></td>
        <td><?=$lang['edu_lecture_name']?></td>
        <td><?=$lang['edu_lecture_teacher']?></td>
        <td><?=$lang['edu_lecture_time']?></td>
        <td><?=$lang['edu_lecture_view']?></td>
        <td><?=$lang['edu_lecture_complete']?></td>
    </tr>

<?php
$virtual_num = 1;
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $view_cnt = "";
    $view_count = "";
    $view_ment = "";

    //이수/미이수 파악
    $view_cnt = sql_fetch(" select count(*) view_cnt from kmp_pilot_lecture_complet where lecture_idx = '{$row[lecture_idx]}' and edu_idx = '{$edu_idx}' and apply_idx = '{$apply_idx}' and mb_id = '{$member['mb_id']}' ");
    $view_count = $view_cnt['view_cnt'];

    if($view_count == 1) $view_ment = $lang['edu_view'];
    else $view_ment = $lang['edu_no_view'];
?>
    <tr>
        <td><?=$virtual_num?></td>
        <td><?=$row['lecture_subject']?></td>
        <td><?=$row['lecture_name']?></td>
        <td><?=$row['lecture_time']?> 분</td>
        <td><input type='button' class='btn btn_02' value='<?=$lang['edu_lecture_play']?>' onclick='lecture_view(<?=$row[lecture_idx]?>)'></td>
        <td><?=$view_ment?></td>
    </tr>
<?php
    $virtual_num++;
}
?>
<?php if ($total_count == 0) { echo '<tr><td colspan="6" class="empty_table">'.$lang['edu_apply_list'].'</td></tr>'; } ?>
</table>

<script>
    function lecture_view(lecture_idx){
        $("#lecture_idx").val(lecture_idx);
        $('#fform').submit();
    }
</script>
