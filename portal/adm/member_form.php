<?php
$sub_menu = "200100";
include_once('./_common.php');


auth_check_menu($auth, $sub_menu, 'w');
$colspan = 6;
$mb = array(
'mb_certify' => null,
//'mb_adult' => null,
'mb_sms' => null,
'mb_intercept_date' => null,
'mb_id' => null,
'mb_name' => null,
//'mb_nick' => null,
'mb_point' => null,
'mb_email' => null,
//'mb_homepage' => null,
'mb_hp' => null,
'mb_tel' => null,
'mb_zip1' => null,
'mb_zip2' => null,
'mb_addr1' => null,
'mb_addr2' => null,
'mb_addr3' => null,
'mb_addr_jibeon' => null,
'mb_signature' => null,
'mb_profile' => null,
'mb_memo' => null,
'mb_leave_date' => null,
'mb_doseongu' => null,
'mb_lead_code' => null,
'mb_license_mean' => null,
'mb_first_license_day' => null,
'mb_license_renewal_day' => null,
'mb_validity_day_from' => null,
'mb_validity_day_to' => null,
'mb_license_ext_day_from' => null,
'mb_license_ext_day_to' => null,
'mb_applicate_or_not' => null,
'mb_punishment' => null,
);

$sound_only = '';
$required_mb_id_class = '';
$required_mb_password = '';

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'] && $mb['mb_id'] != $member['mb_id'])
        alert($mb['mb_id'].' : 본인이 아니거나 자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

//    if($is_admin != 'super' && $mb['mb_id'] != $member['mb_id']){
//        alert('본인이 아닌경우 수정할 수 없습니다.');
//    }

    if($mb['mb_level'] > $member['mb_level'])
        alert('자신보다 권한이 높은회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    //$mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
//    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_doseongu'] = get_text($mb['mb_doseongu']);
    $mb['mb_lead_code'] = get_text($mb['mb_lead_code']);
    $mb['mb_license_mean'] = get_text($mb['mb_license_mean']);
    $mb['mb_first_license_day'] = get_text($mb['mb_first_license_day']);
    $mb['mb_license_renewal_day'] = get_text($mb['mb_license_renewal_day']);
    $mb['mb_validity_day_from'] = get_text($mb['mb_validity_day_from']);
    $mb['mb_validity_day_to'] = get_text($mb['mb_validity_day_to']);
    $mb['mb_license_ext_day_from'] = get_text($mb['mb_license_ext_day_from']);
    $mb['mb_license_ext_day_to'] = get_text($mb['mb_license_ext_day_to']);
    $mb['mb_applicate_or_not'] = get_text($mb['mb_applicate_or_not']);
    $mb['mb_punishment'] = get_text($mb['mb_punishment']);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

if(isset($mb['mb_adult'])) {
    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
}

// 지번주소 필드추가
if(!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if(!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if(!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

// 이메일인증 체크 필드추가
if(!isset($mb['mb_email_certify2'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_email_certify2` varchar(255) NOT NULL DEFAULT '' AFTER `mb_email_certify` ", false);
}

if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '회원 '.$html_title;
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

//회원관리를 할 수 있는 사무장일 경우에 바꿀 수 있는 부분만 바뀌게 변수 선언
$sql_sel_auth = " select * from {$g5['auth_table']} where mb_id = '{$member['mb_id']}'";
$result_auth = sql_fetch($sql_sel_auth);
$smj_only = '';
if($result_auth['au_menu']){
    $smj_only = 'readonly disabled';
}

//교육신청현황을 찾는 쿼리
$mb_edu_list = array();
$sql_edu_apply_list = " SELECT edu_type_name,apply_date, lecture_completion_status FROM kmp_pilot_edu_list a inner JOIN kmp_pilot_edu_apply b ON a.edu_idx = b.edu_idx where mb_id = '{$mb['mb_id']}' order by apply_date";
$row_edu_apply_list = sql_query($sql_edu_apply_list);
if($row_edu_apply_list){
    for($s = 0; $s < $row_edu = sql_fetch_array($row_edu_apply_list); $s++){
        $mb_edu_list[$s]['edu_type_name'] = $row_edu['edu_type_name'];
        $mb_edu_list[$s]['apply_date'] = $row_edu['apply_date'];
        $mb_edu_list[$s]['lecture_completion_status'] = $row_edu['lecture_completion_status'];
    }
}
?>
    <style>
        #my_modal_member {
            display: none;
            width: 400px;
            /*height: 200px;*/
            /*padding: 20px 60px;*/
            background-color: #dde4e9;
            border: 1px solid #888;
            border-radius: 3px;
        }
        #my_modal_member .modal_close_btn {
            position: absolute;
            top: 5px;
            right: 5px;
            -webkit-text-fill-color: #0f192a;
        }
        html.modal-open {
            overflow-y: hidden;
        }
    </style>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>



    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="mb_id">아이디<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15"  minlength="2" maxlength="20">
            <?php if ($w!='u') {?><label for="mb_group">그룹설정</label><?php echo get_group_select("mb_group", '', '', 'no'); }?>
            <?php if ($w=='u'){ ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>" class="btn_frmline">접근가능그룹보기</a><?php } ?>
        </td>
        <th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?><?php if ($w=='u'){ ?>
                    <p>미 입력시 원래 비밀번호로 저장 <br> (회원복구 시 재입력이 필요)</p>
                <?php } ?></label></th>
        <td><input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> <?=$smj_only?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" minlength="6" autocomplete="off"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_img">회원이미지</label></th>
        <td colspan="3">
            <?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_img_width'].'픽셀 높이 '.$config['cf_member_img_height'].'픽셀</strong>로 해주세요.') ?>
            <input type="file" name="mb_img" id="mb_img">
            <?php
            $mb_dir = substr($mb['mb_id'],0,2);
            $icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
            if (file_exists($icon_file)) {
                echo get_member_profile_img($mb['mb_id'],'','','profile_image','','image');
                echo '<input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_name">이름(실명)<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required <?=$smj_only?> class="required frm_input" size="15"  maxlength="10"></td>
<!--        <th scope="row"><label for="mb_nick">닉네임<strong class="sound_only">필수</strong></label></th>-->
<!--        <td><input type="text" name="mb_nick" value="--><?php //echo $mb['mb_nick'] ?><!--" id="mb_nick" required class="required frm_input" size="15"  maxlength="20"></td>-->
        <th scope="row"><label for="mb_level">회원 권한</label><strong class="sound_only">필수</strong></th>
        <td><?php echo get_member_level_select('mb_level', 2, $member['mb_level'], $mb['mb_level']) ?></td>
    </tr>
    <tr>
<!--        <th scope="row">포인트</th>-->
<!--        <td><a href="./point_list.php?sfl=mb_id&amp;stx=--><?php //echo $mb['mb_id'] ?><!--" target="_blank">--><?php //echo number_format($mb['mb_point']) ?><!--</a> 점</td>-->
    </tr>
    <tr>
        <th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" <?=$smj_only?> id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
<!--        <th scope="row"><label for="mb_homepage">홈페이지</label></th>-->
<!--        <td><input type="text" name="mb_homepage" value="--><?php //echo $mb['mb_homepage'] ?><!--" id="mb_homepage" class="frm_input" maxlength="255" size="15"></td>-->
    </tr>
    <tr>
        <th scope="row"><label for="mb_hp">휴대폰번호<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" required class="required frm_input" size="15" maxlength="13" <?=$smj_only?>></td>
        <th scope="row"><label for="mb_tel">전화번호</label></th>
        <td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="13" <?=$smj_only?>></td>
    </tr>
    <tr>
        <th>주소</th>
        <?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php }  ?>
        <lable for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></lable>
        <td><input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'].$mb['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input twopart_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="50" maxlength="6"  placeholder="우편번호">
        <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button></td>
    </tr>
    <tr>
        <th></th>
        <td><input type="text" name="mb_addr1" value="<?php echo get_text($mb['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address full_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="50"  placeholder="기본주소">
        <label for="reg_mb_addr1" class="sound_only">기본주소<?php echo $config['cf_req_addr']?'<strong> 필수</strong>':''; ?></label><br></td>
    </tr>
    <tr>
        <th></th>
        <td>
        <input type="text" name="mb_addr2" value="<?php echo get_text($mb['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address full_input" size="50" placeholder="상세주소">
        <label for="reg_mb_addr2" class="sound_only">상세주소</label>
        </td>
    </tr>
    <tr>
        <th></th>
        <td>
            <input type="text" name="mb_addr3" value="<?php echo get_text($mb['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address full_input" size="50" readonly="readonly" placeholder="참고항목">
            <label for="reg_mb_addr3" class="sound_only">참고항목</label>
            <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($mb['mb_addr_jibeon']); ?>">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_doseongu">도선구</label></th>
        <td><?php echo get_doseongu_select('mb_doseongu', 0, 12, $mb['mb_doseongu'], $smj_only) ?></td>
        <th scope="row"><label for="mb_lead_code">도선약호</label></th>
        <td><input type="text" name="mb_lead_code" value="<?php echo $mb['mb_lead_code'] ?>" id="mb_lead_code" class="frm_input" size="15" maxlength="20"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_sex">성별</label></th>
        <td><?php echo get_member_sex_select('mb_sex', 1, 2, $mb['mb_sex'], $smj_only) ?></td>
        <th scope="row"><label for="mb_birth">생년월일<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" id="mb_birth" name="mb_birth" class="datepicker required frm_input" <?=$smj_only?> required value="<?php echo date_return_empty_space($mb['mb_birth'])?>" maxlength="10" readonly></td>
    </tr>
    <tr><th><h1>면허 관리 정보</h1></th></tr>
    <tr>
        <th scope="row"><label for="mb_license_mean">면허 종류</label></th>
        <td><?php echo get_license_select('mb_license_mean', 0, 4, $mb['mb_license_mean']) ?></td>
        <th scope="row"><label for="mb_first_license_day">최초면허 발급일</label></th>
        <td><input type="text" id="mb_first_license_day" name="mb_first_license_day" class="datepicker frm_input" value="<?php echo date_return_empty_space($mb['mb_first_license_day'])?>" readonly></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_license_renewal_day">면허 갱신일</label></th>
        <td><input type="text" id="mb_license_renewal_day" name="mb_license_renewal_day" class="datepicker frm_input" value="<?php echo date_return_empty_space($mb['mb_license_renewal_day'])?>" readonly></td>
        <th scope="row"><label for="datepicker_from">면허유효기간</label></th>
        <td>
            <input type="text" id="datepicker_from" name="mb_validity_day_from" value="<?php echo date_return_empty_space($mb['mb_validity_day_from'])?>" readonly class="frm_input">부터
            <input type="text" id="datepicker_to" name="mb_validity_day_to" value="<?php echo date_return_empty_space($mb['mb_validity_day_to'])?>" readonly class="frm_input">까지
        </td>

    </tr>
<!--    <tr>-->
<!--        <th scope="row">본인확인방법</th>-->
<!--        <td colspan="3">-->
<!--            <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" --><?php //if($mb['mb_certify'] == 'ipin') echo 'checked="checked"'; ?>
<!--            <label for="mb_certify_ipin">아이핀</label>-->
<!--            <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" --><?php //if($mb['mb_certify'] == 'hp') echo 'checked="checked"'; ?>
<!--            <label for="mb_certify_hp">휴대폰</label>-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th scope="row">본인확인</th>-->
<!--        <td>-->
<!--            <input type="radio" name="mb_certify" value="1" id="mb_certify_yes" --><?php //echo $mb_certify_yes; ?>
<!--            <label for="mb_certify_yes">예</label>-->
<!--            <input type="radio" name="mb_certify" value="" id="mb_certify_no" --><?php //echo $mb_certify_no; ?>
<!--            <label for="mb_certify_no">아니오</label>-->
<!--        </td>-->
<!--        <th scope="row">성인인증</th>-->
<!--        <td>-->
<!--            <input type="radio" name="mb_adult" value="1" id="mb_adult_yes" --><?php //echo $mb_adult_yes; ?>
<!--            <label for="mb_adult_yes">예</label>-->
<!--            <input type="radio" name="mb_adult" value="0" id="mb_adult_no" --><?php //echo $mb_adult_no; ?>
<!--            <label for="mb_adult_no">아니오</label>-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th scope="row">주소</th>-->
<!--        <td colspan="3" class="td_addr_line">-->
<!--            <label for="mb_zip" class="sound_only">우편번호</label>-->
<!--            <input type="text" name="mb_zip" value="--><?php //echo $mb['mb_zip1'].$mb['mb_zip2']; ?><!--" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">-->
<!--            <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>-->
<!--            <input type="text" name="mb_addr1" value="--><?php //echo $mb['mb_addr1'] ?><!--" id="mb_addr1" class="frm_input readonly" size="60">-->
<!--            <label for="mb_addr1">기본주소</label><br>-->
<!--            <input type="text" name="mb_addr2" value="--><?php //echo $mb['mb_addr2'] ?><!--" id="mb_addr2" class="frm_input" size="60">-->
<!--            <label for="mb_addr2">상세주소</label>-->
<!--            <br>-->
<!--            <input type="text" name="mb_addr3" value="--><?php //echo $mb['mb_addr3'] ?><!--" id="mb_addr3" class="frm_input" size="60">-->
<!--            <label for="mb_addr3">참고항목</label>-->
<!--            <input type="hidden" name="mb_addr_jibeon" value="--><?php //echo $mb['mb_addr_jibeon']; ?><!--"><br>-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th scope="row"><label for="mb_icon">최신면허사본</label></th>-->
<!--        <td colspan="3">-->
<!--            --><?php //echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_icon_width'].'픽셀 높이 '.$config['cf_member_icon_height'].'픽셀</strong>로 해주세요.') ?>
<!--            <input type="file" name="mb_icon" id="mb_icon">-->
<!--            --><?php
//            $mb_dir = substr($mb['mb_id'],0,2);
//            $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
//            if (file_exists($icon_file)) {
//                $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file);
//                $icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file) : '';
//                echo '<img src="'.$icon_url.$icon_filemtile.'" alt="">';
//                echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제';
//            }
//            ?>
<!--        </td>-->
<!--    </tr>-->
<!--    <th scope="row"><label for="mb_license">최신면허사본</label></th>-->
<!--    <td colspan="3">-->
<!--        --><?php //echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_icon_width'].'픽셀 높이 '.$config['cf_member_icon_height'].'픽셀</strong>로 해주세요.') ?>
<!--        <input type="file" name="mb_license" id="mb_license">-->
<!--        --><?php
//        $mb_dir = substr($mb['mb_id'],0,2);
//        $icon_file = G5_DATA_PATH.'/member_licence/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
//        if (file_exists($icon_file)) {
//            echo get_member_profile_img($mb['mb_id']);
//            echo '<input type="checkbox" id="del_mb_license" name="del_mb_license" value="1">삭제';
//        }
//        ?>
<!--    </td>-->
    <tr>
        <th scope="row"><label for="mb_license">최신면허사본</label></th>
        <td colspan="3">
<!--            --><?php //echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_icon_width'].'픽셀 높이 '.$config['cf_member_icon_height'].'픽셀</strong>로 해주세요.') ?>
<!--            <input type="file" name="mb_license" id="mb_license">-->
<!--            --><?php
//            $mb_dir = substr($mb['mb_id'],0,2);
//            $icon_file = G5_DATA_PATH.'/member_license/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
//            if (file_exists($icon_file)) {
//                echo get_member_profile_img($mb['mb_id']);
//                echo '<input type="checkbox" id="del_mb_license" name="del_mb_license" value="1">삭제';
//            }
//            ?>
            <?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_icon_width'].'픽셀 높이 '.$config['cf_member_icon_height'].'픽셀</strong>로 해주세요.') ?>
            <input type="file" name="mb_license" id="mb_license">
            <?php
            $mb_dir = substr($mb['mb_id'],0,2);
            $icon_file2 = G5_DATA_PATH.'/member_license/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
            if (file_exists($icon_file2)) {
                $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file2);
                $icon_filemtile2 = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file2) : '';
                echo '<img src="'.$icon_url.$icon_filemtile2.'" alt="">';
                echo '<input type="checkbox" id="del_mb_license" name="del_mb_license" value="1">삭제';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="extension_day">정년연장</label></th>
        <td>
            <input type="text" id="mb_license_ext_day_from" name="mb_license_ext_day_from" value="<?php echo date_return_empty_space($mb['mb_license_ext_day_from'])?>" readonly class="frm_input" <?=$smj_only?>>부터
            <input type="text" id="mb_license_ext_day_to" name="mb_license_ext_day_to" value="<?php echo date_return_empty_space($mb['mb_license_ext_day_to'])?>" readonly class="frm_input" <?=$smj_only?>>까지
        </td>
    </tr>

    <?php if ($w == 'u') { ?>
    <tr>
        <th scope="row">회원가입일</th>
        <td><?php echo date_return_empty_space($mb['mb_datetime']) ?></td>
        <th scope="row">최근접속일</th>
        <td><?php echo date_return_empty_space($mb['mb_today_login']) ?></td>
    </tr>

    <?php } ?>
    <?php if ($smj_only == '') { ?>
        <th scope="row"><label for="mb_leave_date">탈퇴일자</label></th>
        <td>
            <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="frm_input" maxlength="8">
            <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) {
this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
            <label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>
        </td>
    <?php } ?>
    <tr>
        <th>국가 필수 도선사 여부</th>
        <td>
            <input type="text" id="required_pilot_status_from" name="required_pilot_status_from" value="<?php echo date_return_empty_space($mb['required_pilot_status_from'])?>" readonly class="frm_input" <?=$smj_only?>>부터
            <input type="text" id="required_pilot_status_to" name="required_pilot_status_to" value="<?php echo date_return_empty_space($mb['required_pilot_status_to'])?>" readonly class="frm_input" <?=$smj_only?>>까지
        </td>
    </tr>
<!--        <th scope="row">접근차단일자</th>-->
<!--        <td>-->
<!--            <input type="text" name="mb_intercept_date" value="--><?php //echo $mb['mb_intercept_date'] ?><!--" id="mb_intercept_date" class="frm_input" maxlength="8">-->
<!--            <input type="checkbox" value="--><?php //echo date("Ymd"); ?><!--" id="mb_intercept_date_set_today" onclick="if-->
<!--(this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else {-->
<!--this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">-->
<!--            <label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>-->
<!--        </td>-->
<!--    </tr>-->
    <?php
    //소셜계정이 있다면
    if(function_exists('social_login_link_account') && $mb['mb_id'] ){
        if( $my_social_accounts = social_login_link_account($mb['mb_id'], false, 'get_data') ){ ?>

    <tr>
    <th>소셜계정목록</th>
    <td colspan="3">
        <ul class="social_link_box">
            <li class="social_login_container">
                <h4>연결된 소셜 계정 목록</h4>
                <?php foreach($my_social_accounts as $account){     //반복문
                    if( empty($account) ) continue;

                    $provider = strtolower($account['provider']);
                    $provider_name = social_get_provider_service_name($provider);
                ?>
                <div class="account_provider" data-mpno="social_<?php echo $account['mp_no'];?>" >
                    <div class="sns-wrap-32 sns-wrap-over">
                        <span class="sns-icon sns-<?php echo $provider; ?>" title="<?php echo $provider_name; ?>">
                            <span class="ico"></span>
                            <span class="txt"><?php echo $provider_name; ?></span>
                        </span>

                        <span class="provider_name"><?php echo $provider_name;   //서비스이름?> ( <?php echo $account['displayname']; ?> )</span>
                        <span class="account_hidden" style="display:none"><?php echo $account['mb_id']; ?></span>
                    </div>
                    <div class="btn_info"><a href="<?php echo G5_SOCIAL_LOGIN_URL.'/unlink.php?mp_no='.$account['mp_no'] ?>" class="social_unlink" data-provider="<?php echo $account['mp_no'];?>" >연동해제</a> <span class="sound_only"><?php echo substr($account['mp_register_day'], 2, 14); ?></span></div>
                </div>
                <?php } //end foreach ?>
            </li>
        </ul>
        <script>
        jQuery(function($){
            $(".account_provider").on("click", ".social_unlink", function(e){
                e.preventDefault();

                if (!confirm('정말 이 계정 연결을 삭제하시겠습니까?')) {
                    return false;
                }

                var ajax_url = "<?php echo G5_SOCIAL_LOGIN_URL.'/unlink.php' ?>";
                var mb_id = '',
                    mp_no = $(this).attr("data-provider"),
                    $mp_el = $(this).parents(".account_provider");

                    mb_id = $mp_el.find(".account_hidden").text();

                if( ! mp_no ){
                    alert('잘못된 요청! mp_no 값이 없습니다.');
                    return;
                }

                $.ajax({
                    url: ajax_url,
                    type: 'POST',
                    data: {
                        'mp_no': mp_no,
                        'mb_id': mb_id
                    },
                    dataType: 'json',
                    async: false,
                    success: function(data, textStatus) {
                        if (data.error) {
                            alert(data.error);
                            return false;
                        } else {
                            alert("연결이 해제 되었습니다.");
                            $mp_el.fadeOut("normal", function() {
                                $(this).remove();
                            });
                        }
                    }
                });

                return;
            });
        });
        </script>

    </td>
    </tr>

    <?php
        }   //end if
    }   //end if

    run_event('admin_member_form_add', $mb, $w, 'table');
    ?>

    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>
<?php if($w == 'u'){ ?>
<div style="display: flex; text-align: left" class="tbl_frm01 tbl_wrap">
    <table>
        <tr>
            <th scope="row" style="font-size: 15px"> 교육 신청 현황</th>
        </tr>

            <?php if(isset($mb_edu_list) &&$mb_edu_list != '' && count($mb_edu_list) != 0){
                for($i2 = 0; $i2< count($mb_edu_list); $i2++){?>
                <tr>
                    <td><?=$mb_edu_list[$i2]['edu_type_name']?>   - <?= substr($mb_edu_list[$i2]['apply_date'],0,10) ?></td>
                </tr>
                <?php   }
            }else{
                ?>
                <tr>
                    <td>신청현황이 없습니다.</td>
                </tr>
            <?php } ?>
    </table>
    <table>
        <tr>
            <th scope="row" style="font-size: 15px"> 교육 이수 현황</th>
        </tr>
            <?php if(isset($mb_edu_list) && $mb_edu_list != '' && count($mb_edu_list) != 0){
                for($i3 =0; $i3< count($mb_edu_list); $i3++){ ?>
                <tr>
                    <td><?=$mb_edu_list[$i3]['edu_type_name']?>   - <?php echo ($mb_edu_list[$i3]['lecture_completion_status'] == 'N')? '미이수' : '이수'; ?></td><br>
                </tr>
                <?php }
            } else { ?>
                <tr>
                    <td>이수현황이 없습니다.</td>
                </tr>
            <?php } ?>
    </table>
</div>
<?php }?>
<?php if ($w == 'u' && $smj_only == '') { ?>
    <tr>
        <th scope="row"><b style="font-size: 15px">해심재결사항 관리</b></th>
        <td><button type="button" id="modal_open" class="btn btn_03 modal-open" onclick="modal('my_modal_member')"> 관리 </button></td>
        <div id="my_modal_member">
            <form name="fpunish" id="fpunish" action="./member_form_punishment_insert.php"  method="post">
                <h1 class="h2_frm"> 해심재결사항 관리</h1>
                <input type="hidden" id="member_id" name="member_id" value="<?=$mb['mb_id']?>">
                <div>
                    <label for="mb_punishment">징계 선택</label>
                    <select id="mb_punishment" name="mb_punishment" class="frm_input">
                        <option value="1">업무 정지</option>
                        <option value="2">견책</option>
                        <option value="3">면허 취소</option>
                    </select>
                </div>
                <div>
                    <label for="mb_punishment_date">징계일자</label>
                    <input type="date" id="mb_punishment_date" name="mb_punishment_date" class="frm_input">
                </div>
                <br>
                <div>
                    <label for="mb_punishment_memo">메모</label>
                    <input type="text" id="mb_punishment_memo" name="mb_punishment_memo" placeholder="내용을 입력해주세요" width="200" height="200" class="frm_input">
                </div>
                <br>
                <div style="text-align: center">
                    <button type="submit" class="btn btn_03"> 저장 </button>
                </div>

                <button type="button" class="modal_close_btn btn btn_02">닫기</button>
            </form>
        </div>
        <!--        <td>--><?php //echo get_applicable_or_not_select('mb_applicable_or_not', 0, 3, "", 'changePunishmentValue()') ?><!--</td>-->
        <!---->
        <!--        <th scope="row" id="punish_label" style="display: none" class="punishment"><label for="mb_punishment">징계 선택</label></th>-->
        <!--        <td>-->
        <!--            <select id="mb_punishment" name="mb_punishment" style="display: none" class="punishment">-->
        <!---->
        <!--            </select>-->
        <!--        </td>-->
        <!--        <th scope="row" class="punishment" style="display: none"><label for="mb_punishment_date">징계 선고일</label></th>-->
        <!--        <td><input type="text" id="mb_punishment_date" name="mb_punishment_date" class="datepicker punishment" value="" style="display: none" readonly></td>-->
    </tr>
<?php } ?>
<?php
//$sql_member_punish_select = " select * form {$g5}";
$sql_member_punish_select = " from {$g5['member_punishment']} ";

$sql_search_punish = " where mb_id ='{$mb['mb_id']}'";
//if ($stx) {
//    $sql_search .= " and ( ";
//    switch ($sfl) {
//        default :
//            $sql_search .= " ({$sfl} like '%{$stx}%') ";
//            break;
//    }
//    $sql_search .= " ) ";
//}

if (!$sst1) {
    $sst1  = " mb_punishment_date desc";
    $sod1 = "";
}
$sql_order_punish = " order by $sst1 $sod1 ";

/*$sql_count_punish = " select count(*) as cnt
            {$sql_member_punish_select}
            {$sql_search}
            {$sql_order} ";
$row_punish = sql_fetch($sql_count_punish);
$total_count_punish = $row_punish['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count_punish / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
*/

$sql_sel_punish = " select *
            {$sql_member_punish_select}
            {$sql_search_punish}
            {$sql_order_punish}
            ";
$result_punish = sql_query($sql_sel_punish);
?>
    <div><h2>해심재결사항 기록</h2></div>
    <form name="fauthlist" id="fauthlist" method="post" action="./member_form_punishment_delete.php" onsubmit="return fauthlist_submit(this);">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
<!--    <input type="hidden" name="sfl" value="--><?php //echo $sfl ?><!--">-->
<!--    <input type="hidden" name="stx" value="--><?php //echo $stx ?><!--">-->
<!--    <input type="hidden" name="page" value="--><?php //echo $page ?><!--">-->
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록 </caption>
            <thead>
            <tr>
                <th scope="col">
                    <label for="chkall" class="sound_only">현재 페이지 징계 전체</label>
                    <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                </th>
                <th scope="col"><?php echo subject_sort_link('a.mb_id') ?>회원아이디</a></th>
                <!--        <th scope="col">--><?php //echo subject_sort_link('mb_nick') ?><!--닉네임</a></th>-->
                <th scope="col">징계사항</th>
                <th scope="col">상세내용</th>
                <th scope="col">징계일자</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 0;
            for ($i=0; $row=sql_fetch_array($result_punish); $i++)
            {
                $is_continue = false;

                if($is_continue)
                    continue;

                //$mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

                $bg = 'bg'.($i%2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <td class="td_chk">
                        <input type="hidden" name="mb_punishment_memo[<?php echo $i ?>]" value="<?php echo $row['mb_punishment_memo'] ?>">
                        <input type="hidden" name="mb_punishment[<?php echo $i ?>]" value="<?php echo $row['mb_punishment'] ?>">
                        <input type="hidden" name="mb_punishment_date[<?php echo $i ?>]" value="<?php echo $row['mb_punishment_date'] ?>">
                        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>">
                        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_name'] ?>님 징계</label>
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                    </td>
                    <td class="td_mbid"><?php echo $row['mb_id'] ?></a></td>
                    <!--        <td class="td_auth_mbnick">--><?php //echo $mb_nick ?><!--</td>-->
                    <td class="td_applicable_or_not">
                        <?php echo change_applicable_or_not_to_kr($row['mb_punishment']) ?>
                    </td>
                    <td class="mb_punishment_memo"><?php echo $row['mb_punishment_memo'] ?></td>
                    <td class="mb_punishment_date"><?php echo $row['mb_punishment_date'] ?></td>
                </tr>
                <?php
                $count++;
            }

            if ($count == 0)
                echo '<tr><td colspan="'.$colspan.'" class="empty_table">징계사유가 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
    </div>

    <div class="btn_list01 btn_list">
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    </div>

<?php
//if (isset($stx))
//    echo '<script>document.fsearch.sfl.value = "'.$sfl.'";</script>'."\n";

if (strstr($sfl, 'mb_id'))
    $mb_id = $stx;
else
    $mb_id = '';
?>
    </form>

<?php
//$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page=');
//echo $pagelist;
//?>

<script>
    let pwTest = /[a-zA-Z\w!@#$%|^&|*|(|)]{6,20}/;
    let searchPw = /[|~|`|-|_|+|=|?|>|<|,|.]/;
    let birthTest = /^(19[0-9][0-9]|20\d{2})-(0[0-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
    let phoneTest = /^\d{3}-\d{3,4}-\d{4}$/;
    let emailTest = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
    let nameTest = /^[가-힣a-zA-Z]{2,50}$/;
    let telTest = /^\d{2,3}-\d{3,4}-\d{4}$/;
    /*let updateFlag = <?= isset($w)&&$w!='' ? "'".$w."'" : "'".'a'."'" ?>;*/
    // function changePunishmentValue(){
    //     let sVal = $('#mb_applicable_or_not').val();
    //     let $punish_array = [];
    //     let $key;
    //     switch (sVal){
    //         case '1': $punish_array = ["해심1","해심2","해심3"]; $key = 100; $(".punishment").show();  break;
    //         case '2': $punish_array = ["재결1","재결2","재결3"]; $key = 200; $(".punishment").show();  break;
    //         case '3': $punish_array = ["종결1","종결2","종결3"]; $key = 300; $(".punishment").show();  break;
    //         case '0': $(".punishment").hide();
    //         default: break;
    //     }
    //     $('.punish_value').remove();
    //     for($i=0; $i<$punish_array.length; $i++){
    //         $('#mb_punishment').append("<option class=punish_value value='"+($key+$i)+"'>"+$punish_array[$i]+"</option>");
    //     }
    // }
    $(function(){
        $(".datepicker").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: false });
    });
    $(function(){
        // 시작날짜와 끝나는 날짜를 함께 선택해서 사용할때
        let dates = $( "#datepicker_from, #datepicker_to" ).datepicker({
            //defaultDate: "+1w",  // 기본선택일이 1 week 이후가 선택되는 옵션
            changeYear: true,
            changeMonth: true,
            dateFormat: "yy-mm-dd",  //  년월일 표시방법  yy-mm-dd 또는 yymmdd
            numberOfMonths: 1,  // 한눈에 보이는 월달력수
            onSelect: function( selectedDate ) {
                let option = this.id === "datepicker_from" ? "minDate" : "maxDate",
                    instance = $( this ).data( "datepicker" ),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                dates.not( this ).datepicker( "option", option, date );
            }
        });
    })
    $(function(){
        // 시작날짜와 끝나는 날짜를 함께 선택해서 사용할때
        let dates = $( "#mb_license_ext_day_from, #mb_license_ext_day_to" ).datepicker({
            //defaultDate: "+1w",  // 기본선택일이 1 week 이후가 선택되는 옵션
            changeYear: true,
            changeMonth: true,
            dateFormat: "yy-mm-dd",  //  년월일 표시방법  yy-mm-dd 또는 yymmdd
            numberOfMonths: 1,  // 한눈에 보이는 월달력수
            onSelect: function( selectedDate ) {
                let option = this.id === "mb_license_ext_day_from" ? "minDate" : "maxDate",
                    instance = $( this ).data( "datepicker" ),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                dates.not( this ).datepicker( "option", option, date );
            }
        });
    })
    $(function(){
        // 시작날짜와 끝나는 날짜를 함께 선택해서 사용할때
        let dates = $( "#required_pilot_status_from, #required_pilot_status_to" ).datepicker({
            //defaultDate: "+1w",  // 기본선택일이 1 week 이후가 선택되는 옵션
            changeYear: true,
            changeMonth: true,
            dateFormat: "yy-mm-dd",  //  년월일 표시방법  yy-mm-dd 또는 yymmdd
            numberOfMonths: 1,  // 한눈에 보이는 월달력수
            onSelect: function( selectedDate ) {
                let option = this.id === "required_pilot_status_from" ? "minDate" : "maxDate",
                    instance = $( this ).data( "datepicker" ),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                dates.not( this ).datepicker( "option", option, date );
            }
        });
    })
    function fmember_submit(f)
    {
        if (!f.mb_license.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_license.value) {
            alert('면허사본은 이미지 파일만 가능합니다.');
            return false;
        }

        if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
            alert('회원이미지는 이미지 파일만 가능합니다.');
            return false;
        }

        if(!(nameTest.test(f.mb_name.value)) || f.mb_name.value == ""){
            alert('실명을 입력해주세요.(2~50 자 이내)');
            f.mb_name.focus();
            return false;
        }

        if(!(emailTest.test(f.mb_email.value)) || f.mb_email.value == ""){
            alert('제대로 된 이메일 형식이 아닙니다. ex) XXXX@XXXX.com 등 형식으로 작성해주세요');
            f.mb_email.focus();
            return false;
        }

        if(!(phoneTest.test(f.mb_hp.value)) || f.mb_hp.value == "000-0000-0000" || f.mb_hp.value == ""){
            alert("제대로 된 휴대폰 번호 형식이 아닙니다. \n ex) 010-XXXX-XXXX 형식으로 작성해주세요");
            f.mb_hp.focus();
            return false;
        }

        //alert($('#mb_tel').val());
        if($('#mb_tel').val() != "" && !(telTest.test($('#mb_tel').val()))){
            alert('제대로 된 형식이 아닙니다. \nex) xx-xxxx-xxxx 형식으로 작성해주세요');
            $('#mb_tel').focus();
            return false;
        }

        if(!(birthTest.test(f.mb_birth.value)) || f.mb_birth.value == "0000-00-00" || f.mb_birth.value == ""){
            alert(" 제대로 된 형식이 아닙니다 \n ex) 2010-11-11 형식으로 작성해주세요");
            f.mb_birth.focus();
            return false;
        }

        if(f.mb_password.value !=0 && f.mb_password.length != 0){
            if (f.mb_password.value.length > 0) {
                if (f.mb_password.value.length < 6) {
                    alert("비밀번호를 6글자 이상 입력하십시오.");
                    f.mb_password.focus();
                    //f.mb_password.value = "";
                    return false;
                }
            }

            if (!(pwTest.test(f.mb_password.value))) {
                alert("비밀번호는 영어소문자 또는 대문자,특수문자(!@#$%^&*())숫자포함 6글자이상 20글자이하만 허용가능합니다.");
                f.mb_password.focus();
                return false;
            }

            if (searchPw.test(f.mb_password.value)) {
                alert("비밀번호는 특수문자(!@#$%^&*())만 허용가능합니다.");
                f.mb_password.focus();
                return false;
            }

            if (f.mb_password.value.length > 20) {
                alert("비밀번호는 6글자이상 20글자이하로 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        return true;
    }
    function fauthlist_submit(f)
        {
            if (!is_checked("chk[]")) {
                alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            if(document.pressed == "선택삭제") {
                if(!confirm("선택한 내용을 정말 삭제하시겠습니까?")) {
                    return false;
                }
            }

            return true;
        }
</script>
<?php
run_event('admin_member_form_after', $mb, $w);

include_once('./admin.tail.php');