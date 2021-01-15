<?php
include_once('../common.php');
include_once(PORTAL_DATA_PATH."/dbconfig.php");

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    echo "no_member";
    exit;
}else{
    $idx = $_POST[idx];
    $lecture_type_table = $_POST['type'];

    $result = sql_query(" update {$g5['pilot_lecture_apply_table']} set lecture_completion_date = now(), lecture_completion_status = 'Y' where lecture_idx='{$idx}' and mb_id='{$member['mb_id']}' and lecture_type_table='{$lecture_type_table}' ");

    if($result){
        echo "OK";
    }else{
        echo "fail";
    }
}
?>