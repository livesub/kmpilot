<?php
include_once('../common.php');
include_once(PORTAL_DATA_PATH."/dbconfig.php");

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    echo "no_member";
    exit;
}else{
    $edu_idx = $_POST['edu_idx'];
    $edu_type = $_POST['edu_type'];
    $button_type = $_POST['button_type'];
    $apply_idx = $_POST['apply_idx'];

    if($button_type == "apply"){
        //정원이 다 찼는지 파악
        //정원 값 추출
        $row = sql_fetch(" select edu_person from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' ");
        $edu_person = $row['edu_person'];   //교육인원 값

        //현재 신청 인원 구하기
        $row_cnt = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}'  and apply_cancel = 'N' ");
        $apply_count = $row_cnt['apply_cnt'];

        if($edu_person <= $apply_count){
            echo "full";
            exit;
        }

        //이미 교육 신청을 했는지 파악
        $row_status = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where mb_id='{$member['mb_id']}' and edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}'  and apply_cancel = 'N' ");
        $apply_count = $row_status['apply_cnt'];
        if($apply_count != 0){
            echo "apply_ok_status";
            exit;
        }else{
            $result = sql_query(" insert into kmp_pilot_edu_apply set edu_idx='{$edu_idx}', edu_type='{$edu_type}', mb_id='{$member['mb_id']}', mb_name='{$member['mb_name']}', apply_date = now() ");

            if($result){
                echo "OK";
                exit;
            }else{
                echo "fail";
                exit;
            }
        }
    }else{
        //취소시
        $result_up = sql_query(" update kmp_pilot_edu_apply set apply_cancel = 'Y', apply_cancel_date = now() where apply_idx = '{$apply_idx}' and edu_idx='{$edu_idx}' and edu_type='{$edu_type}' and mb_id='{$member['mb_id']}' ");

        if($result_up){
            //신청자가 취소 하였거나 해서 정원이 변경 되었을 경우
            $result_admin_on = sql_query(" update kmp_pilot_edu_list set edu_receipt_status='I' where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' ");

            echo "OK";
            exit;
        }else{
            echo "fail";
            exit;
        }
    }
}
?>