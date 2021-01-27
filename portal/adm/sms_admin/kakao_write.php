<?php
$sub_menu = "900310";
include_once("./_common.php");

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = "카카오 친구톡 보내기";

include_once(G5_ADMIN_PATH.'/admin.head.php');
?>
y

<link rel="stylesheet" href="https://kmpilot.or.kr/CMS/_css/common.css" type="text/css" />
<link rel="stylesheet" href="https://kmpilot.or.kr/CMS/_css/layout.css" type="text/css" />
<link rel="stylesheet" href="https://kmpilot.or.kr/_css/board_default.css" type="text/css" />

<script type="text/javascript">
    CMS_FOLDER = "<?=G5_ADMIN_URL?>/sms_admin";
</script>

<script src="<?=G5_ADMIN_URL?>/sms_admin/sms_js/set_js.js" type="text/javascript"></script>
<script src="<?=G5_ADMIN_URL?>/sms_admin/sms_js/sms.js"></script>
<link rel="stylesheet" type="text/css" href="https://kmpilot.or.kr/CMS/_css/sms.css">

<div id="cont"><!-- 꼭 있어야함 -->
<div class="contents">
	<!--컨텐츠 시작-->

	<div class="visualphone">
		<div id="visualphone-wrap">
			<form name="VisualPhone" id="VisualPhone" method="post" enctype="multipart/form-data" action="./sms_action.php" target="hiddenframe">
			<input id="mode" name="mode" type="hidden" value="kakao">
			<input id="sendtype" name="sendtype" type="hidden" value="k">

			<input id="s_calltype" name="s_calltype" type="hidden" value="0">
			<input id="s_msgflag" name="s_msgflag" type="hidden" value="sms">
			<input id="s_sendtime" name="s_sendtime" type="hidden" value="">

			<input name="s_callphone" name="s_callphone" id="s_callphone" type="hidden" value="">
			<input name="s_userprice" name="s_userprice" id="s_userprice" type="hidden" value="">

			<input id="basic_req" name="basic_req" type="hidden" value="">

			<input name="s_filecnt" id="s_filecnt" type="hidden" value="0">
			<input name="filepath1" id="filepath1" type="hidden" value="">
			<input name="filepath2" id="filepath2" type="hidden" value="">
			<!-- [S] Visualphone -->
			<div class="sect-visualphone">
				<h1 class="blind">비주얼폰</h1>
				<!--
				<div class="subject comm-box" style="display: none;">
					<label for="s_title">제목 :</label>
					<input name="s_title" title="제목을 입력해주세요." class="input-subject" id="s_title" type="text" maxlength="20" value="">
				</div>
				!-->
				<div class="message-area">
					<div class="img-box" id="add_img_box">
						<strong>&lt;첨부 이미지&gt;</strong>
						<div class="img-box-wrap" id="add_img_div"></div>
						<p>이미지를 클릭하시면<br>삭제하실 수 있습니다.</p>
					</div>
					<div class="message-box">
						<textarea name="s_message" class="message-text" id="s_message" rows="1" cols="1"></textarea>
					</div>
					<div class="txt-byte"><span class="txt-message-byte" id="msglen">0</span> / <span class="txt-max-byte" id="max_len">90</span> byte</div>
				</div>
				<!--<
				<div class="btn-message-area">
					<div>
						a class="first" id="savemsg_btn" href="#"><img alt="저장" src="/visualphone/img/btn_save_mesg.gif"></a>
						<a id="loadmsg_btn" href="#"><img alt="열기" src="/visualphone/img/btn_open_mesg.gif"></a>
						<a id="imgadd_btn" href="#"><img alt="첨부" src="/visualphone/img/btn_add_mesg.gif"></a>
						<a id="spchar_btn" href="#"><img alt="특수문자" src="https://kmpilot.or.kr/CMS/_img/sms/btn_sign.gif"></a>
					</div>
				</div>
				!-->
				<div class="btn-phone-area">
					<a class="first" id="pb_btn" href="#"><img alt="주소록" src="https://kmpilot.or.kr/CMS/_img/sms/btn_address.gif"></a>
				</div>
				<div class="phone-area">
					<div class="btn-phone-controll">
						<input name="input_number" title="이름/번호 검색" class="input_text" id="input_number" type="text">
						<input name="ret_input_number" class="input_text" id="ret_input_number" type="hidden">
						<!--<a id="numadd_btn" href="#"><img alt="추가하기" src="https://kmpilot.or.kr/CMS/_img/sms/btn_plus.gif"></a>!-->

						<a class="last" id="allnumdel_btn" href="#"><img alt="전체 삭제하기" src="https://kmpilot.or.kr/CMS/_img/sms/btn_allminus.gif"></a>
						<div class="ly-phone-search ly-div">
							<strong class="blind">번호 검색</strong>
							<div class="ly-wrap">
								<table cellspacing="0" cellpadding="0" summary="번호 검색">
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="sel-phone">
						<ul name="callList" id="callList">
						</ul>
					</div>
					<div class="txt-count-area">
						<span>잔액</span>
						<div class="cnavy"><span class="phone-count" id="sms_money"><strong class="cwhite"><?=number_format($money)?></strong></span> 건</div>
					</div>
					<div class="txt-count-area">
						<span>받는사람</span>
						<div>총 <span class="phone-count" id="addphone_cnt">0</span>명</div>
					</div>
				</div>
				<!--
				<div class="check-phone-area">
					<input name="reserv_chk" title="예약전송" id="reserv_chk" type="checkbox" value="">
					<label for="reserv_chk">예약전송</label>
					<input name="virnum_chk" title="가상번호이용" id="virnum_chk" type="checkbox" value="0">
					<label for="virnum_chk">가상번호이용</label>
				</div>
				!-->
				<div class="regphone-area">
					<span class="txt">회신번호</span>
					<div class="regphone-box">
						<input name="s_reqphone" title="회신번호" id="s_reqphone" type="text" class="c en" value="<?=$CFG['cms_sms_number']?>">
						<!--<a id="morereq_btn" href="#"><img alt="회신번호 더 보기" src="/visualphone/img/sel_down.gif"></a>!-->
					</div>
				</div>
				<!--
				<div class="ly-phone-list ly-div">
					<strong class="blind">회신번호</strong>
					<div class="ly-wrap">
						<table cellspacing="0" cellpadding="0" summary="회신번호">
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<div class="ly-phone-virtual ly-div">
					<strong class="blind">가상번호</strong>
					<div class="ly-wrap">
					</div>
					<div id="monotice">
						<img id="monotice_img" src="/visualphone/img/monotice_new.gif" border="0" usemap="#Map">
					</div>
					<map name="Map">
						<area id="close_noti_btn" href="#" shape="rect" coords="183,117,220,131">
						<area id="mo_detail_btn" href="#" shape="rect" coords="96,110,144,124">
					</map>
				</div>
				!-->
			</div>
			<div class="btn-send-area">
				<a href="#"><img id="msg_send_btn" alt="전송" src="https://kmpilot.or.kr/CMS/_img/sms/btn_send.gif"></a>
				<a href="#"><img id="msg_cancel_btn" alt="취소" src="https://kmpilot.or.kr/CMS/_img/sms/btn_cancel.gif"></a>
			</div>
			<div class="send-type"><img alt="전송타입" src="https://kmpilot.or.kr/CMS/_img/sms/btn_vp_now.gif"></div>
			<div class="img-battery"><img alt="battery" src="https://kmpilot.or.kr/CMS/_img/sms/sms_coin.gif"></div>
			</form>
		</div>

		<!-- [E] Visualphone -->
		<form name="formReceiveList">
			<input name="ReceiveList" id="ReceiveList" type="hidden" value="">
			<input name="ReceiveGroup" id="ReceiveGroup" type="hidden" value="">
		</form>
	</div>


	<!-- 최근번호 레이어 -->
	<div class="ly-recentnum ly-div">
		<strong class="tit-recentnum blind">최근번호</strong>
		<div class="ly-recentnum-wrap">
			<table id="recent_table" cellspacing="0" cellpadding="0" summary="최근번호">
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="btn-phone-new-area">
			<a id="select_btn" href="#"><img alt="선택" src="/visualphone/img/btn_select.gif"></a>
			<a id="delete_btn" href="#"><img alt="삭제" src="/visualphone/img/btn_delet.gif"></a>
			<a class="last" id="alldelete_btn" href="#"><img alt="전체삭제" src="/visualphone/img/btn_alldelet.gif"></a>
		</div>
		<a class="btn-close" href="#"><img alt="닫기" src="https://kmpilot.or.kr/CMS/_img/sms/btn_close.gif"></a>
	</div>


