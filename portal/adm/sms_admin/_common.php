<?php
define('G5_IS_ADMIN', true);
include_once ('../../common.php');
include_once(G5_ADMIN_PATH.'/admin.lib.php');

include_once G5_ADMIN_PATH."/sms_admin/lib/common/suremcfg.php";
include_once G5_ADMIN_PATH."/sms_admin/lib/common/common.php";

$packettest = new SuremPacket;
$result=$packettest->checkMoney();

$res =substr($result,94,1);
$money = substr($result,304,4);
$price = substr($result,308,4);

//사이버 머니 확인
//※ 사용자 잔액조회 결과 수신시 에러가 발생할 경우 기본값으로 '0'원을 넣지 마시고
//에러코드 값을 출력하여 꼭 사용자들이 화면에서 에러를 확인 할 수 있도록 개발하시기 바랍니다.
$money=$packettest->byte2str($money);
//건당금액
$price=$packettest->byte2str($price);


$result = sql_query("select * from SMS_CONFIG");
while($row = sql_fetch_array($result)){
    $get_Config[] = $row;
}

foreach($get_Config as $_idx=>$rec){
    $CFG[trim($rec['SKEY'])] = trim($rec['VALUE']);
}