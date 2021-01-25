<?php
$sub_menu = "900100";
include_once("./_common.php");

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = "문자 기본설정";
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="frm_sms_config" method="post" action="./sms_action.php" target="hiddenframe" onsubmit="return sms_config_update(this)">
<input type="hidden" name="mode" value="config_modify"/>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="cf_sms_type">사용자 코드</label></th>
        <td><?=$deptcode?></td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_sms_type">사용자 이름</label></th>
        <td><?=$username?></td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_sms_type">현재 잔액</label></th>
        <td><strong class="en cred"><?=number_format($money)?></strong>원</td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_sms_type">회신담당자</label></th>
        <td><input type="text" class="text wrest_required" size="20" name="cms_mng" id="cms_mng" value="<?=$CFG["cms_mng"]?>"/></td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_sms_type">회신번호</label></th>
        <td><input type="text" class="text wrest_required" size="14" name="cms_sms_number" id="cms_sms_number" value="<?=$CFG["cms_sms_number"]?>"/> <label class="c9" id="cms_sms_number">예제) xxx-xxxx-xxxx</label></td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_sms_type">MMS 기본제목</label></th>
        <td><input type="text" class="text wrest_required" size="40" name="cms_sms_title" id="cms_sms_title" value="<?=$CFG["cms_sms_title"]?>"/></td>
    </tr>

    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
</div>
</form>

<script>
//CMS 환경설정 저장
function sms_config_update(from){
	var fm = document.forms['frm_sms_config'];
	$("#btn_update").hide();
	$("#process_warp").show();
	if(FormCheck(fm)){
		fm.submit();
	}
	return false;
}
</script>



<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