</div>
<script language="javascript">
<!--
	function InputData_SMS(i,subject){
		try{
			selText();
		}catch(err){
			selText1();
		}
		var re, sq, dq, bs, r1, l;
		var temp;
		var msglen;
		re = /cR_/g;
		sq = /sQ_/g;
		dq = /dQ_/g;
		bs = /bS_/g;
		sp = / /g;

		sms = eval("document.frmEmtList.message_temp"+i+".value");

		//message

		r1 = sms.replace(re, "<BR>");
		r1 = r1.replace(sp, "&nbsp;");
		r1 = r1.replace(sq, "'");
		r1 = r1.replace(dq, "\"");
		r1 = r1.replace(bs, "\\");

		//subject

		r2 = subject.replace(re, "\r\n");
		r2 = r2.replace(sq, "'");
		r2 = r2.replace(dq, "\"");
		r2 = r2.replace(bs, "\\");

		msglen = 0;
		r1= replaceThemeToBlank(r1);
		txtMessage.document.body.innerHTML = r1;
		document.VisualPhone.txtMessage.value = r1;
		document.VisualPhone.txtSubject.value = r2;

		chkBytes();
	}


	function InputData_SMS2(i,subject)
	{
		sms = document.getElementsByClassName("phonemsgbox")[i-1].value;
		document.getElementById("s_message").value = sms;
		document.getElementById("s_message").focus();
	}


	function GoKIND(flag){

		if (flag == 'e1')
		{
			document.location.href = '/sms/sms01/main.asp?KindName=즐건하루&Cidx=2&Kidx=1';
//			document.getElementById("centerimo4").style.display = 'block';
//			document.getElementById("centerimo3").style.display = 'none';
//			document.getElementById("centerimo2").style.display = 'none';
//			document.getElementById("centerimo1").style.display = 'none';
//			document.getElementById("e1").className='bold_blue';
//			document.getElementById("e2").className='small_basic';
//			document.getElementById("e3").className='small_basic';
//			document.getElementById("e4").className='small_basic';
		}
		else if (flag == 'e2')
		{
			document.location.href = '/sms/sms01/main.asp?KindName=특별한날&Cidx=4&Kidx=3';
//			document.getElementById("centerimo4").style.display = 'none';
//			document.getElementById("centerimo3").style.display = 'block';
//			document.getElementById("centerimo2").style.display = 'none';
//			document.getElementById("centerimo1").style.display = 'none';
//			document.getElementById("e1").className='small_basic';
//			document.getElementById("e2").className='bold_blue';
//			document.getElementById("e3").className='small_basic';
//			document.getElementById("e4").className='small_basic';
		}
		else if (flag == 'e3')
		{
			document.location.href = '/sms/sms01/main.asp?KindName=아침인사&Cidx=6&kidx=2';
//			document.getElementById("centerimo4").style.display = 'none';
//			document.getElementById("centerimo3").style.display = 'none';
//			document.getElementById("centerimo2").style.display = 'block';
//			document.getElementById("centerimo1").style.display = 'none';
//			document.getElementById("e1").className='small_basic';
//			document.getElementById("e2").className='small_basic';
//			document.getElementById("e3").className='bold_blue';
//			document.getElementById("e4").className='small_basic';
		}
		else
		{
			document.location.href = '/sms/sms01/main.asp?KindName=행복기원&cidx=7&kidx=3';
//			document.getElementById("centerimo4").style.display = 'none';
//			document.getElementById("centerimo3").style.display = 'none';
//			document.getElementById("centerimo2").style.display = 'none';
//			document.getElementById("centerimo1").style.display = 'block';
//			document.getElementById("e1").className='small_basic';
//			document.getElementById("e2").className='small_basic';
//			document.getElementById("e3").className='small_basic';
//			document.getElementById("e4").className='bold_blue';
		}

	}
