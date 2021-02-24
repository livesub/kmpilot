<?php
$sub_menu = "400100";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];
$choice_type = $_POST['choice_type'];
$edu_onoff_type = $_POST['edu_onoff_type'];

if($choice_type != "all"){
    if($edu_idx == "" || $edu_type == ""){
        alert("비정상적 접근 입니다.");
        exit;
    }

    //교육명 구하기(파일명으로 씀)
    $row_edu = sql_fetch(" select edu_type, edu_name_kr, edu_cal_start, edu_cal_end from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' ");
    $file_name = $row_edu['edu_name_kr']." 신청자 리스트_".date('Ymd');

    $sql = " select * from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and apply_cancel = 'N' order by apply_idx asc ";

    $edu_type = $row_edu['edu_type'];
    $edu_name_kr = $row_edu['edu_name_kr'];
    $edu_cal_start = $row_edu['edu_cal_start'];
    $edu_cal_end = $row_edu['edu_cal_end'];
}else{
    $file_name = "전체 신청자 리스트_".date('Ymd');
    $sql = " select * from kmp_pilot_edu_apply A, kmp_pilot_edu_list B, kmp_member C where A.apply_cancel = 'N' and C.mb_id = A.mb_id and A.edu_idx = B.edu_idx and A.edu_type = B.edu_type and B.edu_onoff_type = '{$edu_onoff_type}' order by B.edu_cal_end DESC, C.mb_name ASC, B.edu_idx DESC ";
}

header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = $file_name.xls" );
header( "Content-Description: PHP4 Generated Data" );

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
        <td>수료상태</td>
    </tr>

<?php
$virtual_num = 1;
for ($i=0; $row=sql_fetch_array($result); $i++) {
    if($choice_type != "all"){
        //회원 정보 찾기
        $row_mem = sql_fetch(" select mb_name, mb_birth, mb_hp, mb_validity_day_from, mb_validity_day_to, mb_license_ext_day_from, mb_license_ext_day_to from kmp_member where mb_id = '{$row['mb_id']}' ");
        //이수 현황
        $row_complete = sql_fetch(" select * from kmp_pilot_edu_apply where mb_id = '{$row['mb_id']}' and edu_idx = '{$row['edu_idx']}' and edu_type = '{$row['edu_type']}' and apply_cancel = 'N' ");
        if($row_complete['lecture_completion_status'] == "N"){
            $complete_ment = "미수료";
            $complete_date = "미수료";
        }else{
            $complete_ment = "수료";
            $complete_date = $row_complete['lecture_completion_date'];
        }
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
        <td><?=$complete_ment?></td>
    </tr>
<?php
    }else{
        //이수 현황
        $row_complete = sql_fetch(" select * from kmp_pilot_edu_apply where mb_id = '{$row['mb_id']}' and edu_idx = '{$row['edu_idx']}' and edu_type = '{$row['edu_type']}' and apply_cancel = 'N' ");
        if($row_complete['lecture_completion_status'] == "N"){
            $complete_ment = "미수료";
            $complete_date = "미수료";
        }else{
            $complete_ment = "수료";
            $complete_date = $row_complete['lecture_completion_date'];
        }
?>
    <tr>
        <td><?=$virtual_num?></td>
        <td><?=edu_type($row['edu_type']);?></td>
        <td><?=$row['edu_name_kr']?></td>
        <td><?=$row['edu_cal_start']?> ~ <?=$row['edu_cal_end']?></td>
        <td><?=$row['apply_date']?></td>
        <td><?=$row['mb_name']?></td>
        <td><?=$row['mb_birth']?></td>
        <td><?=$row['mb_hp']?></td>
        <td><?=$row['mb_validity_day_from']?> ~ <?=$row['mb_validity_day_to']?></td>
        <td><?=$row['mb_license_ext_day_from']?> ~ <?=$row['mb_license_ext_day_to']?></td>
        <td><?=$complete_ment?></td>
    </tr>
<?php
    }
    $virtual_num++;
}
?>
</table>
