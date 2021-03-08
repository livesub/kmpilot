<?php
include_once('./_common.php');

$w = $_POST['w'];

$edu_idx = $_POST['app_edu_idx'];
$edu_type = $_POST['edu_type'];

$edu_complet_type = $_POST['edu_complet_type'];
$count = count($_POST['chk']);
$now_year = date("Y");

if(!$count) {
    echo "no_idx";
    exit;
}

if($edu_complet_type == "complet"){
    //수료확인
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $edu_type = $_POST['edu_type_'.$k];
        $certificate_cnt = 0;

        //이미 순번이 발급 되었는지 확인
        $select_row = sql_fetch(" select certificate_num from kmp_pilot_edu_apply where apply_idx = '{$k}' ");
        if($select_row['certificate_num'] == "0"){
            //DB 에 완료 저장
            $result_up = sql_query(" update kmp_pilot_edu_apply set lecture_completion_date = now(), lecture_completion_status = 'Y' where apply_idx = '{$k}' ");

            //수료증 번호 만들기(순번은 당해년도 교육의 총인원 순번으로 01부터 시작 1차 교육생 10명, 2차 교육생 10명, 3차 교육생 20명 이면 총 01~40까지 발급)
            $certificate_max = sql_fetch(" select max(certificate_num) as certificate_cnt from kmp_pilot_edu_apply where edu_type = '{$edu_type}' and certificate_num != 0 and apply_cancel = 'N' and lecture_completion_status = 'Y' and lecture_completion_date like '%{$now_year}%' order by mb_name asc ");
            $certificate_cnt = $certificate_max['certificate_cnt'];
            $certificate_cnt = $certificate_cnt + 1;

            //수료증 번호 업뎃
            $result_cert_up = sql_query(" update kmp_pilot_edu_apply set certificate_num = '{$certificate_cnt}' where apply_idx = '{$k}' ");
        }
    }
}else if($edu_complet_type == "cancel"){
    //수료취소
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $edu_type = $_POST['edu_type_'.$k];
        //DB 에 완료 저장
        $result_up = sql_query(" update kmp_pilot_edu_apply set lecture_completion_date = '', lecture_completion_status = 'N', certificate_num = '0' where apply_idx = '{$k}' ");
    }
}

echo "ok";

?>