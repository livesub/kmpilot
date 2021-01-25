<?php
include_once('./common.php');
include_once(G5_ADMIN_PATH."/sms_admin/_common.php");
require_once(G5_ADMIN_PATH."/sms_admin/lib/class.SURESMS.php");
$SMS = new SURESMS();

$DATA['CallCount'] = trim($_REQUEST['CallCount']);
$DATA['SeqNo'] = trim($_REQUEST['SeqNum']);
$DATA['UserCode'] = trim($_REQUEST['UserCode']);
$DATA['DeptCode'] = trim($_REQUEST['DeptCode']);
$DATA['Result'] = trim($_REQUEST['Result']);
$DATA['Error'] = trim($_REQUEST['Error']);
$DATA['Phone'] = trim($_REQUEST['Phone']);

$DATA['RTime'] = trim($_REQUEST['RTime']);
$DATA['RecvTime'] = trim($_REQUEST['RecvTime']);
$DATA['ReqPhone'] = trim($_REQUEST['ReqPhone']);

if($DATA['SeqNo']){
	$result = $SMS->getSMS($DATA);
	if($result['RESULT']){
		print "1";
	}else{
		$SMS->resultSMS($DATA);
		print "0";
	}
}
?>