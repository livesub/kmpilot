<?php
//테스트 확인 완료 2021.03.04 kkw
include_once ("./_common.php");
//alert('들어왔나?');

if(!isset($_POST['member_id']) || $_POST['member_id'] == ''){
    alert('잘못된 접근입니다.');
    exit;
}
$member_id = $_POST['member_id'];

$sql_sel_member = " select * from {$g5['member_table']} where mb_id = '{$member_id}'";
$result_sel_member = sql_fetch($sql_sel_member);

if(!$result_sel_member){
    alert('해당 회원이 없습니다. 확인해주세요');
}

//해당 아이디 그룹 찾기
$sql_sel_member_group = " select * from {$g5['group_member_table']} where mb_id = '{$member_id}' limit 1";
$result_group = sql_fetch($sql_sel_member_group);
$result_gr_id = $result_group['gr_id'];

//해당 아이디 학력사항 찾기
$sql_sel_acade = " select * from {$g5['member_academic_back']} where mb_id = '{$member_id}'";
$result_sel_acade = sql_fetch($sql_sel_acade);
?>
<h1>회원 상세 정보</h1>
<div><button onclick="window.close()">닫기</button></div>
<?php
$mb_dir = substr($member_id,0,2);
$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.get_mb_icon_name($member_id).'.gif';
if (file_exists($icon_file)) {
    echo get_member_profile_img($member_id,'','','profile_image','','image');
}
?>

<div style="display: flex;">
    <div>
        <h2><b>이름</b></h2>
        <h3><?=$result_sel_member['mb_name']?></h3>
        <h2><b>연락처</b></h2>
        <h3><?=$result_sel_member['mb_hp']?></h3>
        <h2><b>E-mail</b></h2>
        <h3><?=$result_sel_member['mb_email']?></h3>
        <h2><b>주소</b></h2>
        <?php if($result_sel_member['mb_addr1'] != ''){?>
        <h3><?=$result_sel_member['mb_addr1']?></h3>
        <?php }else{?>
            <h3> 등록된 주소 없음</h3>
        <?php }?>
        <h2><b>상세 주소</b></h2>
        <?php if($result_sel_member['mb_addr2'] != '' || $result_sel_member['mb_addr3'] != ''){?>
        <h3><?=$result_sel_member['mb_addr2']?>&nbsp;&nbsp; <?=$result_sel_member['mb_addr3']?></h3>
        <?php }else{?>
        <h3> 등록된 주소 없음</h3>
        <?php }?>
        <h2><b>연락처</b></h2>
        <h3><?=$result_sel_member['mb_hp']?></h3>
    </div>
    <div>
        <h2><b>도선구</b></h2>
        <h3><?= get_doseongu_name($result_sel_member['mb_doseongu'])?></h3>
        <h2><b>그룹</b></h2>
        <h3><?php echo $result_gr_id? get_group_name($result_gr_id) : '등록된 그룹 없음' ?></h3>
        <h2><b>생년월일</b></h2>
        <h3><?=$result_sel_member['mb_birth']?></h3>
        <h2><b>도선약호</b></h2>
        <h3><?php echo $result_sel_member['mb_lead_code']? $result_sel_member['mb_lead_code'] : '등록된 도선약호 없음' ?></h3>
        <h2><b>면허 종류</b></h2>
        <h3><?=get_license_mean($result_sel_member['mb_license_mean'])?></h3>
        <h2><b>최초 면허 발급일</b></h2>
        <h3><?php echo $result_sel_member['mb_first_license_day']? $result_sel_member['mb_first_license_day'] : '등록된 최초 면허 발급일 없음' ?></h3>
        <h2><b>학력</b></h2>
        <?php if($result_sel_acade != '' && $result_sel_acade['high_status'] != 0 && $result_sel_acade['university_status'] != 0){?>
        <h3>고등학교 : <?=$result_sel_acade['high_name']?> &nbsp; <?=$result_sel_acade['high_major']?> &nbsp; <?=get_grade_status_value($result_sel_acade['high_status'])?></h3>
        <h3>대학교 : <?=$result_sel_acade['university_name']?> &nbsp; <?=$result_sel_acade['university_major']?> &nbsp; <?=get_grade_status_value($result_sel_acade['university_status'])?></h3>
        <?php }else{?>
            <h3> 등록된 학력사항 없음</h3>
        <?php }?>
    </div>
</div>
<button onclick="window.close()">닫기</button>
