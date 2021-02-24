<?php
include_once('./_common.php');

$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];
$choice_type = $_POST['choice_type'];
$edu_onoff_type = $_POST['edu_onoff_type'];


$count = count($_POST['chk']);

if(!$count) {
    echo "no_idx";
    exit;
}

if($edu_idx == "" || $edu_type == "" || $edu_onoff_type == ""){
    echo "fail";
    exit;
}

//관리자 교육 접수현황 자동 업뎃 시키기
admin_receipt_status($edu_idx,$edu_type,$count,"in");

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;

    //회원 정보를 가져 온다.
    $row_m = sql_fetch(" select mb_id, mb_name from kmp_member where mb_no = '{$k}' ");
    $result = sql_query(" insert into kmp_pilot_edu_apply set edu_idx='{$edu_idx}', edu_type='{$edu_type}', mb_id='{$row_m['mb_id']}', mb_name='{$row_m['mb_name']}', apply_date = now() ");
}
echo "ok";
?>