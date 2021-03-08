<?php
$sub_menu = "400200";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if ($w == 'u')
    check_demo();

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$subject = $_POST['subject'];
$startdatetime = $_POST['startdatetime'];
$enddatetime = $_POST['enddatetime'];
$youtube = $_POST['youtube'];
$w = $_POST['w'];
$idx = $_POST['idx'];

if ($w == '')
{
    $result = sql_query(" insert into {$g5['pilot_mending_table']} set writer_name='{$member['mb_name']}', subject = '{$subject}', startdatetime = '{$startdatetime}', enddatetime = '{$enddatetime}', youtube = '{$youtube}',regi_date = now() ");
    alert('정상적으로 저장 되었습니다');
}else if ($w == 'u'){
    $sql = " update {$g5['pilot_mending_table']} set
                writer_name='{$member['mb_name']}',
                subject = '{$subject}',
                startdatetime = '{$startdatetime}',
                enddatetime = '{$enddatetime}',
                youtube = '{$youtube}'
                where idx = '{$idx}' ";
    sql_query($sql);
    alert('정상적으로 수정 되었습니다');
}

goto_url('pilot_mending_list.php');