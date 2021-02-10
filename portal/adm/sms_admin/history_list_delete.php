<?php
$sub_menu = "900400";
include_once("./_common.php");

auth_check_menu($auth, $sub_menu, "r");

$idx = $_REQUEST['IDX'];
$sv = $_REQUEST['sv'];
$sms_mean = $_REQUEST['SMS_MEAN'];
$fDate = $_REQUEST['fDate'];
$lDate = $_REQUEST['lDate'];
$send = $_REQUEST['send'];
$page = $_REQUEST['lDate'];

$sql_sel_DATA_DIX = " select * from CMS_SMS_DATA where IDX = $idx";
$result = sql_query($sql_sel_DATA_DIX);
if(!$result){
    alert('등록된 정보가 없습니다.');
}else{
    $sql_up_DATA_IS_DEL = " update CMS_SMS_DATA set IS_DEL = '1' where IDX = $idx";
    $result_del = sql_query($sql_up_DATA_IS_DEL);
    if($result_del){
        //goto_url("./history_list.php?&amp;sv=$sv&amp;SMS_MEAN=$sms_mean&amp;fDate=$fDate&amp;lDate=$lDate&amp;send=$send&amp;page=$page");
        alert("삭제가 완료 되었습니다.");
    }else{
        alert("삭제 오류!");
    }
}