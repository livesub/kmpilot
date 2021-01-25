<?php
$sub_menu = "100200";
include_once('./_common.php');

check_demo();

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();

$count = (isset($_POST['chk']) && is_array($_POST['chk'])) ? count($_POST['chk']) : 0;
$post_act_button = isset($_POST['act_button']) ? clean_xss_tags($_POST['act_button'], 1, 1) : '';
//alert('몇개 삭제?  '.$post_act_button);
if (!$count)
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");

if ( (isset($_POST['mb_id']) && ! is_array($_POST['mb_id'])) || (isset($_POST['mb_applicable_or_not']) && ! is_array($_POST['mb_applicable_or_not']))
    || (isset($_POST['mb_punishment']) && ! is_array($_POST['mb_punishment'])) || (isset($_POST['mb_punishment_date']) && ! is_array($_POST['mb_punishment_date']))){
    alert("잘못된 요청입니다.");
}

for ($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
    
    $mb_id = isset($_POST['mb_id'][$k]) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['mb_id'][$k]) : '';
    $mb_applicable_or_not = isset($_POST['mb_applicable_or_not'][$k]) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['mb_applicable_or_not'][$k]) : '';
    $mb_punishment = isset($_POST['mb_punishment'][$k]) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['mb_punishment'][$k]) : '';
    $mb_punishment_date = isset($_POST['mb_punishment_date'][$k]) ? preg_replace('/(19|20)\\d{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])/', '', $_POST['mb_punishment_date'][$k]) : '';
    //alert('날짜 제대로 넘어오나?  '.$mb_punishment_date);
    //alert('아이디! : '.$mb_id.' applicable : '.$mb_applicable_or_not.' 징계사항 '.$mb_punishment);
    $sql_del_punish = " delete from {$g5['member_punishment']} where mb_id = '".$mb_id."' and mb_applicable_or_not = '".$mb_applicable_or_not."' and mb_punishment ='".$mb_punishment."' and mb_punishment_date='".$mb_punishment_date."'";
    //alert('del 문구 확인 :'.$sql_del_punish);
    $result_del_punishment = sql_query($sql_del_punish);
    if(!$result_del_punishment){
        alert('징계정보 삭제 오류 !');
    }

    run_event('adm_delete_member_punishment', $mb_id, $mb_applicable_or_not, $mb_punishment);
}

//goto_url('./auth_list.php?'.$qstr);
goto_url('./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id, false);