//MMSbest 함수부분시작
	function MMSINSERT(imgurl,filename,CPCode,FKContent){
		var str = txtMessage.document.body.innerHTML;

		//기존에 이미지가 있다면 해당이미지 삭제 후 이미지 새로 추가
		var str2 = replaceThemeToBlank( str );

		try{
			selText();
		}catch(err){
			selText1();
		}
		//innerhtml전에 포커스를 줘야 이미지 뒤로 포커스가 가는거 같다
		txtMessage.document.body.focus();

		//////////////////////////////////////////////////////////////////////////////
		//gif이미지가 비쥬얼폰에 올라왔을때 움직이지 않기에 원본소스는 주석으로 봉인//
		//////////////////////////////////////////////////////////////////////////////
		/*
		parent.txtMessage.document.body.innerHTML = "<img src='/images/mms/blank_traparent.gif' name='mmsimg' id='mmsimg'><BR>\n" + str2;

		var obj = parent.txtMessage.document.getElementById( 'mmsimg' );
		obj.src = "/images/mms/blank_traparent.gif";
		obj.style.visibility = 'visible';
		obj.width = 105;
		obj.height = 86;
		//이미지 선택안되게함(리사이징 못하게)
		obj.unselectable="on"
		obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + imgurl + "', sizingMethod='scale')";
		*/

		txtMessage.document.body.innerHTML = "<img src='" + imgurl + "' name='mmsimg' id='mmsimg'><BR>\n" + str2;

		var obj = txtMessage.document.getElementById('mmsimg');
		obj.width = 105;
		obj.height = 86;
		//이미지 선택안되게함(리사이징 못하게)
		obj.unselectable="on"

		//파일저장 경로(mms 파일저장경로)

	//	실서버
		var sc_file_path = "D:/www/2007suremcom/mms_cp/"+CPCode+"/"
	// 개발서버
	//	var sc_file_path = "C:/www/web/__2007suremcom_1018/mms_cp/"+CPCode+"/";
		s= document.VisualPhone;

		s.FileCount.value = "1";
		s.FilePath1.value = sc_file_path + filename;

		//아래는 멀티파일첨부시 추가
		s.FileType2.value = "";
		s.FilePath2.value = "";
		s.FileType3.value = "";
		s.FilePath3.value = "";
		s.FileType4.value = "";
		s.FilePath4.value = "";
		s.FileType5.value = "";
		s.FilePath5.value = "";


		//아래는 MMS페이지에서 이미지 전송시 꼭필요한 값들
		s.CPCode.value	  = CPCode;		//회사명
		s.FKContent.value = FKContent;	//이미지FKContent



		//바이트 체크
		chkBytes();

		// MMS전송 모드로 전환
		turnMms();
	}
	function GoKIND1(flag){

		if (flag == 'm13')
		{
			document.getElementById("centermms1").style.display = 'block';
			document.getElementById("centermms2").style.display = 'none';
			document.getElementById("centermms3").style.display = 'none';
			document.getElementById("m13").className='bold_blue';
			document.getElementById("m15").className='small_basic';
			document.getElementById("m17").className='small_basic';
		}
		else if (flag == 'm15')
		{
			document.getElementById("centermms1").style.display = 'none';
			document.getElementById("centermms2").style.display = 'none';
			document.getElementById("centermms3").style.display = 'block';
			document.getElementById("m13").className='small_basic';
			document.getElementById("m15").className='bold_blue';
			document.getElementById("m17").className='small_basic';
		}
		else
		{
			document.getElementById("centermms1").style.display = 'none';
			document.getElementById("centermms2").style.display = 'block';
			document.getElementById("centermms3").style.display = 'none';
			document.getElementById("m13").className='small_basic';
			document.getElementById("m15").className='small_basic';
			document.getElementById("m17").className='bold_blue';
		}

	}
