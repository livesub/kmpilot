<?php
$sub_menu = "100200";
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$au_menu = isset($_POST['au_menu']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['au_menu']) : '';
$post_r = isset($_POST['r']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['r']) : '';
$post_w = isset($_POST['w']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['w']) : '';
$post_d = isset($_POST['d']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['d']) : '';

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

if($_POST['mb_id'] == '' && $_POST['mb_group'] == ''){
    alert('회원 아이디 또는 그룹 중 하나는 꼭 입력해주세요');
}

$check = array();
if(isset($_POST['mb_id']) &&$_POST['mb_id'] != ''){
$mb = get_member($mb_id);
    if (!$mb['mb_id']){
        alert('존재하는 회원아이디가 아닙니다.');
    }
}
$group_id = null;
if(isset($_POST['mb_group']) && $_POST['mb_group'] != ''){
    $group_sel = "select * from {$g5['group_table']} where gr_id ='".$_POST['mb_group']."'";
    $result = sql_fetch($group_sel);
    if($result)
        $group_id = $result['gr_id'];
    if (!$group_id){
        alert('존재하는 그룹이 아닙니다.');
    }
}

if($mb_id != null && $group_id != null){
    if(!get_member_group_check($mb_id,$group_id)){
        alert('일치하는 회원이 없습니다.');
    }else{
        $check['3'] = 3;
    }
}

check_admin_token();

//include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
//
//if (!chk_captcha()) {
//    alert('자동등록방지 숫자가 틀렸습니다.');
//}
if($mb_id != null && $group_id == null) {
    $sql = " insert into {$g5['auth_table']}
            set mb_id   = '$mb_id',
                au_menu = '$au_menu',
                au_auth = '{$post_r},{$post_w},{$post_d}' ";
    $result = sql_query($sql, FALSE);
    if (!$result) {
        $sql1 = " update {$g5['auth_table']}
                set au_auth = '{$post_r},{$post_w},{$post_d}'
              where mb_id   = '$mb_id'
                and au_menu = '$au_menu' ";
        sql_query($sql1);
    }
}elseif($mb_id == null && $group_id != null) {
    $sql_group_sel = " select mb_id from {$g5['group_member_table']} where gr_id = '".$group_id."'";
    $result = sql_query($sql_group_sel);
    //$rel =sql_num_rows($result);
    $count = null;
    $re_mb_id = null;
    //alert('들어오기는 하니?  '.$rel);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        //$row=sql_fetch_array($result);
        //$re_mb_id = null;
        $sql_in_auth = null;
        $re_mb_id = $row['mb_id'];
        $sql_in_auth = " insert into {$g5['auth_table']}
                set mb_id   = '$re_mb_id',
                    au_menu = '$au_menu',
                    au_auth = '{$post_r},{$post_w},{$post_d}' ";
        $result_in = sql_query($sql_in_auth);
        if($result_in){$count++; $id = $re_mb_id;}
        if (!$result_in) {
            $sql_up_auth = " update {$g5['auth_table']}
                    set au_auth = '{$post_r},{$post_w},{$post_d}'
                  where mb_id   = '$re_mb_id'
                    and au_menu = '$au_menu' ";
            sql_query($sql_up_auth);
        }
    }
    //alert($i."번째에 탈출"."아이디 명:".$re_mb_id);
}elseif ($check['3'] == 3){
    if(get_member_group_check($mb_id, $group_id)){
        $sql_3_in = " insert into {$g5['auth_table']}
                set mb_id   = '$mb_id',
                    au_menu = '$au_menu',
                    au_auth = '{$post_r},{$post_w},{$post_d}' ";
        $result_3_in = sql_query($sql_3_in, FALSE);
        if (!$result_3_in) {
            $sql_3_up = " update {$g5['auth_table']}
                    set au_auth = '{$post_r},{$post_w},{$post_d}'
                  where mb_id   = '$mb_id'
                    and au_menu = '$au_menu' ";
            sql_query($sql_3_up);
        }
    }
}
//sql_query(" OPTIMIZE TABLE `$g5['auth_table']` ");

// 세션을 체크하여 하루에 한번만 메일알림이 가게 합니다.
//if( str_replace('-', '', G5_TIME_YMD) !== get_session('adm_auth_update') ){
//    $site_url = preg_replace('/^www\./', '', strtolower($_SERVER['SERVER_NAME']));
//    $to_email = 'gnuboard@'.$site_url;
//
//    mailer($config['cf_admin_email_name'], $to_email, $config['cf_admin_email'], '['.$config['cf_title'].'] 관리권한설정 알림', '<p><b>['.$config['cf_title'].'] 관리권한설정 변경 안내</b></p><p style="padding-top:1em">회원 아이디 '.$mb['mb_id'].' 에 관리권한이 추가 되었습니다.</p><p style="padding-top:1em">'.G5_TIME_YMDHIS.'</p><p style="padding-top:1em"><a href="'.G5_URL.'" target="_blank">'.$config['cf_title'].'</a></p>', 1);
//
//    set_session('adm_auth_update', str_replace('-', '', G5_TIME_YMD));
//}

run_event('adm_auth_update', $mb);

goto_url('./auth_list.php?'.$qstr);