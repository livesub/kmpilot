<?php
$sub_menu = "200100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if ($w == 'u')
    check_demo();

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
$mb_certify_case = isset($_POST['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify_case']) : '';
$mb_certify = isset($_POST['mb_certify']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify']) : '';
$mb_zip = isset($_POST['mb_zip']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_zip']) : '';

// 휴대폰번호 체크
$mb_hp = hyphen_hp_number($_POST['mb_hp']);
if($mb_hp) {
    $result = exist_mb_hp($mb_hp, $mb_id);
    if ($result)
        alert($result);
}

// 인증정보처리
if($mb_certify_case && $mb_certify) {
    $mb_certify = isset($_POST['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify_case']) : '';
    $mb_adult = isset($_POST['mb_adult']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_adult']) : '';
} else {
    $mb_certify = '';
    $mb_adult = 0;
}

$mb_zip1 = substr($mb_zip, 0, 3);
$mb_zip2 = substr($mb_zip, 3);

$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';
//$mb_nick = isset($_POST['mb_nick']) ? trim(strip_tags($_POST['mb_nick'])) : '';

//if ($msg = valid_mb_nick($mb_nick))     alert($msg, "", true, true);


$posts = array();
$check_keys = array(
    'mb_name',
    'mb_birth',
    'mb_tel',
    'mb_addr1',
    'mb_addr2',
    'mb_addr3',
    'mb_addr_jibeon',
    'mb_signature',
    'mb_leave_date',
    'mb_intercept_date',
    'mb_mailling',
    'mb_sms',
    'mb_open',
    'mb_profile',
    'mb_level',
    'mb_sex',
    'mb_doseongu',
    'mb_lead_code',
    'mb_license_mean',
    'mb_first_license_day',
    'mb_license_renewal_day',
    'mb_validity_day_from',
    'mb_validity_day_to',
    'mb_license_ext_day_from',
    'mb_license_ext_day_to',
    'mb_applicable_or_not',
    'mb_punishment',
    'mb_group',
);

for($i=1;$i<=10;$i++){
    $check_keys[] = 'mb_'.$i;
}

foreach( $check_keys as $key ){
    $posts[$key] = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1) : '';
}

$mb_memo = isset($_POST['mb_memo']) ? $_POST['mb_memo'] : '';

//텍스트 값을 date 값으로 변환
//$mb_birth = change_text_to_date($posts['mb_birth']);

$sql_common = "  mb_name = '{$posts['mb_name']}',
                 mb_email = '{$mb_email}',
                 mb_tel = '{$posts['mb_tel']}',
                 mb_hp = '{$mb_hp}',
                 mb_certify = '{$mb_certify}',
                 mb_birth = '{$posts['mb_birth']}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$posts['mb_addr1']}',
                 mb_addr2 = '{$posts['mb_addr2']}',
                 mb_addr3 = '{$posts['mb_addr3']}',
                 mb_addr_jibeon = '{$posts['mb_addr_jibeon']}',
                 mb_signature = '{$posts['mb_signature']}',
                 mb_leave_date = '{$posts['mb_leave_date']}',
                 mb_intercept_date='{$posts['mb_intercept_date']}',
                 mb_memo = '{$mb_memo}',
                 mb_mailling = '1',
                 mb_sms = '{$posts['mb_sms']}',
                 mb_open = '{$posts['mb_open']}',
                 mb_profile = '{$posts['mb_profile']}',
                 mb_level = '{$posts['mb_level']}',
                 mb_sex = '{$posts['mb_sex']}',
                 mb_doseongu = '{$posts['mb_doseongu']}',
                 mb_lead_code = '{$posts['mb_lead_code']}',
                 mb_license_mean = '{$posts['mb_license_mean']}',
                 mb_first_license_day = '{$posts['mb_first_license_day']}',
                 mb_license_renewal_day = '{$posts['mb_license_renewal_day']}',
                 mb_validity_day_from = '{$posts['mb_validity_day_from']}',
                 mb_validity_day_to = '{$posts['mb_validity_day_to']}',
                 mb_license_ext_day_from = '{$posts['mb_license_ext_day_from']}',
                 mb_license_ext_day_to = '{$posts['mb_license_ext_day_to']}',
                 mb_applicable_or_not = '{$posts['mb_applicable_or_not']}',
                 mb_punishment = '{$posts['mb_punishment']}' ";
$mb_group = $posts['mb_group'];

if ($w == '')
{
    $mb = get_member($mb_id);
    if (isset($mb['mb_id']) && $mb['mb_id'])
        alert('이미 존재하는 회원아이디입니다.\\nＩＤ : '.$mb['mb_id'].'\\n이름 : '.$mb['mb_name'].'\\n메일 : '.$mb['mb_email']);

    // 닉네임중복체크
//    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' ";
//    $row = sql_fetch($sql);
//    if (isset($row['mb_id']) && $row['mb_id'])
//        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id'])
        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n메일 : '.$row['mb_email']);

    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '".get_encrypt_string($mb_password)."', mb_datetime = '".G5_TIME_YMDHIS."', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '".G5_TIME_YMDHIS."', {$sql_common} ");
    //권한 중복 체크
    $sql_group_select = " select * from {$g5['group_member_table']} where gr_id = '{$mb_group}' and mb_id ='{$mb_id}' ";
    $row = sql_fetch($sql_group_select);
    if (isset($row['mb_id']) && $row['mb_id'])
        alert('똑같은 권한을 가진 아이디가 있습니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n메일 : '.$row['mb_email']);

    $sql_group_insert = " insert into {$g5['group_member_table']} set gr_id = '{$mb_group}', mb_id = '{$mb_id}', gm_datetime = '".G5_TIME_YMDHIS."'";
    sql_query($sql_group_insert);
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (! (isset($mb['mb_id']) && $mb['mb_id']))
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    if ($is_admin !== 'super' && is_admin($mb['mb_id']) === 'super' ) {
        alert('최고관리자의 비밀번호를 수정할수 없습니다.');
    }

    if ($mb_id === $member['mb_id'] && $_POST['mb_level'] != $mb['mb_level'])
        alert($mb['mb_id'].' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');

    // 닉네임중복체크
//    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' and mb_id <> '$mb_id' ";
//    $row = sql_fetch($sql);
//    if (isset($row['mb_id']) && $row['mb_id'])
//        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id'])
        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n메일 : '.$row['mb_email']);

    if ($mb_password)
        $sql_password = " , mb_password = '".get_encrypt_string($mb_password)."' ";
    else
        $sql_password = "";

    if (isset($passive_certify) && $passive_certify)
        $sql_certify = " , mb_email_certify = '".G5_TIME_YMDHIS."' ";
    else
        $sql_certify = "";

    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

if( $w == '' || $w == 'u' ){

    $mb_dir = substr($mb_id,0,2);
    $mb_license_img = get_mb_icon_name($mb_id).'.gif';

    // 회원 아이콘 삭제
//    if (isset($del_mb_icon) && $del_mb_icon)
//        @unlink(G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_license_img);

    $image_regex = "/(\.(gif|jpe?g|png))$/i";

    // 아이콘 업로드
//    if (isset($_FILES['mb_icon']) && is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
//        if (!preg_match($image_regex, $_FILES['mb_icon']['name'])) {
//            alert($_FILES['mb_icon']['name'] . '은(는) 이미지 파일이 아닙니다.');
//        }
//
//        if (preg_match($image_regex, $_FILES['mb_icon']['name'])) {
//            $mb_icon_dir = G5_DATA_PATH.'/member/'.$mb_dir;
//            @mkdir($mb_icon_dir, G5_DIR_PERMISSION);
//            @chmod($mb_icon_dir, G5_DIR_PERMISSION);
//
//            $dest_path = $mb_icon_dir.'/'.$mb_license_img;
//
//            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
//            chmod($dest_path, G5_FILE_PERMISSION);
//
//            if (file_exists($dest_path)) {
//                $size = @getimagesize($dest_path);
//                if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
//                    $thumb = null;
//                    if($size[2] === 2 || $size[2] === 3) {
//                        //jpg 또는 png 파일 적용
//                        $thumb = thumbnail($mb_license_img, $mb_icon_dir, $mb_icon_dir, $config['cf_member_icon_width'], $config['cf_member_icon_height'], true, true);
//                        if($thumb) {
//                            @unlink($dest_path);
//                            rename($mb_icon_dir.'/'.$thumb, $dest_path);
//                        }
//                    }
//                    if( !$thumb ){
//                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
//                        @unlink($dest_path);
//                    }
//                }
//            }
//        }
//    }

    $mb_img_dir = G5_DATA_PATH.'/member_image/';
    if( !is_dir($mb_img_dir) ){
        @mkdir($mb_img_dir, G5_DIR_PERMISSION);
        @chmod($mb_img_dir, G5_DIR_PERMISSION);
    }
    $mb_img_dir .= substr($mb_id,0,2);

    // 회원 이미지 삭제
    if (isset($del_mb_img) && $del_mb_img){
        @unlink($mb_img_dir.'/'.$mb_license_img);
        //폴더도 같이 삭제
        if(is_dir($mb_img_dir))
            @rmdir($mb_img_dir);
    }
    // 회원 이미지 업로드
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_img']['name'])) {
            alert($_FILES['mb_img']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            @mkdir($mb_img_dir, G5_DIR_PERMISSION);
            @chmod($mb_img_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_img_dir.'/'.$mb_license_img;

            move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_license_img, $mb_img_dir, $mb_img_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_img_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
    //최신 면허 사본 저장
    $mb_license_dir = G5_DATA_PATH.'/member_license/';
    if( !is_dir($mb_license_dir) ){
        @mkdir($mb_license_dir, G5_DIR_PERMISSION);
        @chmod($mb_license_dir, G5_DIR_PERMISSION);
    }
    $mb_license_dir .= substr($mb_id,0,2);

    // 면허사본 삭제
    if (isset($del_mb_license) && $del_mb_license){
        @unlink($mb_license_dir.'/'.$mb_license_img);
        //폴더도 같이 삭제
        if(is_dir($mb_license_dir))
        @rmdir($mb_license_dir);
    }
    // 아이콘 업로드
    if (isset($_FILES['mb_license']) && is_uploaded_file($_FILES['mb_license']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_license']['name'])) {
            alert($_FILES['mb_license']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_license']['name'])) {
            @mkdir($mb_license_dir, G5_DIR_PERMISSION);
            @chmod($mb_license_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_license_dir.'/'.$mb_license_img;

            move_uploaded_file($_FILES['mb_license']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_license_img, $mb_license_dir, $mb_license_dir, $config['cf_member_icon_width'], $config['cf_member_icon_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_license_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
}

run_event('admin_member_form_update', $w, $mb_id);

goto_url('./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id, false);
//goto_url('./member_list.php', false);