//모바일 쿠폰 함수 시작
	function GoKIND2(flag)
	{
		if (flag == 'm1')
		{
			document.getElementById("centermcon1").style.display = 'block';
			document.getElementById("centermcon2").style.display = 'none';
			document.getElementById("centermcon3").style.display = 'none';
			document.getElementById("m1").className='bold_blue';
			document.getElementById("m2").className='small_basic';
			document.getElementById("m3").className='small_basic';
		}
		else if (flag == 'm2')
		{
			document.getElementById("centermcon1").style.display = 'none';
			document.getElementById("centermcon2").style.display = 'block';
			document.getElementById("centermcon3").style.display = 'none';
			document.getElementById("m1").className='small_basic';
			document.getElementById("m2").className='bold_blue';
			document.getElementById("m3").className='small_basic';
		}
		else
		{
			document.getElementById("centermcon1").style.display = 'none';
			document.getElementById("centermcon2").style.display = 'none';
			document.getElementById("centermcon3").style.display = 'block';
			document.getElementById("m1").className='small_basic';
			document.getElementById("m2").className='small_basic';
			document.getElementById("m3").className='bold_blue';
		}
	}

	function PopSend(cat_id, goods_id)
	{
		var f = document.cate_form;
		f.cat_id.value = cat_id;
		f.goods_id.value = goods_id;
		f.target = 'ifrm_pop';
		//f.action = 'http://heartcon.surem.com/pop_auto.php';
        f.action = 'pop_auto.php';
		f.submit();
	}


//-->
</script>


<!--컨텐츠 끝-->


			</div>

 		</div>

	</div>
</div>
<!-- 중요
<iframe width="0" height="0" name='hiddenframe' class="hiddenx"></iframe>
-->





<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');