<?php
$sub_menu = "100050";
include_once('./_common.php');

check_demo();

if (! (isset($_POST['chk']) && is_array($_POST['chk']))) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$mb_datas = array();
$msg = '';


if ($_POST['act_button'] == "선택수정") {
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        $turn = 0;
        $type = "";
        $idx = 0;

        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $turn = $_POST['turn'][$k];
        $type = $_POST['type'][$k];
        $idx = $_POST['idx'][$k];

        $sql = " update {$g5['main_image_table']} set
            turn = '{$turn}',
            type = '{$type}'
            where idx = '{$idx}' ";
        sql_query($sql);
    }
    $ment = "수정";
} else if ($_POST['act_button'] == "선택삭제") {
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        $idx = 0;
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $idx = $_POST['idx'][$k];

        $image_name = $_POST['image_name'][$k];

        $delete = @unlink(G5_DATA_PATH."/main_image/".$image_name);
        if($delete){
            sql_query(" delete from {$g5['main_image_table']} where idx = '{$idx}' ");
        }
    }
    $ment = "삭제";
}

alert("정상적으로 {$ment} 되었습니다.","./main_image_list.php");
?>