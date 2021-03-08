<?php
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

//alert('들어는 오나?');

check_admin_token();

$mb_id = isset($_POST['member_id']) ? trim($_POST['member_id']) : '';

//$posts = array();
//$check_keys = array(
//    'mb_punishment_memo',
//    'mb_punishment',
//    'mb_punishment_date'
//);
//
//for($i=0;$i<=count($check_keys);$i++){
//    $check_keys[] = 'mb_'.$i;
//}
//
//foreach( $check_keys as $key ){
//    $posts[$key] = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1) : '';
//}

//징계사항 중복 확인
$sql_punish_select_in = " select * from {$g5['member_punishment']} where mb_id = '{$mb_id}' and mb_punishment_memo='{$_POST['mb_punishment_memo']}' and mb_punishment='{$_POST['mb_punishment']}' and mb_punishment_date='{$_POST['mb_punishment_date']}'";
$row_sel_punish = sql_fetch($sql_punish_select_in);
//alert('성공 or 실패 '.$sql_punish_select);
if (isset($row_sel_punish['mb_id']) && $row_sel_punish['mb_id']){
    alert('이미 존재하는 징계사항입니다.\\nＩＤ : '.$row_sel_punish['mb_id'].'\\n해심재결 해당여부 : '.$row_sel_punish['mb_punishment_memo'].'\\n징계사항 : '.$row_sel_punish['mb_punishment'].'\\n징계 선고일: '.$row_sel_punish['mb_punishment_date']);
}
//징계사항은 수정이 없기 때문에 둘다 일 경우에도 진행되어야 한다.( 단 값이 있을 경우 에만)
if(isset($_POST['mb_punishment_memo']) && $_POST['mb_punishment_memo'] != '' && isset($_POST['mb_punishment']) && $_POST['mb_punishment'] && isset($_POST['mb_punishment_date']) && $_POST['mb_punishment_date']){
    $sql_punish = " insert into {$g5['member_punishment']} set mb_id = '{$mb_id}', mb_punishment_memo='{$_POST['mb_punishment_memo']}', mb_punishment='{$_POST['mb_punishment']}', mb_punishment_date='{$_POST['mb_punishment_date']}'";
    $result_sql_punish = sql_query($sql_punish);
    if(!$result_sql_punish){
        alert('징계사항 정보 등록을 실패했습니다. 잠시 후에 시도해 주세요');
    }
}
goto_url('./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id, false);