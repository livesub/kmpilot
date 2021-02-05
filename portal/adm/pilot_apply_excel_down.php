<?php
$sub_menu = "400100";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];

if($edu_idx == "" || $edu_type == ""){
    alert("비정상적 접근 입니다.");
    exit;
}

//교육명 구하기(파일명으로 씀)
$row_edu = sql_fetch(" select edu_type, edu_name_kr, edu_cal_start, edu_cal_end from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' ");
$file_name = $row_edu['edu_name_kr']." 신청자 리스트_".date('Ymd');

header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = $file_name.xls" );
header( "Content-Description: PHP4 Generated Data" );

$sql = " select * from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and apply_cancel = 'N' order by apply_idx asc ";
$result = sql_query($sql);
?>
<meta content="application/vnd.ms-excel; charset=UTF-8" name="Content-type">

<table border='1'>
    <tr>
        <td>순번</td>
        <td>교육종류</td>
        <td>교육명</td>
        <td>교육기간</td>
        <td>신청일자</td>
        <td>이름</td>
        <td>생년월일</td>
        <td>휴대폰번호</td>
        <td>면허유효기간</td>
        <td>정년일자</td>
    </tr>

<?php
$virtual_num = 1;
for ($i=0; $row=sql_fetch_array($result); $i++) {
    //회원 정보 찾기
    $row_mem = sql_fetch(" select * from kmp_member where mb_id = '{$row['mb_id']}' ");
?>
    <tr>
        <td><?=$virtual_num?></td>
        <td><?=edu_type($row_edu['edu_type']);?></td>
        <td><?=$row_edu['edu_name_kr']?></td>
        <td><?=$row_edu['edu_cal_start']?> ~ <?=$row_edu['edu_cal_end']?></td>
        <td><?=$row['apply_date']?></td>
        <td><?=$row_mem['mb_name']?></td>
        <td><?=$row_mem['mb_birth']?></td>
        <td><?=$row_mem['mb_hp']?></td>
        <td><?=$row_mem['mb_validity_day_from']?> ~ <?=$row_mem['mb_validity_day_to']?></td>
        <td><?=$row_mem['mb_license_ext_day_from']?> ~ <?=$row_mem['mb_license_ext_day_to']?></td>
    </tr>
<?php
    $virtual_num++;
}
?>
</table>
