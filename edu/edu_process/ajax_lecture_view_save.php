<?php
include_once('../common.php');
include_once(PORTAL_DATA_PATH."/dbconfig.php");

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    echo "no_member";
    exit;
}else{
    $lecture_idx = $_POST['lecture_idx'];
    $apply_idx = $_POST['apply_idx'];
    $edu_idx = $_POST['edu_idx'];

    if($edu_idx == "" || $apply_idx == "" || $lecture_idx == ""){
        echo "fail";
        exit;
    }

    //신청자인지 판단
    $row_cnt = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and apply_idx = '{$apply_idx}' and apply_cancel = 'N' ");
    $apply_count = $row_cnt['apply_cnt'];

    if($apply_count == 0){
        echo "fail";
        exit;
    }

    $result = sql_query(" insert into kmp_pilot_lecture_complet set lecture_idx = '{$lecture_idx}', edu_idx = '{$edu_idx}', apply_idx = '{$apply_idx}', mb_id='{$member['mb_id']}', mb_name='{$member['mb_name']}', complet_date = now() ");

    if($result){
        echo "OK";
    }else{
        echo "fail";
    }
}
?>