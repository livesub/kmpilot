<?php
include_once('./common.php');
include_once(G5_ADMIN_PATH."/sms_admin/_common.php");
require_once(G5_ADMIN_PATH."/sms_admin/lib/class.SURESMS.php");
$SMS = new SURESMS();

$DATA['Usercode'] = trim($_REQUEST['Usercode']);
$DATA['Deptcode'] = trim($_REQUEST['Deptcode']);
$DATA['SeqNo'] = trim($_REQUEST['member']);
$DATA['Count'] = trim($_REQUEST['Count']);
$DATA['Group_name'] = trim($_REQUEST['group_name']);
$DATA['To_Message'] = trim($_REQUEST['to_message']);
$DATA['Result'] = trim($_REQUEST['Result']);

switch($DATA['Result']){
	case "SUCCESS":
		$DATA['Receive']  = "1";//접수성공
		$DATA['ReceiveDetail']  = "성공";
	break;

	case "FAIL FORMATERROR":
		$DATA['Receive']  = "2";//메시지 형식오류
		$DATA['ReceiveDetail']  = "메시지 형식오류";
	break;

	case "FAIL NEEDCASH":
		$DATA['Receive']  = "3";//잔액 부족
		$DATA['ReceiveDetail']  = "잔액 부족";
	break;

	case "FAIL RESERVEERROR":
		$DATA['Receive']  = "4";//예약시간 형식 오류
		$DATA['ReceiveDetail']  = "예약시간 형식 오류";
	break;

	case "FAIL RESERVEDATEERROR":
		$DATA['Receive']  = "5";//과거시간 입력
		$DATA['ReceiveDetail']  = "과거시간 입력";
	break;

	case "FAIL NEEDREGIST":
		$DATA['Receive']  = "6";//인증오류 (ID & 회사코드 확인)
		$DATA['ReceiveDetail']  = "인증오류(ID 또는 회사코드 확인)";
	break;

	case "FAIL NEEDCONFIRM":
		$DATA['Receive']  = "7";//TTS, 국제SMS, SKT URL 별도 허가필요
		$DATA['ReceiveDetail']  = "TTS, 국제SMS, SKT URL 별도 허가필요";
	break;

	case "FAIL CALLDATAREJECT":
		$DATA['Receive']  = "8";//수신거부번호
		$DATA['ReceiveDetail']  = "수신거부번호";
	break;
}
// SMS 반환값 비교
if($DATA['SeqNo']){
	$result = $SMS->getSMS($DATA);
	switch($result['type']){
		case "3":
			$msg  = "입급처리 되었습니다.";
		break;

		case "1":case "2":
			$msg  = "신청되었습니다.";
		break;
	}
	if($result['Receive']=="0"){
		$SMS->UpdateSMS($DATA);
		$Wapp->alertReload($msg,"parent");
	}
}else{
	print_r( "정상적인 접근이 아닙니다.");
}
?>
