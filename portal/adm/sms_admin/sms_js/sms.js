$(document).ready(function(){
	$("#s_message").focus();
});

$(function(){
	/* 전역 변수 선언 */
	if($("#mode").val() == "kakao"){
		var global_msg_flag = "kakao";
	}else{
		var global_msg_flag = "sms";
	}
	/* 기능함수 모음 */
	// 1. checklen() - sms/lms/mms 메세지 길이 체크 함수
	// 2. checklen_mms() - lms/mms 메세지 길이 체크 함수
	// 3. callphone_delete() - 선택 번호 삭제 함수
	// 4. callphone_add() - 입력 번호 추가 함수
	// 5. imagechange - 이미지 선택시 체크 함수
	// 6. charselect - 상용구/특수문자 선택 함수
	// 7. isCallphone(str) - 번호 유효성 검사
	// 8. is_Chinese(values) - 한문 확인 함수
	// 9. commaNum(num) - 천단위 콤마 함수

	function checklen(){
		var txtmsg = $("#s_message").val();
		var msglength = txtmsg.length;
		var tmp_num = 0;
		var str_len = 0;
		var sms_len = 90;
		var limit_len = 2000;

		for(tmp_num=i=0; c=txtmsg.charCodeAt(i++); tmp_num+=c>>11?2:c>>7?2:1){
			if (tmp_num<=2000)
			{
				str_len = i-1;
			}
		}
		if (tmp_num <= sms_len){
            if ($("#s_msgflag").val() == "mms"){
				$("#visualphone-wrap").css("background-position","0px 0px");
                $("#s_title").val("");
                $("#max_len").text(sms_len);
                $("#s_msgflag").val("sms");
            }
		}
		if (sms_len < tmp_num && tmp_num <= limit_len){
            if ($("#s_msgflag").val() == "sms"){
				$("#visualphone-wrap").css("background-position","-230px 0px");
                $("#s_msgflag").val("mms");
                $("#max_len").text(limit_len);
            }
		}else if (tmp_num > limit_len){
			tmp_num = 0;
			alert("최대 전송 메시지 길이를 초과하였습니다.");
			var remsg = txtmsg.substring(0,str_len);
			$("#s_message").val(remsg);
			for(tmp_num=i=0; c=remsg.charCodeAt(i++); tmp_num+=c>>11?2:c>>7?2:1);
		}
		$("#msglen").text(tmp_num);
	}

	function kakao_checklen(){
		var txtmsg = $("#s_message").val();
		var msglength = txtmsg.length;
		var tmp_num = 0;
		var str_len = 0;
		var sms_len = 400;
		var limit_len = 400;

		for(tmp_num=i=0; c=txtmsg.charCodeAt(i++); tmp_num+=c>>11?2:c>>7?2:1){
			if (tmp_num<=limit_len)
			{
				str_len = i-1;
			}
		}

		if (tmp_num > limit_len){
			tmp_num = 0;
			alert("최대 전송 메시지 길이를 초과하였습니다.");
			var remsg = txtmsg.substring(0,str_len);
			$("#s_message").val(remsg);
			for(tmp_num=i=0; c=remsg.charCodeAt(i++); tmp_num+=c>>11?2:c>>7?2:1);
		}
		$("#msglen").text(tmp_num);
	}

	function checklen_mms(){
		var txtmsg = $("#s_message").val();
		var msglength = txtmsg.length;
		var tmp_num = 0;
		var str_len = 0;
		var limit_len = 2000;

		for(tmp_num=i=0; c=txtmsg.charCodeAt(i++); tmp_num+=c>>11?2:c>>7?2:1){
			if (tmp_num<=2000){
				str_len = i-1;
			}
		}
		if (tmp_num <= limit_len){
            if ($("#s_msgflag").val() == "sms"){
                $(".message-area .txt-byte").css("margin-top","0px");
                $("#visualphone-wrap").css("background","url(/visualphone/img/mms_2009_vp_bg.gif) no-repeat 0 0");

                $("#s_msgflag").val("mms");
                $("#max_len").text(limit_len);
            }
		}else if (tmp_num > limit_len){
			tmp_num = 0;
			alert("최대 전송 메시지 길이를 초과하였습니다.");
			var remsg = txtmsg.substring(0,str_len);
			$("#s_message").val(remsg);
			for(tmp_num=i=0; c=remsg.charCodeAt(i++); tmp_num+=c>>11?2:c>>7?2:1);
		}
		$("#msglen").text(tmp_num);
	}

	function callphone_delete(){
		$("#callList > li").click(function() {
			var selectIdx = $("#callList > li").index(this);
			if (selectIdx > -1){
				$("#addphone_cnt").text(parseInt($("#addphone_cnt").text()) - 1);
				$("#callList > li").eq(selectIdx).remove();
			}else if (selectIdx == -1){
				alert("삭제할 번호가 없습니다.");
				return false;
			}
		});
	}

	function callphone_add() {

		var sendnum = $("#input_number").val();
		var sendinfo = $("#ret_input_number").val();
		var senddata = sendinfo.split("|");
		var alertnum  = sendnum.replace(/-/g, "");

		var overlap = false;
		if (sendnum == "") {
			alert("수신번호를 입력해주세요.");
			return false;
		}else if( (alertnum.length <= 9 || alertnum.length >= 12) || ( alertnum.substr(0, 3) != '010' && alertnum.substr(0, 3) != '011' && alertnum.substr(0, 3) != '012' && alertnum.substr(0, 3) != '013' && alertnum.substr(0, 3) != '016' && alertnum.substr(0, 3) != '017' && alertnum.substr(0, 3) != '018' && alertnum.substr(0, 3) != '019' && alertnum.substr(0, 3) != '070' ) ){
			alert("잘못된 수신번호 입니다. 확인해주세요.");
			$("#input_number").val("");
			$("#input_number").focus();
			return false;
		}else{
			// 중복 체크
			for (var i=0; i<$("#callList li").size(); i++){
				var dupchk =($("#callList li:eq("+i+") > input[type='hidden']").val());
				var splitchk = dupchk.split("|");
				if (splitchk[2] == sendnum){
					overlap = true;
				}
			}
			if (!overlap){
				$("#callList").append("<li data-cnt='1'><input type='hidden' name='phone_num[]' value='"+senddata[0]+"|"+senddata[1]+"|"+senddata[2]+"'>"+senddata[0]+"|"+senddata[2]+"</li>");
				$("#addphone_cnt").text(parseInt($("#addphone_cnt").text()) + 1);
				$("#input_number").val("");
				$("#ret_input_number").val("");
				$("#input_number").focus();
				return true;
			}else{
				alert("이미 추가된 번호입니다.");
				$("#input_number").val("");
				$("#ret_input_number").val("");
				$("#input_number").focus();
				return false;
			}

		}
	}
	function callphone_paste_add(){

		var retdata = $("#guest_number").val();
		var chkNum  = retdata.split("|");
		var sendnum = chkNum[1]	;
		var alertnum  = sendnum.replace(/-/g, "");
		var overlap = false;
		if (sendnum == "") {
			alert("수신번호를 입력해주세요.");
			return false;
		}else if( (alertnum.length < 9 || alertnum.length >= 12) || ( alertnum.substr(0, 3) != '010' && alertnum.substr(0, 3) != '011' && alertnum.substr(0, 3) != '012' && alertnum.substr(0, 3) != '013' && alertnum.substr(0, 3) != '016' && alertnum.substr(0, 3) != '017' && alertnum.substr(0, 3) != '018' && alertnum.substr(0, 3) != '019' && alertnum.substr(0, 3) != '070' ) ){
			alert("잘못된 수신번호 입니다. 확인해주세요.");
			$("#s_pastetxt").val("");
			$("#s_pastetxt").focus();
			return false;
		}else{
			// 중복 체크

			for (var i=0; i<$("#callList li").size(); i++){
				var dupchk =($("#callList li:eq("+i+") > input[type='hidden']").val());
				var splitchk = dupchk.split("|");
				if (splitchk[2] == sendnum){
					overlap = true;
				}
			}
			if (!overlap){
				$("#callList").append("<li data-cnt='1'><input type='hidden' name='phone_num[]' value='"+chkNum[0]+"|guest|"+chkNum[1]+"'>"+chkNum[0]+"|"+chkNum[1]+"</li>");
				$("#addphone_cnt").text(parseInt($("#addphone_cnt").text()) + 1);
				$("#guest_number").val("");
				return true;
			}else{
				alert("이미 추가된 번호입니다.");
				$("#guest_number").val("");
				return false;
			}
		}
	}
	// charselect
	$(document).on("click",".ly-special-characters .ly-wrap a",function(){
		var msg = $("#s_message").val();
		$("#s_message").val(msg+$(this).data("str"));
		if (global_msg_flag=="sms"){
			checklen();
		}else if (global_msg_flag=="mms"){
			checklen_mms();
		}else if (global_msg_flag=="kakao"){
			kakao_checklen();
		}
	});

	function isCallphone(str){
		var callphone = str;
		if (callphone.length <= 11 || callphone.length >= 14 || ( callphone.substr(0, 3) != '010' && callphone.substr(0, 3) != '011' && callphone.substr(0, 3) != '012' && callphone.substr(0, 3) != '013' && callphone.substr(0, 3) != '016' && callphone.substr(0, 3) != '017' && callphone.substr(0, 3) != '018' && callphone.substr(0, 3) != '019' && callphone.substr(0, 3) != '070' ))
		{
			return false;
		}else{
			return true;
		}
	}

	function is_Chinese(Values) {
		if (!Values.length) { return false; }
		var chk = false;
		for (var i = 0; i < Values.length; i++) {
			if (escape(Values.substr(i,1)).substr(0,2) == "%u" && Number(escape(Values.substr(i,1)).substr(2,1)) >= 4) {
				chk = true;
			}
		}
		return chk;
	}

	function commaNum(num) {
        var len, point, str;
        num = num + "";
        point = num.length % 3
        len = num.length;

        str = num.substring(0, point);
        while (point < len) {
            if (str != "") str += ",";
            str += num.substring(point, point + 3);
            point += 3;
        }
        return str;
    }

	//이미지 첨부
	$(document).on("click","#imgadd_btn",function(){
		if ($(".ly-mms-file").css("display") == "none")
		{
			$(".ly-mms-file").css("display","block");
		}else{
			$(".ly-mms-file").css("display","none");
		}
		return false;
	});
	$(document).on("click",".ly-mms-file .btn-close",function(){
		$(".ly-mms-file").css("display","none");
		return false;
	});
	//이미지 선택시
	$("input:file").change(function(){
		if ($(this).attr("id") == "img1"){
			if($("#type_page").val() == "kakao"){
				if (parseInt($("#s_filecnt").val()) == 1){
					alert("이미지는 1개 첨부 가능합니다.");
					$(".ly-mms-file").css("display","none");
					return false;
				}
			}else{
				if (parseInt($("#s_filecnt").val()) == 2){
					alert("이미지는 최대 2개까지 첨부 가능합니다.");
					$(".ly-mms-file").css("display","none");
					return false;
				}
			}
			$("form[name=upload_form]").attr("target","hiddenframe");
			$("form[name=upload_form]").submit();
			$(".ly-mms-file").css("display","none");
		}
	});
	// 이미지 체인지
	$(document).change("#s_filecnt",function(){
		var filecnt = $("#s_filecnt").val();
		if($("#mode").val() == "kakao"){
			var limit_len = 1000;
			var sms_len = 1000;
		}else{
			var limit_len = 2000;
			var sms_len = 90;
		}

		if (filecnt > 0){
			$("#add_img_box").css("display","block");
			if ($("#s_msgflag").val() == "sms"){
				$("#visualphone-wrap").css("background-position","-230px 0px");
                $("#s_msgflag").val("mms");
                $("#max_len").text(limit_len);
            }
			global_msg_flag = "mms";
			checklen_mms();
		}else if (filecnt == 0){
			if($("#mode").val() != "kakao"){
				$("#add_img_box").css("display","none");
				if ($("#s_msgflag").val() == "mms"){
					$("#visualphone-wrap").css("background-position","0px 0px");
					$("#s_title").val("");
					$("#max_len").text(sms_len);
					$("#s_msgflag").val("sms");
				}
				global_msg_flag = "sms";
				checklen();
			}else{
				$("#max_len").text(sms_len);
				global_msg_flag = "kakao";
				kakao_checklen();
			}
		}
	});
	//이미지 삭제
	$(document).on("click",".added-img",function(){
		var img_cnt = $(".added-img").length;
		var img_id = $(this).attr("id");
		if (img_cnt == "1"){
			if (confirm("첨부된 이미지를 삭제하시겠습니까?")){
				$(this).remove();
				$("#s_filecnt").val("0").trigger("change");
				//$("#filepath1").val("");
				global_msg_flag = "sms";
			}
		}else if (img_cnt == "2"){
			var filepath2 = $("#filepath2").val();
			var img_id2 = $(this).attr("id");
			if (confirm("첨부된 이미지를 삭제하시겠습니까?")){
				if (img_id == "added_img2"){//두번째 이미지
				}else if (img_id == "added_img1"){//첫번째 이미지
					$("#filepath1").val(filepath2);
					$("#added_img2").attr("id","added_img1");
				}
				$("#s_filecnt").val("1").trigger("change");
				//$("#filepath2").val("");
				$(this).remove();
			}
		}

		//$("#filepath1").val("");
		$.ajax({
			url : "sms_action.php",
			type: "POST",
			data : {"img_path1" : $("#filepath1").val(),"img_path2" : $("#filepath2").val(),"mode":"del_img","prcCode":"ajax" },
			success:function(data, textStatus, jqXHR){
				$("#filepath1").val("");
				$("#filepath2").val("");
				var bind_results = $.parseJSON(data);
				if(bind_results.count > 0){
					alert('삭제할 이미지가 없습니다.');
					location.reload();
				}else{
					alert('삭제 되었습니다.');
					location.reload();
				}
			}
		});
	});

	//메시지 입력시
	$(document).on("focus focusout keyup paste","#s_message",function(e){
		if (global_msg_flag=="sms")
		{
			checklen();
		}else if (global_msg_flag=="mms")
		{
			checklen_mms();
		}else if(global_msg_flag=="kakao")
		{
			kakao_checklen();
		}
	});
	//특수문자
	$(document).on("click","#spchar_btn",function(){
		if ($(".ly-special-characters").css("display") == "none")
		{
			$(".ly-special-characters").css("display","block");
		}else{
			$(".ly-special-characters").css("display","none");
		}
		return false;
	});
	$(document).on("click",".ly-special-characters .btn-close",function(){
		$(".ly-special-characters").css("display","none");
		return false;
	});

	//주소록
	$(document).on("click","#pb_btn",function(){
		Show_Open("sms_address.php",'720','550');
	});

	$(document).on("click",".ly-recentnum .btn-close",function(){
		$(".ly-recentnum").css("display","none");
		return false;
	});
	$(document).on("click","#recent_table tr",function(){
		$("#recent_table tr").removeClass("on");
		$(this).addClass("on");
		return false;
	});
	$(document).on("click",".ly-recentnum #select_btn",function(){
		var selected = false;
		var selectednum = "";
		$("#recent_table tr").each(function(idx){
			if ($(this).hasClass("on"))
			{
				selected = true;
				selectednum = $(this).data("num");
			}
		});
		if (selected){
			$("#input_number").val(selectednum);
			callphone_add();
			$(".ly-recentnum").css("display","none");
		}else{
			alert("선택된 번호가 없습니다. 번호를 선택해주세요.");
		}
	});
	$(document).on("click",".ly-recentnum #delete_btn, .ly-recentnum #alldelete_btn",function(){
		var selected = false;
		var selectednum = "";
		var deltype = "";
		$("#recent_table tr").each(function(idx){
			if ($(this).hasClass("on"))
			{
				selected = true;
				selectednum = $(this).data("num");
			}
		});
		if ($(this).attr("id") == "delete_btn"){
			if (selected)
			{
				if (!confirm("선택한 번호를 삭제하시겠습니까?"))
				{
					return false;
				}
			}
			deltype = "d";
		}else if ($(this).attr("id") == "alldelete_btn"){
			if (!confirm("전체 삭제하시겠습니까?"))
			{
				return false;
			}
			deltype = "a";
			selected = true;
		}
		if (selected){
			$.ajax({
				url : "/visualphone/proc/recentnum_proc.asp",
				cache : false,
				data : {atype : deltype, snum : selectednum},
				success: function(data){
					if (data == "del")
					{
						$("#recent_table tr").each(function(idx){
							if ($(this).hasClass("on")){
								$(this).remove();
								alert("삭제되었습니다.");
							}
						});
					}else if (data == "alldel")
					{
						$("#recent_table").children().remove();
					}
				}
			});
		}else{
			alert("선택된 번호가 없습니다. 번호를 선택해주세요.");
		}
	});

	//붙여넣기
	$(document).on("click","#paste_btn",function(){
		if ($(".ly-paste").css("display") == "none")
		{
			$(".ly-paste").css("display","block");
		}else{
			$(".ly-paste").css("display","none");
		}
		return false;
	});
	$(document).on("click",".ly-paste .btn-close",function(){
		$(".ly-paste").css("display","none");
		return false;
	});
	$(document).on("click",".btn-paste",function(){
		var reTxt = "";
		var tmp = "";
		var arrTmp = "[]";
		var tmpSendnum = "";
		var comma = "";
		var cnt = 0;
		var line_cnt = 0;
		var fail_cnt = 0;
		//번호 미입력
		if ($("#s_pastetxt").val()==""){
			$("#s_pastetxt").focus();
			alert("전송하실 번호를 붙여넣어 주세요.");
			return false;
		}
		reTxt = $("#s_pastetxt").val();
		line_cnt = reTxt.split("\n").length;
		// - 제거
		//엔터 공백 제거
		while (reTxt.indexOf("\n\n") > -1){
			reTxt =	reTxt.replace(/\n\n/g,"\n");
		}
		//첫번째 엔터 공백 제거
		if (reTxt.indexOf("\n") == 0){
			reTxt = reTxt.replace("\n","");
		}
		$("#s_pastetxt").val(reTxt);
		var alldata = $("#s_pastetxt").val();
		var arrNum = alldata.split("\n");
		for (i=0; i<arrNum.length; i++){
			tmp = arrNum[i].replace(/\t/g,",");
			tmp = tmp.replace(/ /g,",");
			arrTmp = tmp.split("|");
			if (isCallphone(arrTmp[1])){
				$("#guest_number").val(tmp);
				callphone_paste_add();
			}
		}
		alert("수신번호에 등록되었습니다.");
		$(".ly-paste").css("display","none");
		$("#s_pastetxt").val("");
	});

	//수신번호 직접 입력시
	var timer = 0;
	$(document).on("keyup","#input_number",function(e){
		if(e.keyCode == 13){
			callphone_add();
		}
		if(timer){
			clearTimeout(timer);
		}
		var phonenum = "";
		var reqname = "";
		var tmp_p = "";
		var tmp_r = "";
		var keyword = $("#input_number").val();
		var changeword = "<font color='red'>"+keyword+"</font>";
		timer = setTimeout(function() {
			$(".ly-phone-search .ly-wrap table tr").remove();
			if ($("#input_number").val() == "")
			{
				$(".ly-phone-search").css("display","none");
			}else{
				$(".ly-phone-search").css("display","block");
				$.ajax({
					url : "sms_action.php",
					type: "POST",
					data : {"s_word" : $("#input_number").val(),"mode":"mb_search","prcCode":"ajax" },
					success:function(data, textStatus, jqXHR){
						var bind_results = $.parseJSON(data);

						if(bind_results.count > 0){

							var ret	= jQuery.parseJSON(data);
							var dwrResult = ret.list;
							if(dwrResult.length){
								for(var i=0; i< dwrResult.length; i++){
									$(".ly-phone-search .ly-wrap table").append("<tr><td><span class='auto-name'>"+dwrResult[i].mb_name+"</span><span class='auto-reqnum'>"+dwrResult[i].mb_hp+"</span><span id='auto-value' class='blind'>"+dwrResult[i].mb_name+"|"+dwrResult[i].mb_id+"|"+dwrResult[i].mb_hp+"</span></td></tr>");
								}
							}
						}else{
							$(".ly-phone-search .ly-wrap table").append("<tr><td><span>검색된 번호가 없습니다.</span></td></tr>");
						}
					},
					error: function(jqXHR, textStatus, errorThrown){
						$("#simple-msg").html('<pre><code class="prettyprint">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</code></pre>');
					}
				});
			}
		}, 300);
	});


	//+ 회원검색 버튼
	$(document).on("click",".ly-phone-search .ly-wrap table tr",function(){
		$("#ret_input_number").val($(this).children().children("#auto-value").text());
		$("#input_number").val($(this).children().children(".auto-reqnum").text());
		callphone_add();
		$(".ly-phone-search").css("display","none");
	});
	//+버튼
	$(document).on("click","#numadd_btn",function(){
		callphone_add();
		$(".ly-phone-search").css("display","none");
	});
	//수신번호 더블클릭 삭제
	$(document).on("dblclick","#callList",function(){
		callphone_delete();
	});
	$(document).on("click","#allnumdel_btn",function(){
		if (confirm("수신번호를 모두 삭제하시겠습니까?"))
		{
			$("#addphone_cnt").text("0");
			$("#callList").find("li").remove().end();
		}
		return false;
	});

	//전송
	$(document).on("click","#msg_send_btn",function(){
		//전송전 예외처리

		var sendmsg = $("#s_message").val();
		if (sendmsg == ""){
			alert("메세지를 입력하지 않았습니다.");
			$("#s_message").focus();
			return false;
		}
		if ($("#s_reqphone").val() == "" && !$("input:checkbox[id='virnum_chk']").is(":checked")){
			if (!confirm("회신번호를 미입력하셨습니다. 전송하시겠습니까?"))
			{
				$("#s_reqphone").focus();
				return false;
			}
		}

		if ($("#addphone_cnt").text() == "0"){
			if ($("#input_number").val() != "")
			{
				$(".ly-phone-search").css("display","none");
				if (isCallphone($("#input_number").val()))
				{
					callphone_add();
				}else{
					alert("잘못된 번호입니다. 다시 입력해주세요.");
					$("#input_number").focus();
					return false;
				}
			}else{
				alert("수신번호를 입력해 주세요.");
				$("#input_number").focus();
				return false;
			}
		}
		if (is_Chinese(sendmsg)){
			alert("한자는 전송할 수 없습니다.");
			return false;
		}
		document.VisualPhone.submit();
		return false;
	});

	//팝업 전송 버튼
	$(document).on("click","#pop_send_btn",function(){
		var calltype = $("#s_calltype").val();
		var msgflag = $("#s_msgflag").val();
		var reqphone = $("#s_reqphone").val();
		var sendmsg = $("#s_message").val();
		var msgtitle = $("#s_title").val();
		var filepath1 = $("#filepath1").val();
		var filepath2 = $("#filepath2").val();
		var sendcnt = $("#addphone_cnt").text();
		var sendnumber = "";
		var sendtime = $("#s_sendtime").val();
		var virnum = $("#virnum_chk").val();
		var duplicate = $("#duplicate_flag").val();
		var comma = "";
		var rejectflag = "";

		//전송 번호 생성
		for (var i=0; i<$("#callList option").size(); i++){
			if (i==0)
			{
				comma = "";
			}else{
				comma = ",";
			}
			sendnumber = sendnumber + comma + $("#callList option:eq("+i+")").val();
		}
		if (sendnumber == ""){
			alert("수신번호 입력이 정상적이지 않습니다. 다시입력해주세요.");
			return false;
		}
		//초기화
		$(".wonning-error1").css("display","none");
		$("#succ_price").text("0원");
		$("#succ_cnt").text("0건");
		$("#fail_cnt").text("0건");
		$("#rsc").text("0");
		$("#reserv_txt").text("");
		var r_error, r_newmem, r_price, r_sprice, r_reject, r_result, r_cnt, r_scnt, r_spam, r_realprice = 0, r_sndcnt;
		if (msgflag=="sms")
		{
			$("#pop_send1").css("display","none");
			$("#pop_sending").css("display","block");
			$(".ly-tit").text($("#s_msgflag").val().toUpperCase()+" 전송중");
			$.ajax({
				method : "post",
				cache : false,
				url : "/visualphone/proc/send_sms.asp",
				data: { s_calltype : calltype, s_msgflag : msgflag , s_reqphone : reqphone , s_message : escape(sendmsg) , s_inputnum : sendnumber , s_sndtime : sendtime, sendlen : sendcnt, s_virnum : virnum, dup : duplicate},
				dataType:"JSON",
				success: function(data){
					$.each ( data, function(i,dat) {
						r_result = dat.Result;
						if (r_result.substring(0,2) == "HD")
						{
							$("#pop_sending").css("display","none");
							$("#pop_send5").css("display","block");
						}else if (r_result.substring(0,2) == "WO")
						{
							if (dat.ercode == "Word")
							{
								$(".ly-send-keyword .cont").text("사용하실 수 없는 문구가 포함되어 전송하실 수 없습니다.");
								$(".ly-send-keyword .txt").text(dat.msg);
							}else if (dat.ercode == "Phone")
							{
								$(".ly-send-keyword .cont").text("사용하실 수 없는 회신번호 입니다. 휴대폰의 경우 통신사 부가서비스를 확인해주세요.");
								$(".ly-send-keyword .txt").text(dat.msg);
							}
							$(".ly-send-keyword .txt").text(dat.msg);
							$("#pop_sending").css("display","none");
							$("#pop_send3").css("display","block");
						}else if (r_result.substring(0,2) == "DU")
						{
							$("#pop_sending").css("display","none");
							$("#pop_send4").css("display","block");
						}else if (r_result.substring(0,1) == "S")
						{
							r_error = dat.ERR;
							r_newmem = dat.NMEventCnt;
							r_price = dat.Price;
							r_sprice = dat.Price070;
							r_reject = dat.RejectNoCnt;
							r_cnt = dat.SentHit;
							r_scnt = dat.SentHit070;
							r_spam = dat.SpamYN;
							if (r_newmem == null)
							{
								r_newmem = "0";
							}
							//금액 처리
							if (r_price != null)
							{
								var tmp = parseInt(r_cnt)-parseInt(r_newmem);
								r_realprice = r_realprice + parseInt(r_price)*tmp;
							}
							if (r_sprice != null)
							{
								r_realprice = r_realprice + parseInt(r_sprice)*parseInt(r_scnt);
							}
							//에러 없으면 진행
							$("#rsc").text(commaNum(r_cnt));
							$("#succ_price").text(commaNum(r_realprice)+"원");
							r_sndcnt = commaNum(r_cnt) + "건";
							if (parseInt(r_newmem) > 0)
							{
								r_sndcnt = r_sndcnt + " (무료 "+ commaNum(r_newmem) +"건)";
							}
							$("#succ_cnt").text(r_sndcnt);
							if (parseInt(r_reject)>0)
							{
								$("#fail_cnt").text("수신거부 "+commaNum(r_reject)+"건");
							}
							if (r_spam == "N")
							{
								$(".wonning-error1").html("'방송 통신 위원회의 자율 규제에 따라 일 동보 전송 500건 제한' 사항을 준수함에<br>따라 일 전송 500건이상 전송시 운영자의 승인절차를 거치게 됩니다.<br>전송이 안되시거나, 긴급한 경우 <span class='red'>1588-4640 (2번)</span>으로 연락주십시오.");
								$(".wonning-error1").css("display","block");
								$("#succ_cnt").text($("#succ_cnt").text() + "(전송대기)");
							}
							if (r_result.substring(1,2)=="6")
							{
								$("#reserv_txt").text($("#yy").val()+"년 "+$("#mm").val()+"월 "+$("#dd").val()+"일 "+$("#h").val()+"시 "+$("#m").val()+"분에 예약");
							}
						}else if (r_result == "f1")
						{
							$(".wonning-error1").html("입력하신 메세지의 형식이 잘못되었습니다.");
							$(".wonning-error1").css("display","block");
						}else if (r_result == "f2"){
							r_reject = dat.RejectNoCnt;
							if (parseInt(r_reject)>0)
							{
								$("#fail_cnt").text("수신거부 "+commaNum(r_reject)+"건");
							}
							$(".wonning-error1").html("전송하실 휴대폰의 번호가 수신거부번호이거나 잘못된 번호입니다.");
							$(".wonning-error1").css("display","block");
						}else if (r_result == "02")
						{
							$(".wonning-error1").html("사이버머니가 부족하여 전송할 수 없습니다.");
							$(".wonning-error1").css("display","block");
						}
					});
					if (r_result.substring(0,2) != "WO" && r_result.substring(0,2) != "DU" && r_result.substring(0,2) != "HD"){
						$("#duplicate_flag").val("0");
						$("#pop_sending").css("display","none");
						$("#pop_send2").css("display","block");
						$(".ly-tit").text($("#s_msgflag").val().toUpperCase()+" 전송완료");
					}
				},
				error : function(){
					$(".wonning-error1").html("전송에 실패하였습니다.");
					$(".wonning-error1").css("display","block");
					$("#pop_sending").css("display","none");
					$("#pop_send2").css("display","block");
					$(".ly-tit").text($("#s_msgflag").val().toUpperCase()+" 전송완료");
				}
			});
		}else if (msgflag=="mms"){
			$("#pop_send1").css("display","none");
			$("#pop_sending").css("display","block");
			$(".ly-tit").text($("#s_msgflag").val().toUpperCase()+" 전송중");
			$.ajax({
				method : "post",
				cache : false,
				url : "/visualphone/proc/send_mms.asp",
				data: { s_calltype : calltype, s_msgflag : msgflag , s_reqphone : reqphone , s_message : escape(sendmsg) , s_inputnum : sendnumber , s_sndtime : sendtime, s_title : escape(msgtitle), s_filecnt : filecnt, filepath1 : filepath1, filepath2 : filepath2 , sendlen : sendcnt, s_virnum : virnum, dup : duplicate},
				dataType:"JSON",
				success: function(data){
					$.each ( data, function(i,dat) {
						r_result = dat.Result;
						if (r_result.substring(0,2) == "HD")
						{
							$("#pop_sending").css("display","none");
							$("#pop_send5").css("display","block");
						}else if (r_result.substring(0,2) == "WO")
						{
							if (dat.ercode == "Word")
							{
								$(".ly-send-keyword .cont").text("사용하실 수 없는 문구가 포함되어 전송하실 수 없습니다.");
								$(".ly-send-keyword .txt").text(dat.msg);
							}else if (dat.ercode == "Phone")
							{
								$(".ly-send-keyword .cont").text("사용하실 수 없는 회신번호 입니다. 휴대폰의 경우 통신사 부가서비스를 확인해주세요.");
								$(".ly-send-keyword .txt").text(dat.msg);
							}
							$(".ly-send-keyword .txt").text(dat.msg);
							$("#pop_sending").css("display","none");
							$("#pop_send3").css("display","block");
						}else if (r_result.substring(0,2) == "DU")
						{
							$("#pop_sending").css("display","none");
							$("#pop_send4").css("display","block");
						}else if (r_result.substring(0,1) == "S")
						{
							r_error = dat.ERR;
							r_price = dat.Price;
							r_reject = dat.RejectNoCnt;
							r_cnt = dat.SentHit;
							r_scnt = dat.SentHit070;
							r_spam = dat.sendcode;
							//금액 처리
							if (r_price != null)
							{
								var tmp = parseInt(r_cnt);
								r_realprice = r_realprice + parseInt(r_price)*tmp;
							}
							//에러 없으면 진행
							$("#rsc").text(commaNum(r_cnt));
							$("#succ_price").text(commaNum(r_realprice)+"원");
							r_sndcnt = commaNum(r_cnt) + "건";
							$("#succ_cnt").text(r_sndcnt);
							if (parseInt(r_reject)>0){
								$("#fail_cnt").text("수신거부 "+commaNum(r_reject)+"건");
							}
							if (r_spam == "N"){
								$(".wonning-error1").html("'방송 통신 위원회의 자율 규제에 따라 일 동보 전송 500건 제한' 사항을 준수함에<br>따라 일 전송 500건이상 전송시 운영자의 승인절차를 거치게 됩니다.<br>전송이 안되시거나, 긴급한 경우 <span class='red'>1588-4640 (2번)</span>으로 연락주십시오.");
								$(".wonning-error1").css("display","block");
								$("#succ_cnt").text($("#succ_cnt").text() + "(전송대기)");
							}
							if (r_result.substring(1,2)=="6"){
								$("#reserv_txt").text($("#yy").val()+"년 "+$("#mm").val()+"월 "+$("#dd").val()+"일 "+$("#h").val()+"시 "+$("#m").val()+"분에 예약");
							}
						}else if (r_result == "f1"){
							$(".wonning-error1").html("입력하신 메세지의 형식이 잘못되었습니다.");
							$(".wonning-error1").css("display","block");
						}else if (r_result == "f2"){
							$(".wonning-error1").html("전송하실 휴대폰의 번호가 수신거부번호이거나 잘못된 번호입니다.");
							$(".wonning-error1").css("display","block");
						}else if (r_result == "02"){
							$(".wonning-error1").html("사이버머니가 부족하여 전송할 수 없습니다.");
							$(".wonning-error1").css("display","block");
						}
					});
					if (r_result.substring(0,2) != "WO" && r_result.substring(0,2) != "DU" && r_result.substring(0,2) != "HD"){
						$("#duplicate_flag").val("0");
						$("#pop_sending").css("display","none");
						$("#pop_send2").css("display","block");
						$(".ly-tit").text($("#s_msgflag").val().toUpperCase()+" 전송완료");
					}
				},
				error : function(){
					$(".wonning-error1").html("전송에 실패하였습니다.");
					$(".wonning-error1").css("display","block");
					$("#pop_sending").css("display","none");
					$("#pop_send2").css("display","block");
					$(".ly-tit").text($("#s_msgflag").val().toUpperCase()+" 전송완료");
				}
			});
		}
	});
	$(document).on("click",".ly-send-overlap .btn-confirm",function(){
		if ($("#samechk").val()=="중복전송확인")
		{
			$("#duplicate_flag").val("1");
			$("#pop_send4").css("display","none");
			$("#samechk").val("");
			$("#pop_send1").css("display","block");
		}else{
			alert("'중복전송확인' 을 입력해주세요.");
		}
	});

	//팝업 닫기버튼
	$(document).on("click",".ly-send .btn-ly-close, .ly-send .btn-cancel",function(){
		if ($(this).attr("id")=="snd_end_btn")
		{
			global_msg_flag = "sms";
			$("#s_msgflag").val("mms");


			$("#filepath1").val("");
			$("#filepath2").val("");
			$("#s_filecnt").val("0");

			//메시지 관련
			$("#s_title").val("");
			$("#s_message").val("");
			$(".added-img").remove();

			//수신자 관련
			$("#addphone_cnt").text("0");
			$("#callList").find("option").remove().end();

			//가상번호
			$(".ly-phone-virtual").css({"display":"none"});
			$("#virnum_chk").val("0");

			//예약 관련
			$(".sel-date").attr("disabled",true);
			$(".sect-visualphone .send-type img").attr("src","/CMS/_img/sms/btn_vp_now.gif");
			$("#msg_send_btn").attr("src","/CMS/_img/sms/btn_send.gif");
			$(".ly-reserve").css({"display":"none"});
			$("#reserv_chk").attr("checked",false);
			$("#s_calltype").val("0");
			$("#s_sendtime").val("");
			$("#ymd").val("");
			$("#h option").remove();
			$("#m option").remove();
			$("#h").append("<option value=''>선택</option>");
			$("#m").append("<option value=''>선택</option>");

			checklen();
		}
		$(".ly-send, #pop_send1, #pop_send2, #pop_send3, #pop_send4, #pop_send5, #pop_sending").css("display","none");
		$("#duplicate_flag").val("0");
	});

	//이어전송
	$(document).on("click","#re_send_btn",function(){
		global_msg_flag = "sms";
		$("#s_msgflag").val("mms");


		$("#filepath1").val("");
		$("#filepath2").val("");
		$("#s_filecnt").val("0");

		//메시지 관련
		$("#s_title").val("");
		$("#s_message").val("");
		$(".added-img").remove();

		checklen();
		$(".ly-send, #pop_send1, #pop_send2, #pop_send3, #pop_send4, #pop_send5, #pop_sending").css("display","none");
		$("#duplicate_flag").val("0");
	});

	//결과 조회
	$(document).on("click","#pop_send2 .btn-result",function(){
		if (confirm("결과조회 페이지로 이동하시겠습니까?"))
		{
			location.href = "/result/main.asp";
		}
	});

	//취소버튼
	$(document).on("click","#msg_cancel_btn",function(){
		if (!confirm("모든 입력을 취소하고 다시 입력하시겠습니까?"))
		{
			return false;
		}
		//SMS 전환
		global_msg_flag = "sms";
		$("#s_msgflag").val("mms");


		$("#filepath1").val("");
		$("#filepath2").val("");
		$("#s_filecnt").val("0");

		//메시지 관련
		$("#s_title").val("");
		$("#s_message").val("");
		$(".added-img").remove();

		//수신자 관련
		$("#addphone_cnt").text("0");
		$("#callList").find("option").remove().end();

		//가상번호
		$(".ly-phone-virtual").css({"display":"none"});
		$("#virnum_chk").val("0");

		//예약 관련
		$(".sel-date").attr("disabled",true);
		$(".sect-visualphone .send-type img").attr("src","/CMS/_img/sms/btn_vp_now.gif");
		$("#msg_send_btn").attr("src","/CMS/_img/sms/btn_send.gif");
		$(".ly-reserve").css({"display":"none"});
		$("#reserv_chk").attr("checked",false);
		$("#s_calltype").val("0");
		$("#s_sendtime").val("");
		$("#ymd").val("");
		$("#h option").remove();
		$("#m option").remove();
		$("#h").append("<option value=''>선택</option>");
		$("#m").append("<option value=''>선택</option>");

		checklen();
	});

	//숫자만 입력
	$("#s_reqphone").on("keyup",function(e){
		$(this).val( $(this).val().replace(/[^0-9]/gi,""));
	});
});

