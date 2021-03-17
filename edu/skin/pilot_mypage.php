<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
//if($is_member <> 1){
//    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
//    exit;
//}

//회원 정보 찾기
echo "작업 진행중~~~<br>";
echo $_SERVER['DOCUMENT_ROOT'].'/data/session';
echo $_SESSION['ss_mb_id'];
?>