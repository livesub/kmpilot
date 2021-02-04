<?php
include_once('./_common.php');

$html_total = $_POST['html_total'];
$edu_onoff_type = $_POST['edu_onoff_type'];
$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];
$lecture_del_type = $_POST['lecture_del_type'];
$tmp = substr($lecture_del_type, 0, -1);

if($lecture_del_type == ""){
    //삭제 아닐때
    for($i = 0; $i < $html_total; $i++){
        $lecture_idx = $_POST['lecture_idx'.$i];
        $lecture_subject = $_POST['lecture_subject'.$i];
        $lecture_name = $_POST['lecture_name'.$i];
        $lecture_time = $_POST['lecture_time'.$i];
        $lecture_youtube = $_POST['lecture_youtube'.$i];

        //DB 에 저장 되어 있는지 먼저 확인
        $row = sql_fetch(" select count(*) as cnt from kmp_pilot_lecture_list where lecture_idx = '{$lecture_idx}' and edu_type = '{$edu_type}' ");
        $total_count = $row['cnt'];

        //삭제가 아닐때
        if($total_count == 0){
            //insert
            $result = sql_query(" insert into kmp_pilot_lecture_list set edu_idx = '{$edu_idx}', edu_onoff_type='{$edu_onoff_type}', edu_type='{$edu_type}', lecture_subject = '{$lecture_subject}',  lecture_name = '{$lecture_name}', lecture_time = '{$lecture_time}', lecture_youtube = '{$lecture_youtube}', lecture_regi = now() ");
        }else{
            //update
            $result = sql_query(" update kmp_pilot_lecture_list set edu_idx = '{$edu_idx}', edu_onoff_type='{$edu_onoff_type}', edu_type='{$edu_type}', lecture_subject = '{$lecture_subject}',  lecture_name = '{$lecture_name}', lecture_time = '{$lecture_time}', lecture_youtube = '{$lecture_youtube}' where lecture_idx = '{$lecture_idx}' and edu_type = '{$edu_type}' ");
        }
    }

    if($result){
        echo "ok";
        exit;
    }else{
        echo "no";
        exit;
    }
}else if($lecture_del_type != ""){
    //삭제 일때(delete 대신 플러그 값 변경)
    $cut_comma = explode(",",$tmp);

    for($e = 0; $e < count($cut_comma); $e++){
        $row_sql = sql_fetch(" select count(*) as cnt from kmp_pilot_lecture_list where lecture_idx = '{$cut_comma[$e]}' and edu_type = '{$edu_type}' ");
        $total_count_sql = $row_sql['cnt'];

        if($total_count_sql != 0){
            $result = sql_query(" update kmp_pilot_lecture_list set lecture_del_type = 'Y' where lecture_idx = '{$cut_comma[$e]}' and edu_type = '{$edu_type}' ");
        }
    }

    if($result){
        echo "ok_del";
        exit;
    }else{
        echo "no_del";
        exit;
    }
}
?>