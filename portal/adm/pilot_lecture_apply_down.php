<?php
include_once('./_common.php');
auth_check_menu($auth, $sub_menu, 'r');
auth_check($auth[$sub_menu], "r");

/*
if ( ! function_exists('utf2euc')) {
    function utf2euc($str) {
        return iconv("UTF-8","cp949//IGNORE", $str);
    }
}
if ( ! function_exists('is_ie')) {
    function is_ie() {
        return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
    }
}
*/


$lecture_idx = $_GET['lecture_idx'];
$type = $_GET['type'];
$status = $_GET['status'];

$table = $g5[$type."_table"];

//교육 테이블 정보 가져 오기
$row_lecture = sql_fetch(" select * from {$table} where idx = '{$lecture_idx}' ");

if($status){
    switch ($status) {
        case 'all' :
            $sql_where = "";
            $ment = " 수강 신청 목록";
            break;
        case 'complet' :
            $sql_where = " and lecture_completion_status = 'Y' ";
            $ment = " 수료 인원 목록";
            break;
        case 'nocomplet' :
            $sql_where = " and  lecture_completion_status = 'N' ";
            $ment = " 미수료 인원 목록";
            break;
        default :
            $sql_where = "";
            $ment = " 수강 신청 목록";
            break;
    }
}

$hp_filename = $row_lecture['subject'].$ment;
$g5['title'] = $row_lecture['subject'].$ment;



header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename={$hp_filename}.xls" );
//header( "Content-Description: PHP4 Generated Data" );
header( "Content-Description: PHP4 Generated Data" );

$result = sql_query(" select * from {$g5[pilot_lecture_apply_table]} where lecture_idx = '{$lecture_idx}' and lecture_type_table='{$type}' {$sql_where} order by idx desc");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.txt {mso-number-format:'\@'}
</style>
</head>

<body>
<table>
    <tr>
        <td><?=$row_lecture['subject']?></td>
    </tr>
    <tr>
        <td><?=$ment?></td>
    </tr>
    <tr>
        <td>이름</td>
        <td>수강신청일</td>
<?php
if($status == "complet" || $status == "all" || $status == ""){
    echo "<td>수료일</td>";
}
?>
        <td>도선구</td>
        <td>연락처</td>
        <td>이메일</td>
        <td>주소</td>
    </tr>

<?php
for ($i=0; $rows=sql_fetch_array($result); $i++) {
    //회원 이름 찾기
    $m_info = sql_fetch(" select mb_name, mb_hp, mb_email, mb_zip1, mb_addr1, mb_addr2, mb_addr3 from {$g5['member_table']} where mb_id = '{$rows[mb_id]}' ");
    if($rows['lecture_completion_status'] == "Y"){
        $add_echo = "<td class='txt'>{$rows['lecture_completion_date']}</td>";
    }else{
        if($rows['lecture_completion_date'] == "0000-00-00 00:00:00"){
            $add_echo = "<td class='txt'></td>";
        }
    }
    echo "
    <tr>
        <td>{$m_info['mb_name']}</td>
        <td class='txt'>{$rows['lecture_apply_date']}</td>
        {$add_echo}
        <td>도선구</td>
        <td>{$m_info['mb_hp']}</td>
        <td class='txt'>{$m_info['mb_email']}</td>
        <td class='txt'>({$m_info['mb_zip1']}) {$m_info['mb_addr1']} {$m_info['mb_addr2']} {$m_info['mb_addr3']}</td>
    </tr>
    ";
}
?>
</table>
</body>
</html>