/* 일반 스크립트 함수 */

//주소록 닫기
function closePblayer(){
	$("#pb_div").css("display","none");
}
//예전주소록 버튼
function open_beforePb(){
	closePblayer();
	phonebookpop = window.open("/phonebook/pop/main.asp","phonebookpop","width=670,height=550,top=0,left=200");
	if (phonebookpop != null ) {
		setCookie("phonebookpop","Y",1);
		phonebookpop.focus();
	}else{
		if(confirm("팝업 차단 때문에 주소록을 열수가 없습니다.\n\n팝업 차단을 해제하시겠습니까?")){
			setCookie("phonebookpop","Y",1);
			location.href ="/common/popup/popup_xpsp2not.asp";
		}
	}
}
// 주소록에서 번호 선택후 셀렉트박스에 입력 함수
function rearrangeGroup()
{
	var grpArr = $("#ReceiveGroup").val().split(",");
	var grpLen = grpArr.length;
	var memArr = $("#ReceiveList").val().split(",");
	var memLen = memArr.length;
	var tmp_val = "[]", tmp_option= "[]", option_name="";
	var totalcnt = 0;

	for (var i=0; i<grpLen-1; i++)
	{
		var overlap = false;
		tmp_val = grpArr[i].split(":%:");
		tmp_option = tmp_val[1].split(":(:");
		option_name = tmp_option[0];
		sndcnt = tmp_option[1].replace(":):","");

		// 중복 체크
		for (var j=0; j<$("#callList option").size(); j++){
			if ($("#callList option:eq("+j+")").val() == "/"+grpArr[i]){
				overlap = true;
			}
		}
		if (!overlap)
		{
			$("#callList").append("<option value='/"+grpArr[i]+"' data-cnt='"+parseInt(sndcnt)+"'>"+option_name+"</option>");
			totalcnt = totalcnt + parseInt(sndcnt);
		}
	}
	tmp_val = "[]";
	tmp_option= "[]";
	for (var i=0; i<memLen-1; i++)
	{
		var overlap = false;
		tmp_val = memArr[i].split(":%:");
		tmp_option = tmp_val[1].split(" ");
		option_name = tmp_option[0];
		for (var j=0; j<$("#callList option").size(); j++){
			if ($("#callList option:eq("+j+")").val() == ":"+memArr[i]){
				overlap = true;
			}
		}
		if (!overlap)
		{
			$("#callList").append("<option value=':"+memArr[i]+"' data-cnt='1'>"+option_name+"</option>");
			totalcnt = totalcnt + 1;
		}
	}
	$("#addphone_cnt").text(parseInt($("#addphone_cnt").text()) + totalcnt);
	return false;
}

