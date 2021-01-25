



	function IsDigit() {

		// onkeydown="IsDigit()" style="ime-mode:disabled"
		if(
			((event.keyCode >= 48) && (event.keyCode <= 57)) ||	// keyboard
			((event.keyCode >= 96) && (event.keyCode <= 105)) ||	// keypad
			(event.keyCode == 9) ||	// tab
			(event.keyCode == 10)||	// enter
			((event.keyCode >= 35) && (event.keyCode <= 40)) ||	// arrow and home,end
			(event.keyCode == 45)||	(event.keyCode == 46)||	// insert, delete
			(event.keyCode == 8) ||	(event.keyCode == 144)	// BS, NumLock
			) {
				event.returnValue = true;
			} else {
				event.returnValue = false;
			}
	}


	function ByteCount(input) { 
		var i, j=0; 

		for(i=0;i<input.length;i++) { 

			val=escape(input.charAt(i)).length; 

			if(val== 6) j++; 
			j++; 
		} 
		return j; 
	}

	function trim( strValue )
	{
		var ReturnValue = "";

		if( strValue == "" )
					return "";

		for(var i=0;i<strValue.length;i++)
		{
				if(strValue.charAt(i) != " ")
					  ReturnValue = ReturnValue + strValue.charAt(i);
		}

		return ReturnValue;
	}

	function is_binNo(num) { 
		if(num.length != 10) {
			return false;
		}
		var reg = /([0-9]{3})-?([0-9]{2})-?([0-9]{5})/; 
		if (!reg.test(num)) return false; 
		num = RegExp.$1 + RegExp.$2 + RegExp.$3; 
		var cVal = 0; 
		for (var i=0; i<8; i++) { 
			var cKeyNum = parseInt(((_tmp = i % 3) == 0) ? 1 : ( _tmp  == 1 ) ? 3 : 7); 
			cVal += (parseFloat(num.substring(i,i+1)) * cKeyNum) % 10; 
		} 
		var li_temp = parseFloat(num.substring(i,i+1)) * 5 + '0'; 
		cVal += parseFloat(li_temp.substring(0,1)) + parseFloat(li_temp.substring(1,2)); 
		return (parseInt(num.substring(9,10)) == 10-(cVal % 10)%10); 
	} 

	function is_ssn(J) {
		var J1, J2, dash;

		J1 = J.substring(0,6);
		J2 = J.substring(6,6+7);


		if(J1 =="111111" || J2 =="1111118"){
			return false;
		} else {
			// 주민등록번호 1 ~ 6 자리까지의 처리
			// 주민등록번호에 숫자가 아닌 문자가 있을 때 처리
			for(i=0;i<J1.length;i++){
				if (J1.charAt(i) >= 0 && J1.charAt(i) <= 9) {
					// 숫자면 값을 곱해 더한다.
					if(i == 0){
						SUM = (i+2) * J1.charAt(i);
					}else{ 
						SUM = SUM +(i+2) * J1.charAt(i);
					}
				}else{
					// 숫자가 아닌 문자가 있을 때의 처리
					return false;
				}
			}
			for(i=0;i<2;i++){
				// 주민등록번호 7 ~ 8 자리까지의 처리
				if (J2.charAt(i) >= 0 && J2.charAt(i) <= 9) {
					SUM = SUM + (i+8) * J2.charAt(i);
				}else{
					// 숫자가 아닌 문자가 있을 때의 처리
					return false;
				}
			}
			for(i=2;i<6;i++){
				// 주민등록번호 9 ~ 12 자리까지의 처리
				if (J2.charAt(i) >= 0 && J2.charAt(i) <= 9) {
					SUM = SUM + (i) * J2.charAt(i);
				}else{
					// 숫자가 아닌 문자가 있을 때의 처리
					return false;
				}
			}
			// 나머지 구하기
			var checkSUM = SUM % 11;
			// 나머지가 0 이면 10 을 설정
			if(checkSUM == 0){
				var checkCODE = 10;
				// 나머지가 1 이면 11 을 설정
			}else if(checkSUM ==1){
				var checkCODE = 11;
			}else{
				var checkCODE = checkSUM;
			}
			// 나머지를 11 에서 뺀다
			var check1 = 11 - checkCODE;
			if (J2.charAt(6) >= 0 && J2.charAt(6) <= 9) {
				var check2 = parseInt(J2.charAt(6))
			}else{
				// 숫자가 아닌 문자가 있을 때의 처리
				return false;
			}
			if(check1 != check2){
				// 주민등록번호가 틀릴 때의 처리
				return false;
			}else{
				return true;
			} 
		}
	}

	function checkspace(id)
	{
		if (id.indexOf(" ") >= 0) return false;
		return true;
	}

	var issubmit = false;
	function FormCheck(form) { 
			var regNum =/^[0-9]+$/; 
			var regPhone =/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{3,4}$/; 
			var regMail =/^[_a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+\.[a-zA-Z]+$/; 
			var regDomain =/^ftp|http|https:\/\/[\.a-zA-Z0-9-]+\.[a-zA-Z]+$/; 
			var regAlpha =/^[a-zA-Z]+$/; 
			var regIdPass =/^[a-zA-Z0-9_]+$/; 
			var regHost =/^[a-zA-Z-]+$/; 
			var regHangul =/[가-힣]/; 
			var regHangulOnly =/^[가-힣 ]*$/; 

			if(issubmit) {
				//alert("처리중입니다. 잠시만 기다려 주세요.");
				//return false;
			}

			for(var i = 0;i < form.elements.length;i++) { 
					var currEl = form.elements[i]; 

					if(currEl.getAttribute("required") != null && currEl.getAttribute("required")!="" ) { 
							
						if (currEl.option == "check" && currEl.value == "")
						{
							return ErrMsg(currEl,'check',form); 
						}
							if (trim(currEl.value).length < 1) { 
									return ErrMsg(currEl,'',form); 
							} 
					}
					
					if(currEl.getAttribute("requireds") != null ) { 
						
						if (trim(currEl.value).length < 1) { 
							
							if (currEl.getAttribute("title")!="" && currEl.getAttribute("title")!=undefined)
							{
								alert(currEl.getAttribute("title"));
							}else{
								alert(currEl.hname + " 항목은 필수입니다.");
							}
							return false;
						} 
					}
					
					

					if(currEl.getAttribute("option") != null && currEl.value != "") { 
							if(currEl.option == "email" && !regMail.test(currEl.value)) { 
									return ErrMsg(currEl, "email", form); 
							} 
							if(currEl.option == "domain" && !regDomain.test(currEl.value)) { 
									return ErrMsg(currEl, "domain", form); 
							} 
							if(currEl.option == "phone" && !regPhone.test(currEl.value)) { 
									return ErrMsg(currEl, "phone", form); 
							} 
							if(currEl.option == "hangul" && !regHangulOnly.test(currEl.value)) { 
									return ErrMsg(currEl, "hangul", form); 
							} 
							if(currEl.option == "idpass" && !regIdPass.test(currEl.value)) { 
									return ErrMsg(currEl, "idpass", form); 
							} 
							if(currEl.option == "number" && !regNum.test(currEl.value)) { 
									return ErrMsg(currEl, "number", form); 
							} 
							if(currEl.option == "ssn" && !is_ssn(currEl.value)) { 
									return ErrMsg(currEl, "ssn", form); 
							} 
							if(currEl.option == "binno" && !is_binNo(currEl.value)) { 
									return ErrMsg(currEl, "binno", form); 
							} 
					}
					if(currEl.getAttribute("option")=="dchk"){
						//중복확인한 값과 입력된 값이 같은지 체크
						chk_id = currEl.getAttribute("dchkid");
						chk_value = document.getElementById(chk_id).value;
						if (chk_value=="" || chk_value!=currEl.value)
						{
							return ErrMsg(currEl,"dchk",form);
						}
						
					}
					if(currEl.getAttribute("nospace") != null && !checkspace(currEl.value)) { 
							return ErrMsg(currEl, "nospace", form); 
					} 
					
					if(currEl.getAttribute("ssame") != null && currEl.value != "") { 
							ssameEI = eval("form." + currEl.ssame + ".value"); 
							if(currEl.value != ssameEI) { 
									return ErrMsg(currEl, "ssame", form); 
							} 
					} 
					
					if(currEl.getAttribute("minsize") != null && currEl.value != "") { 
							if(currEl.minsize > ByteCount(currEl.value)) { 
									return ErrMsg(currEl, "minsize", form); 
							} 
					} 
					if(currEl.getAttribute("minlength") != null && currEl.value != "") { 
							if(currEl.minlength > ByteCount(currEl.value)) { 
									return ErrMsg(currEl, "minlength", form); 
							} 
					} 

					if(currEl.getAttribute("maxsize") != null && currEl.value != "") { 
							if(currEl.maxsize < ByteCount(currEl.value)) { 
									return ErrMsg(currEl, "maxsize", form); 
							} 
					} 					

					if(currEl.getAttribute("maxlength") != null && currEl.value != "") { 
							if(currEl.getAttribute("maxlength") < ByteCount(currEl.value)) { 
									return ErrMsg(currEl, "maxlength", form); 
							} 
					} 					
			} 
		//var msg = "입력하신 내용을 전송하시겠습니까?";
		//if (confirm(msg)) {
			issubmit=true;

			return true;
		//}
		//return false;

	} 
	function ErrMsg(el, type, form) { 
	    var bgColor = '#FEFCEF'; 
		var name = (el.hname) ? el.hname : el.name; 
		var 	focus_target = el;
		var focus_target_id="";
		switch(type) { 
			case "ssame": 
					eval("var samename = (form."+el.ssame+".hname) ? form."+el.ssame+".hname : form."+el.ssame+".name");
					alert("'"+ name + "' 항목은 '" + samename + "' 항목과 같아야 합니다."); 
					break; 
			case "email": 
					alert("'"+ name + "'의 형식이 올바르지 않습니다."); 
					break;  
			case "dchk": 
					focus_target_id = el.dchkid;
					focus_target = document.getElementById(focus_target_id);
					alert("이미 사용중인 "+ name + " 이거나, 중복확인을 하지 않으셨습니다."); 
					break;
			case "domain": 
					alert("'"+ name + "'의 형식이 올바르지 않습니다\n\n02-1234-5678형식으로 입력하세요."); 
					break; 
			case "phone": 
					alert("'"+ name + "'의 형식이 올바르지 않습니다"); 
					break; 
			case "number": 
					alert("'"+ name + "' 항목은 숫자만 입력하실 수 있습니다.");
					break;
			case "hangul": 
					alert("'"+ name + "' 항목은 한글만 입력할 수 있습니다"); 
					break; 
			case "english": 
					alert("'"+ name + "' 항목은 영문만 입력하실 수 있습니다"); 
					break; 
			case "idpass": 
					alert("'"+ name + "' 항목은 영문, 숫자, _ 만 입력하실 수 있습니다"); 
					break; 
			case "minlength": 
					alert("'"+ name + "' 항목은 " + el.getAttribute("minlength") + "자 이상이어야 합니다."); 
					break; 
			case "minsize": 
					alert("'"+ name + "' 항목은 " + el.getAttribute("minsize") + "자 이상이어야 합니다."); 
					break; 
			case "maxsize": 
					alert("'"+ name + "' 항목은 " + el.getAttribute("maxsize") + "자 이하이어야 합니다."); 
					break; 
			case "maxlength": 
					alert("'"+ name + "' 항목은 " + el.getAttribute("maxlength") + "자 이하이어야 합니다."); 
					break; 
			case "ssn": 
					alert("주민등록번호가 올바르지 않습니다."); 
					break; 
			case "binno": 
					alert("사업자등록번호가 올바르지 않습니다."); 
					break; 
			case "nospace": 
					alert("'"+ name+"' 항목에는 빈칸이 올 수 없습니다."); 
					break; 
			case "check": 
					alert("'"+ name+"' (을)를 체크해 주세요"); 
					return false; 
			default: 
						if (el.getAttribute("title")!="" && el.getAttribute("title")!=null)
					{
						alert(el.getAttribute("title"));
					}else{
						alert("'"+ name + "' 항목은 필수입니다."); 
					}
					break; 
		} 
		if (focus_target.style.display!="none" && focus_target.type!="hidden")
		{
					focus_target.focus(); 
		}

		return false; 
	} 


/*데이터 입력 유효성 검사*/

function checkValue(opt,v){
	var pattern = new Array();

	pattern["id"] = /^[a-zA-Z]{1}[a-zA-Z0-9]{3,49}$/g;

	switch(opt){
		case "id":	return pattern[opt].test(v);		break;
	}
}
// IP 입력 유효성 검사
function valid_ip(ip) {
	if( ip.match("^[0-9]{1,3}(\.)[0-9]{1,3}(\.)[0-9]{1,3}(\.)[0-9]{1,3}$") == null ) {
		return false;
	} else {
		return true;
	}
}

function ipaddr_chk(ip_addr)
{
	var pattern = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/g;
	return pattern.test(ip_addr);
}


function pwd_chk(id_val){
	var pattern = /^[a-zA-Z0-9]{6,50}$/g;
	return pattern.test(id_val);
}

function email_chk(email_val){
	var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/g;
	return pattern.test(email_val);
}


function tel_no_chk(tel_no){
	var tel_pattern = /^[0-9]{2,4}-[0-9]{3,4}-[0-9]{4}$/g;
	var tel_valid = tel_pattern.test(tel_no);

	if (tel_valid) {
		// 동일 번호가 7자리 이상 반복되면 잘못된 전화 번호로 본다.
		var tel_only_no = tel_no.replace(/[-]/g, '');
		var dup_pattern = /([0]{7}|[1]{7}|[2]{7}|[3]{7}|[4]{7}|[5]{7}|[6]{7}|[7]{7}|[8]{7}|[9]{7})/g;
		var dup_value = dup_pattern.test(tel_only_no);

		if (dup_value) {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

function domain_chk(dom_name, type_chk){
	var pattern;

	if (type_chk == true) {
		pattern = /^[-0-9a-zA-Z]+(\.[-0-9a-zA-Z]+)*(\.[0-9a-zA-Z]+)+$/g;
	} else {
		pattern = /^[-0-9a-zA-Z]{1}[-0-9a-zA-Z]{1,128}$/g;
	}
	return pattern.test(dom_name);
}




// space 가 있으면 true, 없으면 false
function checkSpace( str )
{
     if(str.search(/\s/) != -1){
     	return true;
     } else {
        return false;
     }
}

function isValid_passwd( str, msgID )
{
     var cnt = 0;
     if( str.value )
	 {
		/* check whether input value is included space or not  */
		 var retVal = checkSpace(str.value);
		 if( retVal ) {
			 var Msg = "비밀번호에는 공백이 있으면 안됩니다.";
			 if(msgID && $("#"+msgID)){
				 $("#"+msgID).html(Msg);
			 }else{
				alert(Msg);
			 }
			 str.select();
			 return false;
		 }
		 for( var i=0; i < str.value.length; ++i)
		 {
			 if( str.value.charAt(0) == str.value.substring( i, i+1 ) ) ++cnt;
		 }
		 if( cnt == str.value.length ) {
			 var Msg = "보안상의 이유로 한 문자로 연속된 비밀번호는 허용하지 않습니다.";
			 if(msgID && $("#"+msgID)){
				 $("#"+msgID).html(Msg);
			 }else{
				alert(Msg);
			 }
			 str.value = "";
			 str.focus();
			 return false;
		 }
	
		 var isPW = /^[A-Za-z0-9`\-=\\\[\];',\./~!@#\$%\^&\*\(\)_\+|\{\}:"<>\?]{3,16}$/;
		 if( !isPW.test(str.value) ) {
			 var Msg = "비밀번호는 3~16자의 영문 대소문자와 숫자, 특수문자를 사용할 수 있습니다.";
			 if(msgID && $("#"+msgID)){
				 $("#"+msgID).html(Msg);
			 }else{
				alert(Msg);
			 }
			 str.select();
			 return false;
		 }
		 return true;
	 }
}



//##############################################################################//
// 한글/ 숫자만 입력받게 하는 함수
// STYLE="IME-MODE:ACTIVE;"		: 한글만 kr
// STYLE="IME-MODE:DISABLED;"	: 숫자만 nu
// STYLE="IME-MODE:INACTIVE;"	: 영문만 en
// 위 스타일과 같이 사용함
//##############################################################################//
function onlyNumber() { 
if ( ((event.keyCode < 48) || (57 < event.keyCode)) && (45 != event.keyCode) ) event.returnValue=false; 
} 
function onlyHan() { 
if ( (event.keyCode > 0) ) event.keyCode = '0'; return false; 
} 

function pre_set(form){

	for(var i = 0;i < form.elements.length;i++) { 
		var currEl = form.elements[i]; 
		if(currEl.getAttribute("option") != null && currEl.getAttribute("option")=="number") { 
			
			currEl.onkeydown=function(){

				if ((event.keyCode<48 || event.keyCode>57 ) && !(event.keyCode==8 ||event.keyCode==9 || event.keyCode==37 || event.keyCode==39 || event.keyCode==127 ||event.keyCode==27   ))				{
						return false;
				}
			}
		}
	}

}


