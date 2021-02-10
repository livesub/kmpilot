<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

//년도 검색
$year_ch = $_GET['year_ch'];
$default_year = 2021;
$now_year = date("Y");
if($year_ch == "") $select_y = $now_year;
else $select_y = $year_ch;

$sql = " select count(*) as cnt from kmp_pilot_edu_list where edu_del_type = 'N' and edu_receipt_start like '%{$select_y}%' ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql_list = " select * from kmp_pilot_edu_list where edu_del_type = 'N' and edu_receipt_start like '%{$select_y}%' order by edu_idx asc ";
$result = sql_query($sql_list);
?>

<table>
    <tr>
        <td><b><?=$lang['menu_apply_status']?><b></td>
        <td> <?=$lang['edu_year_search']?>
            <select name="year_ch" id="year_ch" onchange = "year_change();">
<?php
    for($k = $default_year; $k <= $now_year; $k++){
?>
                <option value="<?=$k?>" <?php if($k == $select_y) echo "selected"?>><?=$k?></option>
<?php
    }
?>
            </select>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td><?=$lang['edu_apply_status']?></td>
    </tr>
</table>

<table border=1>
    <tr>
        <td><?=$lang['edu_list_num']?></td>
        <td><?=$lang['edu_list_name']?></td>
        <td><?=$lang['edu_list_type']?></td>
        <td><?=$lang['edu_apply_start']?></td>
        <td><?=$lang['edu_apply_end']?></td>
        <td><?=$lang['edu_apply_status2']?></td>
    </tr>
<?php
$virtual_num = 1;
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $status_data = "";
    $edu_name = $row["edu_name_$lang_type"];
    $edu_way = edu_way($row["edu_way"],$lang);

    if($row['edu_receipt_status'] == "P"){
        $edu_cal_start = $lang['edu_list_undefined'];
        $edu_cal_end = $lang['edu_list_undefined'];
    }else{
        $edu_cal_start = $row['edu_cal_start'];
        $edu_cal_end = $row['edu_cal_end'];
    }

    //신청 상태 확인
    $row_data = sql_fetch(" select count(*) as cnt from kmp_pilot_edu_apply where edu_idx = '{$row['edu_idx']}' and edu_type = '{$row['edu_type']}' and mb_id='{$member['mb_id']}' and apply_cancel = 'N' ");
    $status_data = $row_data['cnt'];

    if($status_data == 1) $status_ment = $lang['edu_apply_receipt_st'];
    else $status_ment = $lang['edu_apply_receipt_st1'];
?>
    <tr>
        <td><?=$virtual_num?></td>
        <td><?=$edu_name?></td>
        <td><?=$edu_way?></td>
        <td><?=$edu_cal_start?></td>
        <td><?=$edu_cal_end?></td>
        <td><?=$status_ment?></td>
    </tr>
<?php
    $virtual_num++;
}
?>

    <?php if ($total_count == 0) { echo '<tr><td colspan="6" class="empty_table">'.$lang['edu_apply_list'].'</td></tr>'; } ?>
</table>






<table>
    <tr>
        <td><?=$lang['edu_apply_status1']?></td>
    </tr>
</table>

<table border=1>
    <tr>
        <td><?=$lang['edu_list_num']?></td>
        <td><?=$lang['edu_list_name']?></td>
        <td><?=$lang['edu_list_type']?></td>
        <td><?=$lang['edu_apply_start']?></td>
        <td><?=$lang['edu_apply_end']?></td>
        <td><?=$lang['edu_apply_complete']?></td>
        <td><?=$lang['edu_apply_certificates']?></td>
    </tr>
<?php
//교육 이수현황
$sql_cp = " select count(*) as cnt from kmp_pilot_edu_apply where apply_cancel = 'N' and apply_date like '%{$select_y}%' and mb_id = '{$member['mb_id']}' ";
$row_cp = sql_fetch($sql_cp);
$total_count_cp = $row_cp['cnt'];

$sql_list_cp = " select * from kmp_pilot_edu_apply where apply_cancel = 'N' and apply_date like '%{$select_y}%' and mb_id = '{$member['mb_id']}' order by edu_idx asc ";
$result_cp = sql_query($sql_list_cp);

$virtual_num_cp = 1;
for ($j=0; $row_cp=sql_fetch_array($result_cp); $j++) {
    $lecture_cp_st_ment = "";
    $edu_name_cp = "";
    $edu_way_cp = "";
    $edu_cal_start_cp = "";
    $edu_cal_end_cp = "";
    $edu_print_button = "";

    $row_edu = sql_fetch(" select edu_name_kr, edu_name_en, edu_way, edu_cal_start, edu_cal_end from kmp_pilot_edu_list where edu_idx = '{$row_cp['edu_idx']}' and edu_type = '{$row_cp['edu_type']}' ");
    $edu_name_cp = $row_edu["edu_name_$lang_type"];
    $edu_way_cp = edu_way($row_edu["edu_way"],$lang);

    if($row_cp['edu_receipt_status'] == "P"){
        $edu_cal_start_cp = $lang['edu_list_undefined'];
        $edu_cal_end_cp = $lang['edu_list_undefined'];
    }else{
        $edu_cal_start_cp = $row_edu['edu_cal_start'];
        $edu_cal_end_cp = $row_edu['edu_cal_end'];
    }

    if($row_cp['lecture_completion_status'] == "Y")
    {
        $lecture_cp_st_ment = $lang['mypage_complet1'];
        $edu_print_button = "<input type='button' class='btn btn_02' value='".$lang[edu_print]."' onclick='print_chk(\"{$row_cp[edu_idx]}\",\"{$row_cp[edu_type]}\")'>";
    }else{
        $lecture_cp_st_ment = $lang['mypage_complet2'];
        $edu_print_button = "<input type='button' class='btn btn_02' value='".$lang[edu_print]."' onclick='alert(\"$lang[edu_apply_nocomplete]\");return false;'>";
    }

?>
    <tr>
        <td><?=$virtual_num_cp?></td>
        <td><?=$edu_name_cp?></td>
        <td><?=$edu_way_cp?></td>
        <td><?=$edu_cal_start_cp?></td>
        <td><?=$edu_cal_end_cp?></td>
        <td><?=$lecture_cp_st_ment?></td>
        <td><?=$edu_print_button?></td>
    </tr>
<?php
    $virtual_num_cp++;
}
?>

    <?php if ($total_count_cp == 0) { echo '<tr><td colspan="7" class="empty_table">'.$lang['edu_apply_list'].'</td></tr>'; } ?>
</table>


<form name="form_print" id="form_print" method="POST" action="../skin/pilot_certificate_print.php">
    <input type="hidden" name="edu_idx" id="edu_idx" vlaue="">
    <input type="hidden" name="edu_type" id="edu_type" vlaue="">
</form>

<script>
    function print_chk(edu_idx,edu_type){
        $("#edu_idx").val(edu_idx);
        $("#edu_type").val(edu_type);
        $("#form_print").submit();
    }
</script>

<script>
    function year_change(){
        location.href = "content.php?co_id=pilot_edu_apply_status&year_ch="+$("#year_ch option:selected").val();
    }
</script>