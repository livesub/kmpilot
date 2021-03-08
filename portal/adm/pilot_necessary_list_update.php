<?php
$sub_menu = "400300";
include_once('./_common.php');

check_demo();

if (! (isset($_POST['chk']) && is_array($_POST['chk']))) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$renewal_datas = array();
$msg = '';

if ($_POST['act_button'] == "선택수정") {
} else if ($_POST['act_button'] == "선택삭제") {
    $count = count($_POST['chk']);

    if(!$count)
    alert('삭제할 메일목록을 1개이상 선택해 주세요.');

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $sql = " delete from {$g5['pilot_necessary_table']} where idx = '$k' ";
        sql_query($sql);
    }

    alert('정상 삭제 되었습니다.');
}

goto_url('./pilot_necessary_list.php');