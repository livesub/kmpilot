<?php
Class SURESMS{
	var $_Table_;
	var $sCode,$cfg;

	function SMS(){
		global $tb,$SITE,$_site,$CFG;
		$this->_Tables = Array(
			"cms_sms_setup"=>$tb["cms_sms_setup"],
			"cms_sms_result"=>$tb["cms_sms_result"],
			"cms_sms_data"=>$tb["cms_sms_data"],
		);
		$this->returnNum = explode('-',$CFG['cms_sms_number']);
	}
	function insertSmsData($R_DATA){
		global $SITE,$DB;
		$returnNum	  = explode('-',$R_DATA['s_reqphone']);
		if($R_DATA['s_msgflag']){
			switch($R_DATA['s_msgflag']){
				case "mms":	//장문
					$retType = "M";
				break;
				case "sms":	//단문
					$retType = "S";
				break;
			}
		}
		if($R_DATA['sendtype']){
			switch($R_DATA['sendtype']){
				case "cms":	//관리자
					$retSendType = "C";
				break;
				case "portal"://사이트
					$retSendType = "P";
				break;
			}
		}
		$DATA = array(
		"SMS_TYPE"		=> $retType, // 문자 구분자
		"SEND_TYPE"		=> $retSendType, // 발송 구분자
		"SPHONE1"		=> $returnNum[0], // 회신번호
		"SPHONE2"		=> $returnNum[1], // 회신번호
		"SPHONE3"		=> $returnNum[2], // 회신번호
		"SMS_MSG"		=> $R_DATA['s_message'], //송신메시지
		"S_COUNT"		=> $R_DATA['s_count'], //총 수신자
		"REG_DATE"		=> time()
		);

		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "INSERT INTO CMS_SMS_DATA SET " . implode(",",$qry_arr);
		$result = sql_query($qry);
		$MaxID = sql_insert_id();

		return  $MaxID;
	}


	function insertKakaoData($R_DATA){
		global $SITE,$DB;
		$returnNum	  = explode('-',$R_DATA['s_reqphone']);
		if($R_DATA['s_msgflag']){
			switch($R_DATA['s_msgflag']){
				case "kakao":	//카카오
					$retType = "K";
				break;
			}
		}
		if($R_DATA['sendtype']){
			switch($R_DATA['sendtype']){
				case "k":
					$retSendType = "K";
				break;
			}
		}
		$DATA = array(
		"SMS_TYPE"		=> $retType, // 문자 구분자
		"SEND_TYPE"		=> $retSendType, // 발송 구분자
		"SPHONE1"		=> $returnNum[0], // 회신번호
		"SPHONE2"		=> $returnNum[1], // 회신번호
		"SPHONE3"		=> $returnNum[2], // 회신번호
		"SMS_MSG"		=> $R_DATA['s_message'], //송신메시지
		"S_COUNT"		=> $R_DATA['s_count'], //총 수신자
		"REG_DATE"		=> time()
		);

		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "INSERT INTO CMS_SMS_DATA SET " . implode(",",$qry_arr);
		$result = sql_query($qry);
		$MaxID = sql_insert_id();

		return  $MaxID;
	}

	function insertSmsUserData($R_DATA){
		global $SITE,$DB;
		$DATA = array(
			"PARENTIDX"		=> $R_DATA['g_idx'], //수신자 IDX값
			"USER_ID"		=> $R_DATA['mb_id'], //수신자 아이디
			"RPHONE1"		=> $R_DATA['callphone1'], //수신번호 앞자리
			"RPHONE2"		=> $R_DATA['callphone2'], //수신번호 중간자리
			"RPHONE3"		=> $R_DATA['callphone3'], //수신번호 뒷자리
			"RECV_NAME"		=> $R_DATA['mb_name'], //수신자명
			"LINK_FILE"		=> $R_DATA['link_file'], //데이터 링크
			"R_MSG"			=> $R_DATA['r_message'], //문자내용
			"REG_DATE"		=> time()
		);

		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "INSERT INTO CMS_SMS_RESULT SET " . implode(",",$qry_arr);
		$result = sql_query($qry);
		$MaxID = sql_insert_id();
		return  $MaxID;
	}

	function insertKakaoUserData($R_DATA){
		global $SITE,$DB;
		$DATA = array(
			"PARENTIDX"		=> $R_DATA['g_idx'], //수신자 IDX값
			"USER_ID"		=> $R_DATA['mb_id'], //수신자 아이디
			"RPHONE1"		=> $R_DATA['callphone1'], //수신번호 앞자리
			"RPHONE2"		=> $R_DATA['callphone2'], //수신번호 중간자리
			"RPHONE3"		=> $R_DATA['callphone3'], //수신번호 뒷자리
			"RECV_NAME"		=> $R_DATA['mb_name'], //수신자명
			"LINK_FILE"		=> $R_DATA['link_file'], //데이터 링크
			"R_MSG"			=> $R_DATA['r_message'], //문자내용
			"REG_DATE"		=> time()
		);

		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "INSERT INTO CMS_SMS_RESULT SET " . implode(",",$qry_arr);
		$result = sql_query($qry);
		$MaxID = sql_insert_id();
		return  $MaxID;
	}

	function UpdateSmsUserData($R_ID,$RESULT){
		global $SITE,$DB;

		if($RESULT=="O"){
			$retSendResult = "1";
			$RESULT_TMP = "1";
		}else{
			$retSendResult = "0";
			$RESULT_TMP = "0";
		}
		$DATA = array(
			"SEND_RESULT"	=> $RESULT_TMP,
			"RECEIVE"		=> $retSendResult
		);

		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "UPDATE CMS_SMS_RESULT SET " . implode(",",$qry_arr). " where idx='".$R_ID."'" ;
		$result = sql_query($qry);
	}

	function UpdateSmsUserData2($R_ID,$RESULT){
		global $SITE,$DB;

		if($RESULT=="2"){	//서버 오류 일시
			$retSendResult = "0";
			$RESULT = "0";
		}elseif($RESULT=="0"){	//슈어엠 오류가 아닌 리턴 값 오류 일때
			$retSendResult = "1";
			$RESULT = "0";
		}else{
			$retSendResult = "1";
			$RESULT = "1";
		}

		$DATA = array(
			"SEND_RESULT"	=> $RESULT,
			"RECEIVE"		=> $retSendResult
		);

		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "UPDATE CMS_SMS_RESULT SET " . implode(",",$qry_arr). " where idx='".$R_ID."'" ;
		$result = sql_query($qry);
	}

	function UpdateKakaoUserData($R_ID,$RESULT){
		global $SITE,$DB;

		if($RESULT=="O"){
			$retSendResult = "1";
		}else{
			$retSendResult = "0";
		}
/*
		$DATA = array(
			"SEND_RESULT"	=> $RESULT,
			"RECEIVE"		=> $retSendResult
		);
*/
		$DATA = array(
			"SEND_RESULT"	=> 1,
			"RECEIVE"		=> $retSendResult
		);
		foreach ($DATA as $key=>$val) $qry_arr[] = $key ." = ". quote($val);
		$qry = "UPDATE CMS_SMS_RESULT SET " . implode(",",$qry_arr). " where idx='".$R_ID."'" ;
		$result = sql_query($qry);
	}

	//SMS 스킨등록
	function create_sms_skin($R_DATA){
		global $DB;
		$qry = "select count(*) from ".$this->_Tables['cms_sms_setup']." where SMS_TYPE ='".$R_DATA["sms_type"]."'";
		$CHK_DATA = $DB->sql_fetch($qry);
		if($CHK_DATA[0]>0){
			$set_state = "0";
		}else{
			$set_state = "1";
		}
		$DATA = array(
			"SMS_NAME"		=> $R_DATA['sms_name'],
			"SMS_CONT"		=> $R_DATA['sms_cont'],
			"SMS_CONT_LEN"	=> strlen(charConvert($R_DATA["sms_cont"],2)),
			"SMS_TYPE"		=> $R_DATA['sms_type'],
			"SMS_STATE"		=> $set_state,
			"REG_DATE"		=> time(),
			"REG_IP"		=> $_SERVER["REMOTE_ADDR"]
		);
		$qry = $DB->insertQuery($this->_Tables['cms_sms_setup'],$DATA);
		$result = $DB->dbQuery($qry);
	}
	//SMS 스킨수정
	function modify_sms_skin($idx,$R_DATA){
		global $SITE,$DB;

		$DATA = array(
			"SMS_NAME"		=> $R_DATA['sms_name'],
			"SMS_CONT"		=> $R_DATA['sms_cont'],
			"SMS_CONT_LEN"	=> strlen(charConvert($R_DATA["sms_cont"],2)),
			"SMS_TYPE"		=> $R_DATA['sms_type'],
			"MODIFY_DATE"	=> time(),
			"MODIFY_IP"		=> $_SERVER["REMOTE_ADDR"]
		);
		$qry = $DB->updateQuery($this->_Tables['cms_sms_setup'],$DATA,"trim(IDX)='".$idx."'");
		$result = $DB->dbQuery($qry);
	}

	// SMS 스킨삭제
	function delete_sms_skin($R_DATA){
		global $SITE,$DB;
		$qry = $DB->deleteQuery($this->_Tables['cms_sms_setup'],"trim(IDX)='".$R_DATA['idx']."'");
		if ($qry) $result =$DB->dbQuery($qry);
	}

	function send_mobile($R_DATA,$FILES=null){
		global $SITE,$DB,$MEM,$CFG,$WebApp,$Wapp,$packettest,$usercode,$username,$deptcode,$deptname,$UserCode,$DeptCode,$DeptName;

		if($R_DATA['sendtype']=="cms"){

			$bytelimitChk	= strlen(charConvert($R_DATA["s_message"],2));
			$returnNum		= explode('-',$R_DATA['s_reqphone']);
			$to_group		= $R_DATA['phone_num'];

			if($R_DATA['s_msgflag']=="sms" && $bytelimitChk < 90){
				include_once G5_ADMIN_PATH."/sms_admin/lib/common/suremcfg.php";
				include_once G5_ADMIN_PATH."/sms_admin/lib/common/common.php";
				$packettest = new SuremPacket;
				if(count($to_group)){
					$G_DATA = array(
						"s_msgflag"		=> "sms", //문자발송 타입
						"sendtype"		=> $R_DATA['sendtype'], //발송 구분자
						"s_reqphone"	=> $R_DATA['s_reqphone'], //회신번호
						"s_message"		=> $R_DATA["s_message"], //송신메시지
						"s_count"		=> count($to_group) //발신갯수
					);

					$group_result = $this->insertSmsData($G_DATA); // sms 발송 데이터 저장
				}
				for ($i=0; $i<count($to_group);$i++){
					if ($to_group[$i]!=""){

						unset($to_sms_info[0]);

						$to_sms_info = explode("|",trim($to_group[$i]));
						/* 휴대폰에 - 문자열 찾기 */
						if (preg_match('/-/', $to_sms_info[2])){
							$setNumSeq = explode("-",$to_sms_info[2]);
						}else{
							$setNumSeq = array(substr($to_sms_info[2],0,3),substr($to_sms_info[2],3,-4),substr($to_sms_info[2],-4,4));
						}

						$DATA = array(
							"sendtype"		=> $R_DATA['s_msgflag'], //문자발송 타입
							"mb_id"			=> $to_sms_info[1], //수신자 ID 값
							"mb_name"		=> $to_sms_info[0], //회원이름
							"callphone1"	=> $setNumSeq[0], //수신번호 앞자리
							"callphone2"	=> $setNumSeq[1], //수신번호 중간자리
							"callphone3"	=> $setNumSeq[2], //수신번호 마지막자리
							"r_message"		=> $R_DATA['s_message'],
							"g_idx"			=> $group_result //송신메시지
						);
						$record_result	= $this->insertSmsUserData($DATA); // sms 발송 데이터 저장

						/* SMS 개인발송 */
						if(trim($to_sms_info[2])!=""){
							$SeqNo			= $record_result;
							$callphone1		= $setNumSeq[0];
							$callphone2		= $setNumSeq[1];
							$callphone3		= $setNumSeq[2];
							//$callmessage	= charConvert($R_DATA["s_message"],2);
							$callmessage	= $R_DATA["s_message"];
							$rdate			= "00000000";
							$rtime			= "000000";
							$reqphone1		= $returnNum[0];
							$reqphone2		= $returnNum[1];
							$reqphone3		= $returnNum[2];
							$callname		= "";
//							$result=$packettest->sendsms($SeqNo,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname);
							$result=$this->sendsms2($SeqNo,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname,$CFG['cms_sms_number']);
							//$res =substr($result,94,1);
							if($result == "success"){
								$res = 1;
							}else if($result == "server_error"){
								$res = 2;	//슈어엠 서버 에러 일때 (500,403 기타 등등)
							}else{
								$res = 0;	//슈어엠 오류가 아닌 리턴 값 오류 일때
							}
							$this->UpdateSmsUserData2($record_result,$res);
						}
					}
				}
			}
			if($R_DATA['s_msgflag']=="mms" || $bytelimitChk > 90){
				include_once G5_ADMIN_PATH."/sms_admin/lib/common/suremcfg_mms.php";
				include_once G5_ADMIN_PATH."/sms_admin/lib/common/common_mms.php";

				$packettest = new MmsPacket;
				$smsSubject = $CFG["cms_sms_title"]; //mms 타입설정

				if(count($to_group)){
					$G_DATA = array(
						"s_msgflag"		=> "mms", //문자발송 타입
						"sendtype"		=> $R_DATA['sendtype'], //발송 구분자
						"s_reqphone"	=> $R_DATA['s_reqphone'], //회신번호
						"s_message"		=> $R_DATA["s_message"], //송신메시지
						"s_count"		=> count($to_group) //발신갯수
					);
					$group_result = $this->insertSmsData($G_DATA); // sms 발송 데이터 저장
				}

				for ($i=0; $i<count($to_group);$i++){
					if ($to_group[$i]!=""){
						unset($to_sms_info[0]);
						$to_sms_info = explode("|",trim($to_group[$i]));
						/* 휴대폰에 - 문자열 찾기 */
						if (preg_match('/-/', $to_sms_info[2])){
							$setNumSeq = explode("-",$to_sms_info[2]);
						}else{
							$setNumSeq = array(substr($to_sms_info[2],0,3),substr($to_sms_info[2],3,-4),substr($to_sms_info[2],-4,4));
						}
						$DATA = array(
							"sendtype"		=> $R_DATA['s_msgflag'], //문자발송 타입
							"mb_id"			=> $to_sms_info[1], //수신자 ID 값
							"mb_name"		=> $to_sms_info[0], //회원이름
							"callphone1"	=> $setNumSeq[0], //수신번호 앞자리
							"callphone2"	=> $setNumSeq[1], //수신번호 중간자리
							"callphone3"	=> $setNumSeq[2], //수신번호 마지막자리
							"r_message"		=> $R_DATA['s_message'],
							"g_idx"			=> $group_result //송신메시지
						);
						$record_result	= $this->insertSmsUserData($DATA); // sms 발송 데이터 저장
						/* SMS 개인발송 */
						if(trim($to_sms_info[2])!=""){

							$SeqNo		= $record_result;								//고객사측 일련번호
							$CallPhone	= $setNumSeq[0].$setNumSeq[1].$setNumSeq[2];									//수신번호 ex)01012345678
							$ReqPhone	= $returnNum[0].$returnNum[1].$returnNum[2];	//회신번호 ex)01078454545
							$Time		= "";											//안 넣었을 경우 즉시 발송 예약시 ex) 20991225231100
							//$Subject	= charConvert($smsSubject,2);					//메시지 제목
							$Subject	= $smsSubject;					//메시지 제목
							//$Msg		= charConvert($R_DATA["s_message"],2);			//메시지 내용
							$Msg		= $R_DATA["s_message"];			//메시지 내용
							$filepath1	= $R_DATA['filepath1'];							//파일경로1
							$filepath2	= $R_DATA['filepath2'];							//파일경로2
							$filepath3	= "";							//파일경로3

							$result=$this->SendMms2($SeqNo, $CallPhone, $ReqPhone, $Time, $Subject, $Msg,$filepath1,$filepath2,$filepath3,$CFG['cms_sms_number']);

							if($result == "success"){
								$res = 1;
							}else if($result == "server_error"){
								$res = 2;	//슈어엠 서버 에러 일때 (500,403 기타 등등)
							}else{
								$res = 0;	//슈어엠 오류가 아닌 리턴 값 오류 일때
							}
							$this->UpdateSmsUserData2($record_result,$res);
						}
					}
				}
			}
		}
		if($R_DATA['sendtype']=="portal"){
			$bytelimitChk	= strlen(charConvert($R_DATA["CONTENT"],2));
			$returnNum		= explode('-',$CFG['cms_sms_number']);
			$link_url		= $CFG['cms_DomainURL'];

			$to_group = explode(",",$R_DATA["SEND_MEMBER_ID"]);

			// 발송원페이지 링크 생성 시 MMS 강제 실행
			if($R_DATA['IS_MAKE_USE']){
				include_once G5_ADMIN_PATH."/sms_admin/lib/common/suremcfg_mms.php";
				include_once G5_ADMIN_PATH."/sms_admin/lib/common/common_mms.php";
				$packettest = new MmsPacket;

				if(count($to_group)){
					$G_DATA = array(
						"s_msgflag"		=> "mms", //문자발송 타입
						"sendtype"		=> $R_DATA['sendtype'], //발송 구분자
						"s_reqphone"	=> $CFG['cms_sms_number'], //발송 구분자
						"s_message"		=> $R_DATA["CONTENT"], //송신메시지
						"s_count"		=> count($to_group) //송신메시지
					);
					$group_result = $this->insertSmsData($G_DATA); // sms 발송 데이터 저장
				}
				for ($i=0; $i<count($to_group);$i++){
					$ret_data = $MEM->getMember($to_group[$i]);
					if ($ret_data["USER_MOBILE"]!=""){
						if($R_DATA['IS_MAKE_USE']){
							$data_link = $CFG['cms_DomainURL']."/global.file.php?sCode=".$R_DATA["sCode"]."&pCode=".$R_DATA['pCode']."&BdCode=".$R_DATA['resultBdCode']."&log_idx=".$group_result."&mode=m_read&idx=".$R_DATA["resultId"]."&mb_id=".$ret_data["USER_ID"];
						}else{
							$data_link = $CFG['cms_DomainURL']."/".$R_DATA["sCode"]."/?pCode=".$R_DATA['pCode']."&mode=view&idx=".$R_DATA['resultId'];
						}
						$sms_contents = strtr($R_DATA['CONTENT'],array(
							'{DATA_ORG}'	=>$data_link,
							'{SITE_URL}'	=>$link_url,
							'{NAME}'		=>$ret_data["USER_NAME"]
						));
						/* 휴대폰에 - 문자열 찾기 */
						if (preg_match('/-/', $ret_data["USER_MOBILE"])){
							$setNumSeq = explode("-",$ret_data["USER_MOBILE"]);
						}else{
							$setNumSeq = array(substr($ret_data["USER_MOBILE"],0,3),substr($ret_data["USER_MOBILE"],3,-4),substr($ret_data["USER_MOBILE"],-4,4));
						}
						$DATA = array(
							"sendtype"		=> "mms", //문자발송 타입
							"mb_id"			=> $ret_data["USER_ID"], //수신자 ID 값
							"mb_name"		=> $ret_data["USER_NAME"], //회원이름
							"callphone1"	=> $setNumSeq[0], //수신번호 앞자리
							"callphone2"	=> $setNumSeq[1], //수신번호 중간자리
							"callphone3"	=> $setNumSeq[2], //수신번호 마지막자리
							"r_message"		=> $sms_contents,
							"link_file"		=> $data_link, //데이터 링크
							"g_idx"			=> $group_result //송신메시지
						);
						$record_result	= $this->insertSmsUserData($DATA); // sms 발송 데이터 저장

						if(trim($ret_data["USER_MOBILE"])!=""){
							$SeqNo		= $record_result;								//고객사측 일련번호
							$CallPhone	= $setNumSeq[0].$setNumSeq[1].$setNumSeq[2];	//수신번호 ex)01012345678
							$ReqPhone	= $returnNum[0].$returnNum[1].$returnNum[2];	//회신번호 ex)01078454545
							$Time		= "";											//안 넣었을 경우 즉시 발송 예약시 ex) 20991225231100
							$Subject	= charConvert($R_DATA["SUBJECT"],2);				//메시지 제목
							$Msg		= charConvert($sms_contents,2);				//메시지 내용
							$filepath1="";												//파일경로1
							$filepath2="";												//파일경로2
							$filepath3="";												//파일경로3

							$result=$packettest->SendMms($SeqNo, $CallPhone, $ReqPhone, $Time, $Subject, $Msg,$filepath1,$filepath2,$filepath3);
							$this->UpdateSmsUserData($record_result,$result);
						};
					}
				}
			}else{
				if($bytelimitChk < 90){
					include_once G5_ADMIN_PATH."/sms_admin/lib/common/suremcfg.php";
					include_once G5_ADMIN_PATH."/sms_admin/lib/common/common.php";

					$packettest = new SuremPacket;

					if(count($to_group)){
						$G_DATA = array(
							"s_msgflag"		=> "sms", //문자발송 타입
							"sendtype"		=> $R_DATA['sendtype'], //발송 구분자
							"s_reqphone"	=> $CFG['cms_sms_number'], //발송 구분자
							"s_message"		=> $R_DATA["CONTENT"], //송신메시지
							"s_count"		=> count($to_group)
						);
						$group_result = $this->insertSmsData($G_DATA); // sms 발송 데이터 저장
					}
					for ($i=0; $i<count($to_group);$i++){

						$ret_data = $MEM->getMember($to_group[$i]);
						if ($ret_data["USER_MOBILE"]!=""){
							if($R_DATA['IS_MAKE_USE']){
								$data_link = $CFG['cms_DomainURL']."/global.file.php?sCode=".$R_DATA["sCode"]."&pCode=".$R_DATA['pCode']."&BdCode=".$R_DATA['resultBdCode']."&log_idx=".$group_result."&mode=m_read&idx=".$R_DATA["resultId"]."&mb_id=".$ret_data["USER_ID"];
							}else{
								$data_link = $CFG['cms_DomainURL']."/".$R_DATA["sCode"]."/?pCode=".$R_DATA['pCode']."&mode=view&idx=".$R_DATA['resultId'];
							}
							$sms_contents = strtr($R_DATA['CONTENT'],array(
								'{DATA_ORG}'	=>$data_link,
								'{SITE_URL}'	=>$link_url,
								'{NAME}'		=>$ret_data["USER_NAME"]
							));
							/* 휴대폰에 - 문자열 찾기 */
							if (preg_match('/-/', $ret_data["USER_MOBILE"])){
								$setNumSeq = explode("-",$ret_data["USER_MOBILE"]);
							}else{
								$setNumSeq = array(substr($ret_data["USER_MOBILE"],0,3),substr($ret_data["USER_MOBILE"],3,-4),substr($ret_data["USER_MOBILE"],-4,4));
							}
							$DATA = array(
								"sendtype"		=> "sms", //문자발송 타입
								"mb_id"			=> $ret_data["USER_ID"], //수신자 ID 값
								"mb_name"		=> $ret_data["USER_NAME"], //회원이름
								"callphone1"	=> $setNumSeq[0], //수신번호 앞자리
								"callphone2"	=> $setNumSeq[1], //수신번호 중간자리
								"callphone3"	=> $setNumSeq[2], //수신번호 마지막자리
								"r_message"		=> $sms_contents,
								"g_idx"			=> $group_result //송신메시지
							);
							$record_result	= $this->insertSmsUserData($DATA); // sms 발송 데이터 저장
							if(trim($ret_data["USER_MOBILE"])!=""){
								$SeqNo			= $record_result;
								$callphone1		= $setNumSeq[0];
								$callphone2		= $setNumSeq[1];
								$callphone3		= $setNumSeq[2];
								$callmessage	= charConvert($sms_contents,2);
								$rdate			= "00000000";
								$rtime			= "000000";
								$reqphone1		= $returnNum[0];
								$reqphone2		= $returnNum[1];
								$reqphone3		= $returnNum[2];
								$callname		= "";
								$result=$packettest->sendsms($SeqNo,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname);
								$res =substr($result,94,1);
								$this->UpdateSmsUserData($record_result,$res);
							}
						}
					}
				}
				if($bytelimitChk > 90){
					include_once G5_ADMIN_PATH."/sms_admin/lib/common/suremcfg_mms.php";
					include_once G5_ADMIN_PATH."/sms_admin/lib/common/common_mms.php";
					$packettest = new MmsPacket;

					if(count($to_group)){
						$G_DATA = array(
							"s_msgflag"		=> "mms", //문자발송 타입
							"sendtype"		=> $R_DATA['sendtype'], //발송 구분자
							"s_reqphone"	=> $CFG['cms_sms_number'], //발송 구분자
							"s_message"		=> $R_DATA["CONTENT"], //송신메시지
							"s_count"		=> count($to_group)
						);
						$group_result = $this->insertSmsData($G_DATA); // sms 발송 데이터 저장
					}
					for ($i=0; $i<count($to_group);$i++){

						if(preg_match("/[a-zA-Z0-9]/",$to_group[$i])){
							$member = sql_fetch("select * from {$g5['member_table']} where mb_id='".$to_group[$i]."'");
						}
						$member["USER_TYPE_ARR"]= $this->getMemberShip($member["mb_id"]);

						if ($ret_data["USER_MOBILE"]!=""){
							if($R_DATA['IS_MAKE_USE']){
								$data_link = $CFG['cms_DomainURL']."/global.file.php?sCode=".$R_DATA["sCode"]."&pCode=".$R_DATA['pCode']."&BdCode=".$R_DATA['resultBdCode']."&log_idx=".$group_result."&mode=m_read&idx=".$R_DATA["resultId"]."&mb_id=".$ret_data["mb_id"];
							}else{
								$data_link = $CFG['cms_DomainURL']."/".$R_DATA["sCode"]."/?pCode=".$R_DATA['pCode']."&mode=view&idx=".$R_DATA['resultId'];
							}
							$sms_contents = strtr($R_DATA['CONTENT'],array(
								'{DATA_ORG}'	=>$data_link,
								'{SITE_URL}'	=>$link_url,
								'{NAME}'		=>$ret_data["USER_NAME"]
							));
							/* 휴대폰에 - 문자열 찾기 */
							if (preg_match('/-/', $ret_data["USER_MOBILE"])){
								$setNumSeq = explode("-",$ret_data["USER_MOBILE"]);
							}else{
								$setNumSeq = array(substr($ret_data["USER_MOBILE"],0,3),substr($ret_data["USER_MOBILE"],3,-4),substr($ret_data["USER_MOBILE"],-4,4));
							}
							$DATA = array(
								"sendtype"		=> "mms", //문자발송 타입
								"mb_id"			=> $ret_data["USER_ID"], //수신자 ID 값
								"mb_name"		=> $ret_data["USER_NAME"], //회원이름
								"callphone1"	=> $setNumSeq[0], //수신번호 앞자리
								"callphone2"	=> $setNumSeq[1], //수신번호 중간자리
								"callphone3"	=> $setNumSeq[2], //수신번호 마지막자리
								"r_message"		=> $sms_contents,
								"link_file"		=> $data_link, //데이터 링크
								"g_idx"			=> $group_result //송신메시지
							);
							$record_result	= $this->insertSmsUserData($DATA); // sms 발송 데이터 저장

							if(trim($ret_data["USER_MOBILE"])!=""){
								$SeqNo		= $record_result;								//고객사측 일련번호
								$CallPhone	= $setNumSeq[0].$setNumSeq[1].$setNumSeq[2];	//수신번호 ex)01012345678
								$ReqPhone	= $returnNum[0].$returnNum[1].$returnNum[2];	//회신번호 ex)01078454545
								$Time		= "";											//안 넣었을 경우 즉시 발송 예약시 ex) 20991225231100
								$Subject	= charConvert($R_DATA["SUBJECT"],2);				//메시지 제목
								$Msg		= charConvert($sms_contents,2);				//메시지 내용
								$filepath1="";												//파일경로1
								$filepath2="";												//파일경로2
								$filepath3="";												//파일경로3

								$result=$packettest->SendMms($SeqNo, $CallPhone, $ReqPhone, $Time, $Subject, $Msg,$filepath1,$filepath2,$filepath3);
								$this->UpdateSmsUserData($record_result,$result);
							};
						}
					}
				}
			}
		}
	}


	function getAuth($rData){
		global $DB;
		$qry	= "select count(*) as CNT from ".$this->_Tables["cms_sms_result"]." where Receive='1' AND SeqNo='".$rData."'";
		$DATA	= $DB->sql_fetch($qry);
		return $DATA['CNT'];
	}
	function resultSMS($R_DATA){
		global $DB;
		$DATA = array(
			"ERRCODE"	=> $R_DATA['Error'],
			"RESULT"	=> $R_DATA['Result'],
		);
		$qry = $DB->updateQuery($this->_Tables['cms_sms_result'],$DATA,"trim(IDX)='".$R_DATA['SeqNo']."'");
		$result = $DB->dbQuery($qry);
	}
	function delete_sms_data($R_DATA){
		global $DB;

		$getData = $this->getSendData($R_DATA['idx']);
		$qry = "select count(*) as CNT from ".$this->_Tables["cms_sms_result"]." where trim(PARENTIDX)='".$R_DATA['idx']."'";
		$row = $DB->sql_fetch($qry);
		if(!$row["CNT"]){
			$qry2 = $DB->deleteQuery($this->_Tables["cms_sms_data"],"trim(IDX)='".$getData['PARENTIDX']."'");
			if ($qry2) $result =$DB->dbQuery($qry2);
		}

	}
	//////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////문자 주소록 쿼리   /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////
	function getSendData($IDX){
		global $DB;
		$ret_data = $DB->sql_fetch("select * from ".$this->_Tables["cms_sms_data"]." where trim(IDX)='".$IDX."'");
		return $ret_data;
	}
	function getSendResultData($IDX){
		global $DB;
		$ret_data = $DB->sql_fetch("select * from ".$this->_Tables["cms_sms_result"]." where trim(IDX)='".$IDX."'");
		return $ret_data;
	}
	function getSendUserCnt($idx){
		global $DB,$MEM;
		if($idx){
			$qry = "select *,count(*) as totalCount from ".$this->_Tables["cms_sms_result"]." where trim(PARENTIDX)='".$idx."'";
			$result = $DB->sql_fetch($qry);
			return $result;
		}
	}
	function getRecentList(){
		global $DB;

		$yy_s 	= date("Y");
		$mm_s	= date("m");
		$dd_s	= date("d");
		$mm_s	= sprintf("%02d",$mm_s);
		$dd_s	= sprintf("%02d",$dd_s);
		$s_date=mktime(0, 0, 0, $mm_s, $dd_s-7, $yy_s);
		$e_date=mktime(0, 0, 0, $mm_s, $dd_s+1, $yy_s);
		$qry ="select * from ".$this->_Tables["cms_sms_result"]." where REG_DATE BETWEEN '".$s_date."' AND '".$e_date."' GROUP BY RPHONE3 ORDER BY IDX";
		$get_list = $DB->getRows($qry);
		return $get_list;
	}
	//////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////SMS 및 LMS 결과수신/////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////
	function &getSMS($R_DATA){
		global $DB,$MEM;
		if($R_DATA){
			$qry = "select * from ".$this->_Tables["cms_sms_result"]." where trim(IDX)='".$R_DATA['SeqNo']."'";
			$result = $DB->sql_fetch($qry);
			return $result;
		}
	}
	function UpdateSMS($R_DATA){
		global $DB;
		$DATA = array(
			"Receive"		=> $R_DATA['Receive'],
			"ReceiveDetail"	=> $R_DATA['ReceiveDetail'],
		);
		$qry = $DB->updateQuery($this->_Tables['cms_sms_result'],$DATA,"SeqNo='".$R_DATA['SeqNo']."'");
		$result = $DB->dbQuery($qry);
	}

	function up_img($FILE){
		global $DB,$Wapp,$SITE;
		$mktime = date("Ymd",time());

        $image_regex = "/(\.(gif|jpe?g|png))$/i";
        if (!preg_match($image_regex, $FILE["img1"]["name"])) {
            alert($FILE["img1"]["name"] . '은(는) 이미지 파일이 아닙니다.');
            exit;
        }

        $uploadPathSMS = G5_DATA_PATH."/smsFile/".$mktime;
        if(!is_dir($uploadPathSMS))	@exec("mkdir -p -m0777 ".$uploadPathSMS);

        if($FILE["img1"]["size"] < 500000 ){

			//파일업로드
			if ($FILE["img1"]["name"] && $FILE["img1"]["size"]>0){

				//이미지 파일 체크
				if(checkExe($FILE["img1"]["name"] ,array("jpg"))){
					$fileExt = getFileExt($FILE["img1"]["name"]);
					$newFileName = md5(mktime().$FILE["img1"]["name"]).".".$fileExt;
					$DATA['pathFile'] = $uploadPathSMS."/".$newFileName;
					$DATA['fileName'] = $newFileName;
					$DATA['fileFolder'] = $mktime;
					upload($FILE["img1"],$newFileName,$uploadPathSMS);
				}else{
                    alert("이미지 업로드에 실패했습니다.\\n지정한 jpg 사진파일이 아닙니다.\\n정보수정을 통해 다시 업로드해주시기 바랍니다.");
					exit;
				}
            }
		}else{
            //$Wapp->alert("500KB 이미지까지 첨부 가능합니다.");
            alert("500KB 이미지까지 첨부 가능합니다.");
			exit;
        }

		return $DATA;
	}

	function up_img_kakao($FILE){
		global $DB,$Wapp,$SITE;
		$mktime = date("Ymd",time());

        $image_regex = "/(\.(gif|jpe?g|png))$/i";
        if (!preg_match($image_regex, $FILE["img1"]["name"])) {
            alert($FILE["img1"]["name"] . '은(는) 이미지 파일이 아닙니다.');
            exit;
        }

        $uploadPathSMS = G5_DATA_PATH."/smsFile/".$mktime;
        if(!is_dir($uploadPathSMS))	@exec("mkdir -p -m0777 ".$uploadPathSMS);

        if($FILE["img1"]["size"] < 500000 ){

			//파일업로드
			if ($FILE["img1"]["name"] && $FILE["img1"]["size"]>0){

				//이미지 파일 체크
				if(checkExe($FILE["img1"]["name"] ,array("jpg"))){
					$fileExt = getFileExt($FILE["img1"]["name"]);
					$newFileName = md5(mktime().$FILE["img1"]["name"]).".".$fileExt;
					$DATA['pathFile'] = $uploadPathSMS."/".$newFileName;
					$DATA['fileName'] = $newFileName;
					$DATA['fileFolder'] = $mktime;
					upload($FILE["img1"],$newFileName,$uploadPathSMS);

					$thumb = thumbnail($newFileName, $uploadPathSMS, $uploadPathSMS, 500, 600, true, true);
					if($thumb){
						@unlink($DATA['pathFile']);
						$DATA['fileName'] = $thumb;
						$DATA['pathFile'] = $uploadPathSMS."/".$thumb;
					}
				}else{
                    alert("이미지 업로드에 실패했습니다.\\n지정한 jpg 사진파일이 아닙니다.\\n정보수정을 통해 다시 업로드해주시기 바랍니다.");
					exit;
				}
            }
		}else{
            //$Wapp->alert("500KB 이미지까지 첨부 가능합니다.");
            alert("500KB 이미지까지 첨부 가능합니다.");
			exit;
        }

		return $DATA;
	}


	function delete_sms_img($R_DATA){
		$chkCount =  0;

		if($R_DATA['img_path1']){
			if (is_file($R_DATA["img_path1"])){
				@unlink($R_DATA["img_path1"]);
				$chkCount +=  0;
			}else{
				$chkCount +=  1;
			}
		}
		if($R_DATA['img_path2']){
			if (is_file($R_DATA["img_path2"])){
				@unlink($R_DATA["img_path2"]);
				$chkCount +=  0;
			}else{
				$chkCount +=  1;
			}
		}
		return $chkCount;
	}

	//카카오 친구톡 추가(2021.01.25 김영섭)
	function send_kakao($R_DATA,$FILES=null){
		global $SITE,$DB,$MEM,$CFG,$WebApp,$Wapp,$packettest,$usercode,$username,$deptcode,$deptname,$UserCode,$DeptCode,$DeptName;
		if($R_DATA['sendtype']=="K"){
			$bytelimitChk	= strlen(charConvert($R_DATA["s_message"],2));
			$returnNum		= explode('-',$R_DATA['s_reqphone']);
			$to_group		= $R_DATA['phone_num'];

			if(count($to_group)){
				$G_DATA = array(
					"s_msgflag"		=> "kakao", //문자발송 타입
					"sendtype"		=> $R_DATA['sendtype'], //발송 구분자
					"s_reqphone"	=> $R_DATA['s_reqphone'], //회신번호
					"s_message"		=> $R_DATA["s_message"], //송신메시지
					"s_count"		=> count($to_group) //발신갯수
				);
				$group_result = $this->insertKakaoData($G_DATA); // sms 발송 데이터 저장
			}

			for ($i=0; $i<count($to_group);$i++){
				if ($to_group[$i] != ""){
					unset($to_sms_info[0]);
					$to_sms_info = explode("|",trim($to_group[$i]));
					/* 휴대폰에 - 문자열 찾기 */
					if (preg_match('/-/', $to_sms_info[2])){
						$setNumSeq = explode("-",$to_sms_info[2]);
					}else{
						$setNumSeq = array(substr($to_sms_info[2],0,3),substr($to_sms_info[2],3,-4),substr($to_sms_info[2],-4,4));
					}
					$DATA = array(
						"sendtype"		=> $R_DATA['s_msgflag'], //문자발송 타입
						"mb_id"			=> $to_sms_info[1], //수신자 ID 값
						"mb_name"		=> $to_sms_info[0], //회원이름
						"callphone1"	=> $setNumSeq[0], //수신번호 앞자리
						"callphone2"	=> $setNumSeq[1], //수신번호 중간자리
						"callphone3"	=> $setNumSeq[2], //수신번호 마지막자리
						"r_message"		=> $R_DATA['s_message'],
						"g_idx"			=> $group_result //송신메시지
					);
					$record_result	= $this->insertKakaoUserData($DATA); // sms 발송 데이터 저장

					/* kakao 발송 */
					if(trim($to_sms_info[2])!=""){
						$SeqNo			= $record_result;
						$callphone1		= $setNumSeq[0];
						$callphone2		= $setNumSeq[1];
						$callphone3		= $setNumSeq[2];

						//$callmessage	= charConvert($R_DATA["s_message"],2);
						$callmessage	= $R_DATA["s_message"];			//메시지 내용

						$rdate			= "00000000";
						$rtime			= "000000";
						$reqphone1		= $returnNum[0];
						$reqphone2		= $returnNum[1];
						$reqphone3		= $returnNum[2];
						$callname		= "";
						$filepath1		= $R_DATA['filepath1'];							//파일경로1
						$result = $this->sendkakao($SeqNo,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname,$CFG['cms_sms_number'],$filepath1);

						$res =substr($result,94,1);
						$this->UpdateKakaoUserData($record_result,$res);
					}
				}
			}
		}else{
			alert("잘못된 경로 입니다.","kakao_write.php");
			exit;
		}
		return "kakao";
	}


	function sendkakao($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname,$cms_sms_number,$param_File1=""){
		global $SITE,$DB,$MEM,$CFG,$WebApp,$Wapp,$packettest,$usercode,$username,$deptcode,$deptname,$UserCode,$DeptCode,$DeptName;

		$callphone1_cut = preg_replace('/(0)(\d)/','$2',$callphone1);	//앞자리 0 자르기
		$phone_mk = "82".$callphone1_cut.$callphone2.$callphone3;	//국가번호 포함한 전화번호 (821012345678)

		$curl = curl_init();
		if($param_File1 == ""){
			//이미지 없을때
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://rest.surem.com/alimtalk/v2/json",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",

			CURLOPT_POSTFIELDS => "
			{
				'usercode' : '".$usercode."',		//슈어엠 제공 아이디
				'deptcode' : '".$deptcode."',		//슈어엠 제공 회사코드
				'yellowid_key' : '".G5_YELLOWID_KEY."',	//발신프로필키(40 자)
				'messages' :
					[{
						'type'			:'ft',
						'message_id'	:'".$member."',	//메시지 고유키 값
						'to' 			: '".$phone_mk."',	//국가번호 포함한 전화번호 (821012345678)
						'text' 			: '".$callmessage."',	//전송할 메시지
						'from' 			: '".$cms_sms_number."',
						'reserved_time' : '',	//예약 발송시 예약시간 (YYYYMMDDhhmm - 12 자리) => 미입력시 즉시전송
						're_send' 		: 'Y',	//알림톡 전송 실패시 문자 재전송 여부 Y 는 MMS 로 재전송
						're_text' 		: '".$callmessage."',
						'additional_information': '".$usercode."',
						'buttons' 		:
							[{
								'button_type' : 'WL',
								'button_name' : '슈어엠 이동',
								'button_url' : 'http://www.surem.co.kr'
							},{
								'button_type' : 'MD',
								'button_name' : '메시지 전달'
							}]
					}]
			}",

			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"cache-control: no-cache"
				),
			));
		}else{

			//이미지 있을때
			$File1_json1 = new CURLFILE($param_File1);
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://rest.surem.com/friendtalk/v1/image',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',

				CURLOPT_POSTFIELDS => array(
					"image"	=> $File1_json1,
					"reqJSON" 	=> '{
						"usercode" 		: "'.$usercode.'",		//슈어엠 제공 아이디
						"deptcode" 		: "'.$deptcode.'",		//슈어엠 제공 회사코드
						"yellowid_key" 	: "'.G5_YELLOWID_KEY.'",	//발신프로필키(40 자)
						"messages" : [{
							"message_id"	:"'.$member.'",	//메시지 고유키 값
							"to" 			: "'.$phone_mk.'",	//국가번호 포함한 전화번호 (821012345678)
							"text" 			: "'.$callmessage.'",	//전송할 메시지
							"from" 			: "'.$cms_sms_number.'",
							"reserved_time" : "",	//예약 발송시 예약시간 (YYYYMMDDhhmm - 12 자리) => 미입력시 즉시전송
							"image_url" 	: "",
							"re_send" : "Y",
							"button_set" :	{
								"button_name" : "슈어엠 이동",
								"button_url" : "http://www.surem.co.kr"
							}
						}]
				}'),
				CURLOPT_HTTPHEADER => array(
					"Content-Type: multipart/form-data",
					"cache-control: no-cache"
				),
			));
		}

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}


	function sendsms2($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname,$cms_sms_number){
		global $usercode,$username,$deptcode,$deptname;
		$callphone1_cut = preg_replace('/(0)(\d)/','$2',$callphone1);	//앞자리 0 자르기
		$phone_mk = "82".$callphone1_cut.$callphone2.$callphone3;	//국가번호 포함한 전화번호 (821012345678)
		$ori_phone_mk = $callphone1.$callphone2.$callphone3;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://rest.surem.com/sms/v1/json",
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "POST",

			CURLOPT_POSTFIELDS => "{
				'usercode': '".$usercode."',
				'deptcode': '".$deptcode."',
				'messages':
				[{
					'message_id': '".$member."',
					'to': '".$ori_phone_mk."'
				}],
				'text': '".$callmessage."',
				'from': '".$cms_sms_number."',
				'additional_information': '".$usercode."'
			}",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$data = json_decode($response);

		if ($err) {
		  	//echo "cURL Error #:" . $err;
			return "fail";
		} else {
			if($data == ""){
				return "server_error";
			}else{
				//echo $response;
				return $data->results[0]->result; //success
			}
		}
	}

	function SendMms2($param_SeqNo, $param_CallPhone, $param_ReqPhone, $param_Time, $param_Subject, $param_Msg, $param_File1, $param_File2, $param_File3,$cms_sms_number){
		global $UserCode, $DeptCode;

		$curl = curl_init();
		if($param_File1 == "" && $param_File2 == "" && $param_File3 == "")
		{
			//이미지가 없는 lms
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://rest.surem.com/lms/v1/json',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',

				CURLOPT_POSTFIELDS => "{
					'usercode': '".$UserCode."',
					'deptcode': '".$DeptCode."',
					'messages':
					[{
						'message_id': '".$param_SeqNo."',
						'to': '".$param_CallPhone."'
					}],
					'subject' : '".$param_Subject."',
					'text': '".$param_Msg."',
					'from': '".$cms_sms_number."',
					'additional_information': '".$UserCode."'
				}",
				CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json",
					"cache-control: no-cache"
				),
			));
		}else{
			//이미지 있는 메세지
			if($param_File1 == "") $File1_json1 = "";
			else $File1_json1 = new CURLFILE($param_File1);

			if($param_File2 == "") $File1_json2 = "";
			else $File1_json2 = new CURLFILE($param_File2);

			if($param_File3 == "") $File1_json3 = "";
			else $File1_json3 = new CURLFILE($param_File3);

			if($param_File1 != "" && $param_File2 == "" && $param_File3 == ""){
				//1이미지가 있을때
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'http://rest.surem.com/mms/v1',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',

					CURLOPT_POSTFIELDS => array(
						"image1"	=> $File1_json1,
						"reqJSON" 	=> '{
							"usercode" : "'.$UserCode.'",
							"deptcode" : "'.$DeptCode.'",
							"messages" : [{
								"message_id":"'.$param_SeqNo.'",
								"to" : "'.$param_CallPhone.'"
							}],
							"subject" : "'.$param_Subject.'",
							"text" : "'.$param_Msg.'",
							"from" : "'.$cms_sms_number.'",
							"additional_information": "'.$UserCode.'"
					}'),
					CURLOPT_HTTPHEADER => array(
						"Content-Type: multipart/form-data",
						"cache-control: no-cache"
					),
				));
			}else{
				//2이미지가 있을때
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'http://rest.surem.com/mms/v1',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',

					CURLOPT_POSTFIELDS => array(
						"image1"=> $File1_json1,
						"image2"=> $File1_json2,
						"reqJSON" 	=> '{
							"usercode" : "'.$UserCode.'",
							"deptcode" : "'.$DeptCode.'",
							"messages" : [{
								"message_id":"'.$param_SeqNo.'",
								"to" : "'.$param_CallPhone.'"
							}],
							"subject" : "'.$param_Subject.'",
							"text" : "'.$param_Msg.'",
							"from" : "'.$cms_sms_number.'",
							"additional_information": "'.$UserCode.'"
					}'),
					CURLOPT_HTTPHEADER => array(
						"Content-Type: multipart/form-data",
						"cache-control: no-cache"
					),
				));
			}
		}

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$data = json_decode($response);

		if ($err) {
		  	//echo "cURL Error #:" . $err;
			return "fail";
		} else {
			if($data == ""){
				return "server_error";
			}else{
				//echo $response;
				return $data->results[0]->result; //success
			}
		}
	}
}
?>