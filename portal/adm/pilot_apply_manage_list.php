<?php

include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$edu_onoff_type = $_GET['edu_onoff_type'];    //온라인,오프라인  등록 페이지 같이 쓰기 위해 파라메터로 구분 함
$edu_idx = $_GET['edu_idx'];
$edu_type = $_GET['edu_type'];
$choice_type = $_GET['choice_type'];

/*
if($edu_idx == "" || $edu_type == "" || $choice_type == ""){
    alert($lang['fatal_err'],"");
    exit;
}
*/
if($edu_onoff_type == "off")
{
    $g5['title'] = '오프라인 교육';
    $sub_menu = "400100";
    $title_change = "교육기간";
    $return_page = "pilot_off_edu_list.php";
    $colspan = 10;
}else{
    $g5['title'] = '온라인 교육';
    $sub_menu = "400200";
    $title_change = "수강기간";
    $return_page = "pilot_on_edu_list.php";
    $colspan = 12;
}

$search = "";
if($_GET['sfl'] != "" && $_GET['stx'] != ""){
    //검색
    if($_GET['sfl'] == "mb_name") $search = " and A.{$_GET[sfl]} like '%{$_GET[stx]}%' ";
    else $search = " and B.{$_GET[sfl]} like '%{$_GET[stx]}%' ";
}

include_once('./admin.head.php');

if($choice_type == "select"){
    //선택시
    $and_add = " and A.edu_idx = '{$edu_idx}' ";
}

$sql_common = " from kmp_pilot_edu_apply A, kmp_pilot_edu_list B ";
$sql_order = " where A.apply_cancel = 'N' {$and_add} and A.edu_idx = B.edu_idx and A.edu_type = B.edu_type and B.edu_onoff_type = '{$edu_onoff_type}'  {$search} order by B.edu_idx desc";

$sql = " select count(*) as cnt {$sql_common} {$sql_order} ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
?>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
<input type="hidden" name="edu_idx" id="edu_idx" value="<?=$edu_idx?>">
<input type="hidden" name="edu_type" id="edu_type" value="<?=$edu_type?>">
<input type="hidden" name="choice_type" id="choice_type" value="<?=$choice_type?>">
<input type="hidden" name="edu_onoff_type" id="edu_onoff_type" value="<?=$edu_onoff_type?>">


<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="">교육종류선택</option>
    <option value="edu_type_name" <?php if($_GET['sfl'] == "edu_type_name") echo "selected";?>>교육종류</option>
    <option value="edu_name_kr" <?php if($_GET['sfl'] == "edu_name_kr") echo "selected";?>>교육명(한글)</option>
    <option value="mb_name" <?php if($_GET['sfl'] == "mb_name") echo "selected";?>>이름</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>

<div class="btn_fixed_top">
    <a href="./<?=$return_page?>" id="edu_list" class="btn btn_01">목록</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" rowspan="2" >
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">순번</th>
        <th scope="col">교육종류</th>
        <th scope="col">교육명</a></th>
        <th scope="col"><?=$title_change?></th>
        <th scope="col">신청일자</th>
        <th scope="col">이름</th>
        <th scope="col">생년월일</th>
        <th scope="col">면허유효기간</th>
        <th scope="col">정년일자</th>
<?php
if($edu_onoff_type == "on"){
?>
        <th scope="col">과목이수현황</th>
        <th scope="col">수료일자</th>
<?php
}
?>
    </tr>

    </thead>
    <tbody>

<?php
$virtual_num = $total_count - $rows * ($page - 1);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $edu_ment = "";
    $complete_ment = "";
    $complete_date = "";
    $edu_ment = edu_type($row['edu_type']);

    //회원 정보 가져오기
    $row_mem = sql_fetch(" select mb_name, mb_birth, mb_hp, mb_validity_day_from, mb_validity_day_to, mb_license_ext_day_from, mb_license_ext_day_to from kmp_member where mb_id = '{$row['mb_id']}' ");
?>
    <tr>
        <td><input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['edu_idx'] ?>"></td>
        <td><?=$virtual_num?></td>
        <td><?=$edu_ment?></td>
        <td><?=$row['edu_name_kr']?></td>
        <td><?=$row['edu_cal_start']?> ~ <?=$row['edu_cal_end']?></td>
        <td><?=$row['apply_date']?></td>
        <td><?=$row_mem['mb_name']?></td>
        <td><?=$row_mem['mb_birth']?></td>
        <td><?=$row_mem['mb_validity_day_from']?> ~ <?=$row_mem['mb_validity_day_to']?></td>
        <td><?=$row_mem['mb_license_ext_day_from']?> ~ <?=$row_mem['mb_license_ext_day_to']?></td>
<?php
    if($edu_onoff_type == "on"){
        //이수 현황
        $row_complete = sql_fetch(" select * from kmp_pilot_edu_apply where mb_id = '{$row['mb_id']}' and edu_idx = '{$row['edu_idx']}' and edu_type = '{$row['edu_type']}' and apply_cancel = 'N' ");
        if($row_complete['lecture_completion_status'] == "N"){
            $complete_ment = "미수료";
            $complete_date = "미수료";
        }else{
            $complete_ment = "수료";
            $complete_date = $row_complete['lecture_completion_date'];
        }
?>
        <td><?=$complete_ment?></td>
        <td><?=$complete_date?></td>
<?php
    }
?>
    </tr>
<?php
    $virtual_num--;
}
if (!$i)
    echo "<tr><td colspan='{$colspan}' class=\"empty_table\">자료가 없습니다.</td></tr>";
?>
    </tbody>
    </table>
</div>
</form>