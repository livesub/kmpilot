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
    $edu_type = $_POST['edu_type'];
    $now_year = date("Y");

    if($edu_idx == "" || $apply_idx == "" || $lecture_idx == ""){
        echo "fail";
        exit;
    }

    //신청자인지 판단
    $row_cnt = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and apply_idx = '{$apply_idx}' and apply_cancel = 'N' and mb_id='{$member['mb_id']}' ");
    $apply_count = $row_cnt['apply_cnt'];

    if($apply_count == 0){
        echo "fail";
        exit;
    }

    $result = sql_query(" insert into kmp_pilot_lecture_complet set lecture_idx = '{$lecture_idx}', edu_idx = '{$edu_idx}', apply_idx = '{$apply_idx}', mb_id='{$member['mb_id']}', mb_name='{$member['mb_name']}', complet_date = now() ");

    if($result){
        /* 교육 동영상을 다 봤다면 수강 완료 시키기*/
        //교육에 등록된 동영상 갯수 구하기
        $sql_cnt = " select count(*) as cnt from kmp_pilot_lecture_list where lecture_del_type = 'N' and edu_idx = '{$edu_idx}' ";
        $row_cnt = sql_fetch($sql_cnt);
        $total_count = $row_cnt['cnt'];

        //내가 본 동영상의 갯수(이수한 동영상)
        $view_movie_cnt = sql_fetch(" select count(*) view_cnt from kmp_pilot_lecture_complet where edu_idx = '{$edu_idx}' and apply_idx = '{$apply_idx}' and mb_id = '{$member['mb_id']}' ");
        $view_movie_count = $view_movie_cnt['view_cnt'];

        if($total_count == $view_movie_count){
            //DB 에 완료 저장
            $result_up = sql_query(" update kmp_pilot_edu_apply set lecture_completion_date = now(), lecture_completion_status = 'Y' where apply_idx = '{$apply_idx}' and mb_id='{$member['mb_id']}' ");

            //수료증 번호 만들기(순번은 당해년도 교육의 총인원 순번으로 01부터 시작 1차 교육생 10명, 2차 교육생 10명, 3차 교육생 20명 이면 총 01~40까지 발급)
            $certificate_max = sql_fetch(" select max(certificate_num) as certificate_cnt from kmp_pilot_edu_apply where edu_type = '{$edu_type}' and apply_cancel = 'N' and lecture_completion_status = 'Y' and lecture_completion_date like '%{$now_year}%' order by mb_name asc ");
            $certificate_cnt = $certificate_max['certificate_cnt'];
            $certificate_cnt = $certificate_cnt + 1;

            //수료증 업뎃
            $result_cert_up = sql_query(" update kmp_pilot_edu_apply set certificate_num = '{$certificate_cnt}' where apply_idx = '{$apply_idx}' and mb_id='{$member['mb_id']}' ");
        }
        /* 교육 동영상을 확인 했는지 확인 하기 끝*/

        echo "OK";
    }else{
        echo "fail";
    }
}
?>