//세션체크

function sessionConnect(c){
	$("#s_userprice").val(c);
}


//이미지 업로드 후
function upload_complete(cnt, img1, img2, imgname1, imgname2,folderPath,url_path){
	var upload_cnt = cnt;
	var path1 = img1;
	var path2 = img2;
	$("#add_img_div").children().remove();
	if (cnt == 0)
	{
	}else if (cnt == 1){
		$("#add_img_div").append("<img src='"+url_path+"/smsFile/"+folderPath+"/"+imgname1+"' class='added-img' id='added_img1'>");
		$("#filepath1").val(path1.replace(/\\/gi,"/"));
		$("#s_message").focus();
	}else if (cnt == 2){
		$("#add_img_div").append("<img src='"+url_path+"/smsFile/"+folderPath+"/"+imgname1+"' class='added-img' id='added_img1'>");
		$("#add_img_div").append("<img src='"+url_path+"/smsFile/"+folderPath+"/"+imgname2+"' class='added-img' id='added_img2'>");
		$("#filepath1").val(path1.replace(/\\/gi,"/"));
		$("#filepath2").val(path2.replace(/\\/gi,"/"));
		$("#s_message").focus();
	}
	$("#s_filecnt").val(cnt).trigger("change");
}

function imageProcFunc(filepath, filename, folderPath,url_path){
	if (parseInt($("#s_filecnt").val()) == 0){
		upload_complete("1",filepath,"",filename,"",folderPath,url_path);
	}else if (parseInt($("#s_filecnt").val()) == 1){
		upload_complete("2",$("#filepath1").val(),filepath,$("#added_img1").attr("src").split(folderPath+"/")[1],filename, folderPath,url_path);
		//window.parent.imageProcFunc("D:\\www\\2007suremcom\\mms_img1\\20171020033651_guest_1.jpg","20171020033651_guest_1.jpg");
		///home/kmpilot/www/smsFile/20171020/0c95a9b8e3ff10698a4540dc8d93ced5.jpg
	}else{
		alert("이미지를 더이상 올릴수 없습니다.");
	}
}
