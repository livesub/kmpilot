<?php
include_once('./_common.php');

$html_total = $_POST['html_total'];
$edu_onoff_type = $_POST['edu_onoff_type'];
$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];
$process_cnt = $_POST['process_cnt'];
$process_cnt_cut = explode(",",$process_cnt);
$lecture_del_type = $_POST['lecture_del_type'];
$lecture_del_type = substr($lecture_del_type, 0, -1);

$del_type = $_POST['del_type'];

if($del_type == "inup"){
    //삭제 아닐때
    for($i = 0; $i < count($process_cnt_cut); $i++){
        $lecture_idx = $_POST['lecture_idx'.$process_cnt_cut[$i]];
        $lecture_subject = $_POST['lecture_subject'.$process_cnt_cut[$i]];
        $lecture_name = $_POST['lecture_name'.$process_cnt_cut[$i]];
        $lecture_time = $_POST['lecture_time'.$process_cnt_cut[$i]];
        $lecture_youtube = $_POST['lecture_youtube'.$process_cnt_cut[$i]];

        if($lecture_idx == ""){
            //신규데이터
            $result = sql_query(" insert into kmp_pilot_lecture_list set edu_idx = '{$edu_idx}', edu_onoff_type='{$edu_onoff_type}', edu_type='{$edu_type}', lecture_subject = '{$lecture_subject}',  lecture_name = '{$lecture_name}', lecture_time = '{$lecture_time}', lecture_youtube = '{$lecture_youtube}', lecture_regi = now() ");
        }else{
            //저장 데이터
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
}else if($del_type == "del"){
    //삭제 일때(delete 대신 플러그 값 변경)
    $cut_comma = explode(",",$lecture_del_type);

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