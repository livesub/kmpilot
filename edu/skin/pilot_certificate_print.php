<?php
include_once('../common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$edu_idx = $_POST['edu_idx'];
$edu_type = $_POST['edu_type'];

if($edu_idx == "" || $edu_type == ""){
    alert($lang['fatal_err'],"");
    exit;
}

$sql_list = sql_query(" select * from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' ");
$edu_list_cnt = sql_num_rows($sql_list);
$row = sql_fetch_array($sql_list);

if($edu_list_cnt == 0){
    alert($lang['fatal_err'],"");
    exit;
}

//수료 했는지 재 검증
$sql_cp = sql_query("select lecture_completion_date,certificate_num from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and mb_id='{$member['mb_id']}' and lecture_completion_status = 'Y' ");
$row_cp_cnt = sql_num_rows($sql_cp);
$row_app = sql_fetch_array($sql_cp);
if($row_cp_cnt == 0){
    alert($lang['fatal_err'],"");
    exit;
}

//for($i = 10; $i <= 999 ; $i++) {
//    $num = sprintf('%02d',$i); echo $num."\n";
//}

$edu_cal_end = explode("-",$row['edu_cal_end']);
$year_mk = substr($edu_cal_end[0],2,2);
$certificate_num = sprintf('%02d',$row_app['certificate_num']);
//$year_mk = date("y");
$no_mk = $row['edu_type']." ".$year_mk." - ".$certificate_num; //사람 순번이 아닌 이수 완료 순서로 한다!

//유효 기간은 교육 종료 일 부터 2년
$end_available = ($edu_cal_end[0] + 2)."-".$edu_cal_end[1]."-".$edu_cal_end[2];

$cmp_date_mk_cut = explode(" ",$row_app['lecture_completion_date']);
$cmp_date_mk = explode("-",$cmp_date_mk_cut[0]);
//$g5['title'] = $g5['board_title'];
//include_once(G5_PATH.'/head.php');
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title><?=$row['edu_name_'.$lang_type]?></title>
<link rel="stylesheet" href="http://localhost/edu/css/default.css?ver=191202">
<link rel="stylesheet" href="http://localhost/edu/js/font-awesome/css/font-awesome.min.css?ver=191202">
<style type="text/css"> @page { size: auto;  margin: 0mm; } </style>
</head>
<body>

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
    location.href='../bbs/content.php?co_id=pilot_edu_apply_status';
}
</script>


<div id='print_page'>
<table>
    <tr>
        <td><font size="7">수 료 증 서</font></td>
    </tr>
    <tr>
        <td>No : <?=$no_mk?></td>
    </tr>
    <tr>
        <td>성명 : <?=$member['mb_name']?></td>
    </tr>
    <tr>
        <td>생년월일 : <?=date_point_change($member['mb_birth'])?></td>
    </tr>

    <tr>
        <td>교육과정 : <?=edu_type($row['edu_type'])?></td>
    </tr>

    <tr>
        <td>교육기간 : <?=date_point_change($row['edu_cal_start'])?> ~ <?=date_point_change($row['edu_cal_end'])?> (<?=$row[edu_time]?>) </td>
    </tr>

    <tr>
        <td>유효기간 : <?=date_point_change($row['edu_cal_end'])?> ~ <?=date_point_change($end_available)?></td>
    </tr>

    <tr>
        <td>수료일 : <?=$cmp_date_mk[0]?>년 <?=$cmp_date_mk[1]?>월 <?=$cmp_date_mk[2]?>일</td>
    </tr>


</table>
</div>
    <input type='button' value='<?=$lang['lecture_print']?>' onclick="pageprint()">
    <input type='button' value='<?=$lang['lecture_list']?>' onclick="page_list()">
</body>
</html>
