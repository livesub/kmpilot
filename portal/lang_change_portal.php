<?php
    $lang_change = $_POST['lang_type'];
    setcookie('lang_change_portal', $lang_change, time() + 86400 * 1);  //일단 1일 잡아둠
    echo "OK";
?>
