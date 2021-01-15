<?php
include_once('../common.php');
include_once(PORTAL_DATA_PATH."/dbconfig.php");

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    echo "no_member";
    exit;
}else{
    $idx = $_POST['idx'];
    //$lecture_type_table = $g5[$_POST['lecture_type_table']];
    $lecture_type_table = $_POST['lecture_type_table'];

    //이미강의를 신청을 했는지 파악
    $row_status = sql_fetch(" select lecture_completion_status from {$g5['pilot_lecture_apply_table']} where mb_id='{$member['mb_id']}' and lecture_idx = '{$idx}' and lecture_type_table = '{$lecture_type_table}' ");
    if($row_status['lecture_completion_status'] == 'Y'){
        echo "fail";
        exit;
    }

    $result = sql_query(" insert into {$g5['pilot_lecture_apply_table']} set lecture_idx='{$idx}', lecture_type_table='{$lecture_type_table}', mb_id='{$member['mb_id']}', mb_name='{$member['mb_name']}', lecture_apply_date = now() ");

    if($result){
        echo "OK";
        exit;
    }else{
        echo "fail";
        exit;
    }
}
?>