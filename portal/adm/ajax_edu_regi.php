<?php
include_once('./_common.php');

$w = $_POST['w'];
$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];
$edu_onoff_type = $_POST['edu_onoff_type'];
$edu_type_name = edu_type($edu_type);   //교육 종류 검색을 위해 명을 입력 한다

$edu_name_kr = $_POST['edu_name_kr'];
$edu_name_en = $_POST['edu_name_en'];
$edu_way = $_POST['edu_way'];
$edu_place = $_POST['edu_place'];
$edu_time = $_POST['edu_time'];
$edu_cal_start = $_POST['edu_cal_start'];
$edu_cal_end = $_POST['edu_cal_end'];
$edu_cal_type = $_POST['edu_cal_type'];

$edu_receipt_start = $_POST['edu_receipt_start'];
$edu_receipt_end = $_POST['edu_receipt_end'];
$edu_receipt_type = $_POST['edu_receipt_type'];
$edu_receipt_status = $_POST['edu_receipt_status'];
$edu_person = $_POST['edu_person'];
$page = $_POST['page'];


//종료일 미정 선택시 종료일을 지운다
if($edu_cal_type == "0"){
    $edu_cal_start = "";
    $edu_cal_end = "";
}else $edu_cal_type = 1;

if($edu_receipt_type == "0"){
    $edu_receipt_end = "";
    $edu_receipt_start = "";
}else $edu_receipt_type = 1;

if($w == ""){
    //등록
    $result = sql_query(" insert into kmp_pilot_edu_list set edu_onoff_type = '{$edu_onoff_type}', edu_type='{$edu_type}', edu_type_name='{$edu_type_name}', edu_name_kr = '{$edu_name_kr}', edu_name_en = '{$edu_name_en}', edu_way = '{$edu_way}', edu_place = '{$edu_place}', edu_time = '{$edu_time}', edu_cal_start = '{$edu_cal_start}', edu_cal_end = '{$edu_cal_end}', edu_cal_type = '{$edu_cal_type}', edu_receipt_start = '{$edu_receipt_start}',edu_receipt_end = '{$edu_receipt_end}', edu_receipt_type = '{$edu_receipt_type}', edu_receipt_status = '{$edu_receipt_status}', edu_person = '{$edu_person}', edu_regi = now() ");

    if($result){
        echo "ok";
        exit;
    }else{
        echo "no";
        exit;
    }
}else if($w == "u"){
    if($edu_cal_type == 1 && $edu_receipt_type == 1){
        //교육,접수 기간이 확정일시 게시물 등록일을 업뎃 한다.
        $edu_regi = ", edu_regi = now() ";
    }

    //수정
    $result = sql_query(" update kmp_pilot_edu_list set edu_onoff_type = '{$edu_onoff_type}', edu_type='{$edu_type}', edu_type_name='{$edu_type_name}', edu_name_kr = '{$edu_name_kr}', edu_name_en = '{$edu_name_en}', edu_way = '{$edu_way}', edu_place = '{$edu_place}', edu_time = '{$edu_time}', edu_cal_start = '{$edu_cal_start}', edu_cal_end = '{$edu_cal_end}', edu_cal_type = '{$edu_cal_type}', edu_receipt_start = '{$edu_receipt_start}', edu_receipt_end = '{$edu_receipt_end}', edu_receipt_type = '{$edu_receipt_type}', edu_receipt_status = '{$edu_receipt_status}', edu_person = '{$edu_person}' {$edu_regi} where edu_idx = '{$edu_idx}' ");

    if($result){
        echo "ok";
        exit;
    }else{
        echo "no";
        exit;
    }
}else if($w == "d"){
    $count = count($_POST['chk']);

    if(!$count) {
        echo "no_idx";
        exit;
    }

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $sql = " update kmp_pilot_edu_list set edu_del_type = 'Y' where edu_idx = '$k' ";
        sql_query($sql);
    }

    echo "ok";
}



?>