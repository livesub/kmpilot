<?php
$sub_menu = "900300";
include_once("./_common.php");

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = "문자 보내기";


$result = sql_query(" select * from {$g5['group_table']} where gr_id != 'community' ");
$group_cnt = mysqli_num_rows($result);

//교육 총갯수 구하기
$now_year = date("Y");
$row_edu = sql_fetch(" select count(*) as cnt from kmp_pilot_edu_list where edu_del_type = 'N' and edu_cal_start like '%{$now_year}%' ");
$eduCount = $row_edu['cnt'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>

<link rel="shortcut icon" href="#">

<title>한국도선사협회</title>

<link rel="stylesheet" href="https://kmpilot.or.kr/CMS/_css/common.css" type="text/css" />
<link rel="stylesheet" href="https://kmpilot.or.kr/CMS/_css/layout.css" type="text/css" />
<link rel="stylesheet" href="https://kmpilot.or.kr/_css/board_default.css" type="text/css" />

<script type="text/javascript">
    CMS_FOLDER = "<?=G5_ADMIN_URL?>/sms_admin";
</script>


<script src="<?=G5_ADMIN_URL?>/sms_admin/sms_js/set_js.js" type="text/javascript"></script>
<script language="javascript" src="<?=G5_ADMIN_URL?>/sms_admin/sms_js/ajax_lib.js" type="text/javascript"></script>
<script language="javascript" src="<?=G5_ADMIN_URL?>/sms_admin/sms_js/address.min.js" type="text/javascript"></script>

<div class="pop_content">
	<div class="addr_category">
		<h2 class="blind">주요 연락처</h2>
		<ul class="default_group">
			<li class="group"><a id="group_list" href="#">그룹<em id="groupCount">(<?=$group_cnt?>)</em></a></li>
			<li class="branch"><a id="branch_list" href="#">지회<em id="branchCount">(12)</em></a></li>
			<li class="branch edu"><a id="edu_list" href="#">교육<em id="eduCount">(<?=$eduCount?>)</em></a></li>
        </ul>


		<div class="scr_area" id="scr_area"  style="display:block;">
			<h2 class="blind">그룹 주소</h2>
			<div class="loading" id="loading_glist"></div>
			<ul class="user_group">
                <li class="on first" id="group_all">그룹</li>
<?php
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $row_group_member = sql_fetch(" select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$row['gr_id']}' ");
    $group_member_count = $row_group_member['cnt'];
?>
                <li><a href="javascript:;" onclick="getTypeList('<?=$row['gr_id']?>','group');"><?=$row['gr_subject']?></a> <em class="cnt">(<?=$group_member_count?>)</em></li>
<?php
}
?>

			</ul>
        </div>

    </div>


	<div class="addr_list">
		<h3 id="listHeadLine">전체주소</h3>
		<div class="search_bar">
			<form name="addr_search" id="addr_search" onsubmit="return false;" action="">
			<input type="hidden" name="now_year" id="now_year" value="<?=$now_year?>">

			<fieldset>
				<legend>쪽지 주소 찾기 검색폼</legend>
				<label class="blind" for="addr_search">쪽지 주소찾기</label>
				<input type="hidden" name="mode" value="mb_search" />
				<input type="hidden" name="prcCode" value="ajax" />
				<input type="hidden" name="addr_group_type" id="addr_group_type" value="" />
				<input type="hidden" name="addr_group" id="addr_group" value="" />
				<span class="search_box">
				<input class="ipt" name="addr_sw" id="addr_sw" placeholder='회원 찾기'>

				</span>
				<a class="button_s" id="addr_search_btn" href="#">찾기</a>
			</fieldset>
			</form>
		</div>
		<div class="addr_list_head">
			<input id="check_all_addr" type="checkbox" name="check_all_addr" value=""><label for="check_all_addr" id="title_ment">그룹 전체선택<span class="blind">전체선택</span></label>
		</div>
		<div class="addr_list_cont">
			<ul id="listUL"></ul>
		</div>
	</div>
	<div class="addr_toitem">
		<h3>받는 사람 <em id="send_cnt" class="cnt_total">0</em> 명</h3>
		<div class="addr_list_cont">
			<ul id="toUL"></ul>
		</div>
		<div class="btn_ctrl">
			<a class="addr_add" href="javascript:;" onclick="multi_add_target();"><span class="blind">선택 추가</span></a>
			<a class="addr_remove" href="javascript:;" onclick="multi_remove_target();"><span class="blind">선택 삭제</span></a>
		</div>
	</div>
</div>
<div class="pop_bottom">
	<div class="ft_inct">
		<p>Shift 또는 Ctrl키를 누르시면 여러개를 한번에 선택하여 추가할 수 있습니다.</p>
		<a class="button_s" href="#" onclick="add_number();" id="selected_add_item">확인</a>
		<a class="button_s" onclick="window.close();" href="#">취소</a>
	</div>
</div>

<form name="insertt_addr" id="insertt_addr" onsubmit="return false;" action="">
	<input type="hidden" name="chkVal" id="chkVal" value="" />
</form>