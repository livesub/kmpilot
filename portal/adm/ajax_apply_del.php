<?php
include_once('./_common.php');

$w = $_POST['w'];
$edu_idx = $_POST['app_edu_idx'];

if($w == "d"){
    $count = count($_POST['chk']);

    if(!$count) {
        echo "no_idx";
        exit;
    }

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        $sql = " update kmp_pilot_edu_apply set apply_cancel = 'Y', apply_cancel_date = now() where apply_idx = '$k' ";
        sql_query($sql);
    }
    echo "ok";
}
?>