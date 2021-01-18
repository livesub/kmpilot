<?php
include_once('../common.php');
include_once(PORTAL_DATA_PATH."/dbconfig.php");

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert("{$lang['lecture_login']}","");
    exit;
}

$lecture_idx = $_POST['lecture_idx'];
$lecture_type_table = $_POST['lecture_type_table'];

$location = G5_BBS_URL."/content.php?co_id=pilot_certificates_issued_list";


//post로 넘겨 받은 데이터가 정확한지 검사
$row = sql_fetch(" select * from ".$g5['pilot_lecture_apply_table']." where lecture_idx = '{$lecture_idx}' and lecture_type_table = '{$lecture_type_table}' and mb_id = '{$member['mb_id']}' and lecture_completion_status = 'Y' ");

if(count($row) == 0){
    alert("{$lang['lecture_login']}","");
    exit;
}

$lecture_completion_date = explode(" ",$row['lecture_completion_date']);
$date_val = explode("-",$lecture_completion_date[0]);
$data = sql_fetch(" select subject,startdatetime,enddatetime from {$g5[$row[lecture_type_table].'_table']} where idx = '{$lecture_idx}' ");
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title><?=$data['subject']?></title>

<style type="text/css"> @page { size: auto;  margin: 0mm; } </style>


<script>
var initBody;
function beforePrint()
{
    initBody = document.body.innerHTML;
    document.body.innerHTML = print_page.innerHTML;
}

function afterPrint()
{
    document.body.innerHTML = initBody;
}

function pageprint()
{
    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;
    window.print();
}

function page_list()
{
    location.href='<?=$location?>';
}
</script>


<body>
<div id='print_page'>
<table>
    <tr>
        <td><font size="7">수 료 증 서</font></td>
    </tr>
    <tr>
        <td>성명 : <?=$member['mb_name']?></td>
    </tr>
    <tr>
        <td>생년월일 : <?=date_change($member['mb_birth'])?></td>
    </tr>

    <tr>
        <td>교육과정 : <?=$data['subject']?></td>
    </tr>

    <tr>
        <td>교육기간 : <?=date_change($data['startdatetime'])?> ~ <?=date_change($data['enddatetime'])?></td>
    </tr>

    <tr>
        <td>유효기간 : </td>
    </tr>

    <tr>
        <td>수료일 : <?=$date_val[0]?>년 <?=$date_val[1]?>월 <?=$date_val[2]?>일</td>
    </tr>


</table>
</div>
    <input type='button' value='<?=$lang['lecture_print']?>' onclick="pageprint()">
    <input type='button' value='<?=$lang['lecture_list']?>' onclick="page_list()">
</body>
</html>
