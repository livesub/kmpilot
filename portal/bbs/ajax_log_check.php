<?php
include_once('../common.php');
include_once(G5_DATA_PATH."/dbconfig.php");

$chk_type = $_POST['chk_type'];
$mb_id = $_POST['mb_id'];
$mb_password = $_POST['mb_password'];

if($chk_type == "in"){
    if($mb_id == "" || $mb_password == ""){
        echo "fatal";
        exit;
    }

    $mb = get_member($mb_id);

    if (! (isset($mb['mb_id']) && $mb['mb_id']) || !login_password_check($mb, $mb_password, $mb['mb_password'])) {

        run_event('password_is_wrong', 'login', $mb);
        echo "member_no";
        exit;
    }

    // 차단된 아이디인가?
    if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
        echo "member_access_no";
        exit;
    }

    // 탈퇴한 아이디인가?
    if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
        echo "member_secession";
        exit;
    }

    // 회원아이디 세션 생성
    set_session('ss_mb_id', $mb['mb_id']);
    // FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
    set_session('ss_mb_key', md5($mb['mb_datetime'] . get_real_client_ip() . $_SERVER['HTTP_USER_AGENT']));

    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);

    // 관리자로 로그인시 DATA 폴더의 쓰기 권한이 있는지 체크합니다. 쓰기 권한이 없으면 로그인을 못합니다.
    if( is_admin($mb['mb_id']) && is_dir(G5_DATA_PATH.'/tmp/') ){
        $tmp_data_file = G5_DATA_PATH.'/tmp/tmp-write-test-'.time();
        $tmp_data_check = @fopen($tmp_data_file, 'w');
        if($tmp_data_check){
            if(! @fwrite($tmp_data_check, G5_URL)){
                $tmp_data_check = false;
            }
        }
        @fclose($tmp_data_check);
        @unlink($tmp_data_file);

        if(! $tmp_data_check){
            echo "fatal";
            exit;
        }
    }
    echo "OK";
}else{
    session_unset(); // 모든 세션변수를 언레지스터 시켜줌
    session_destroy(); // 세션해제함

    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
    run_event('member_logout', $link);
    echo "OK";
}
?>