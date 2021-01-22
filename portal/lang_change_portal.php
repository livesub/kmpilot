<?php
    $lang_change = $_POST['lang_type'];
    setcookie('lang_change_portal', $lang_change, time() + 86400 * 7);  //일단 7일 잡아둠
    echo "OK";
?>
